#!/usr/bin/env python3
import os
import sys
import json
import traceback
import tempfile
import numpy as np
from io import BytesIO
import paramiko
import stat  # Importación faltante para _is_directory
from keras.preprocessing.image import ImageDataGenerator
from keras.models import Sequential
from keras.layers import Conv2D, MaxPooling2D, Flatten, Dense, Dropout
from keras.optimizers import Adam
from keras.callbacks import EarlyStopping, ModelCheckpoint
import logging

# Configuración de logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(levelname)s - %(message)s',
    stream=sys.stderr
)
logger = logging.getLogger(__name__)

class FTPClient:
    def __init__(self):
        self.host = os.getenv('FTP_HOST')
        self.user = os.getenv('FTP_USER')
        self.passwd = os.getenv('FTP_PASS')
        self.port = 22  # SFTP usa puerto 22
    
    def download_directory(self, remote_path, local_path):
        """Descarga recursiva de directorios usando SFTP"""
        try:
            transport = paramiko.Transport((self.host, self.port))
            transport.connect(username=self.user, password=self.passwd)
            sftp = paramiko.SFTPClient.from_transport(transport)
            
            os.makedirs(local_path, exist_ok=True)
            
            for item in sftp.listdir(remote_path):
                remote_item = f"{remote_path}/{item}"
                local_item = os.path.join(local_path, item)
                
                try:
                    if self._is_directory(sftp, remote_item):
                        self.download_directory(remote_item, local_item)
                    elif item.lower().endswith(('.jpg', '.jpeg', '.png')):
                        with BytesIO() as buf:
                            sftp.getfo(remote_item, buf)
                            buf.seek(0)
                            with open(local_item, 'wb') as f:
                                f.write(buf.getvalue())
                            logger.info(f"Descargado: {remote_item}")
                except Exception as e:
                    logger.error(f"Error procesando {remote_item}: {str(e)}")
                    continue
            
            sftp.close()
            transport.close()
            return True
        
        except Exception as e:
            logger.error(f"Error SFTP: {str(e)}")
            return False
    
    def _is_directory(self, sftp, path):
        """Verifica si un path remoto es directorio"""
        try:
            return stat.S_ISDIR(sftp.stat(path).st_mode)
        except:
            return False
    
    def upload_model(self, local_model_path, local_weights_path):
        """Sube los archivos del modelo al servidor"""
        try:
            transport = paramiko.Transport((self.host, self.port))
            transport.connect(username=self.user, password=self.passwd)
            sftp = paramiko.SFTPClient.from_transport(transport)
            
            # Subir modelo
            with open(local_model_path, 'rb') as f:
                sftp.putfo(f, 'modelo.cnn')
            
            # Subir pesos
            with open(local_weights_path, 'rb') as f:
                sftp.putfo(f, 'pesos.cnn')
            
            sftp.close()
            transport.close()
            return True
        except Exception as e:
            logger.error(f"Error subiendo modelo: {str(e)}")
            return False

def create_model(params, input_shape, num_classes):
    """Crea la arquitectura del modelo CNN"""
    model = Sequential([
        Conv2D(params['kernels1'], (3,3), activation='relu', padding='same', input_shape=input_shape),
        MaxPooling2D((2,2)),
        Conv2D(params['kernels2'], (3,3), activation='relu', padding='same'),
        MaxPooling2D((2,2)),
        Conv2D(params['kernels3'], (3,3), activation='relu', padding='same'),
        MaxPooling2D((2,2)),
        Flatten(),
        Dense(128, activation='relu'),
        Dropout(params['dropout_rate']),
        Dense(num_classes, activation='softmax')
    ])
    
    model.compile(
        optimizer=Adam(learning_rate=0.001),
        loss='categorical_crossentropy',
        metrics=['accuracy']
    )
    
    return model

def main():
    try:
        # 1. Cargar parámetros desde Laravel
        params = json.loads(sys.argv[1])
        logger.info("Parámetros recibidos")
        
        # 2. Configurar entorno temporal
        with tempfile.TemporaryDirectory() as temp_dir:
            logger.info(f"Directorio temporal creado: {temp_dir}")
            
            # 3. Descargar datos de entrenamiento
            ftp = FTPClient()
            train_dir = os.path.join(temp_dir, 'train')
            remote_train_path = '/datasets/entrenamientos/'
            
            if not ftp.download_directory(remote_train_path, train_dir):
                raise Exception("Error descargando datos de entrenamiento")
            
            logger.info("Datos de entrenamiento descargados")
            
            # 4. Configurar generador de imágenes
            train_datagen = ImageDataGenerator(
                rescale=params['rescale'],
                zoom_range=params['zoom_range'],
                horizontal_flip=bool(params['horizontal_flip']),
                vertical_flip=bool(params['vertical_flip']),
                validation_split=0.0
            )
            
            train_generator = train_datagen.flow_from_directory(
                train_dir,
                target_size=(params['altura'], params['anchura']),
                batch_size=params['batch_size'],
                class_mode='categorical',
                shuffle=True
            )
            
            # 5. Verificar clases
            if len(train_generator.class_indices) != params['clases']:
                raise ValueError(
                    f"Número de clases no coincide. Esperado: {params['clases']}, "
                    f"Encontrado: {len(train_generator.class_indices)}. "
                    f"Clases: {list(train_generator.class_indices.keys())}"
                )
            
            # 6. Crear y entrenar modelo
            input_shape = (params['altura'], params['anchura'], 3)
            model = create_model(params, input_shape, params['clases'])
            
            logger.info("Iniciando entrenamiento...")
            
            history = model.fit(
                train_generator,
                steps_per_epoch=min(100, train_generator.samples // train_generator.batch_size),
                epochs=params['epocas'],
                verbose=1,
                callbacks=[
                    EarlyStopping(monitor='loss', patience=5),
                    ModelCheckpoint(
                        os.path.join(temp_dir, 'best_model.h5'),
                        monitor='accuracy',
                        save_best_only=True
                    )
                ]
            )
            
            # 7. Guardar y subir modelo
            model_path = os.path.join(temp_dir, 'modelo_final.h5')
            weights_path = os.path.join(temp_dir, 'pesos_final.h5')
            
            model.save(model_path)
            model.save_weights(weights_path)
            
            if not ftp.upload_model(model_path, weights_path):
                raise Exception("Error subiendo modelo al servidor")
            
            # 8. Preparar resultados
            result = {
                'status': 'success',
                'accuracy': float(history.history['accuracy'][-1]),
                'loss': float(history.history['loss'][-1]),
                'classes': list(train_generator.class_indices.keys()),
                'samples_per_class': {
                    cls: count for cls, count in zip(
                        train_generator.class_indices.keys(),
                        np.bincount(train_generator.classes)
                    )
                },
                'total_samples': train_generator.samples
            }
            
            print(json.dumps(result))
    
    except Exception as e:
        error_msg = {
            'status': 'error',
            'message': str(e),
            'traceback': traceback.format_exc()
        }
        logger.error(json.dumps(error_msg, indent=2))
        print(json.dumps(error_msg))
        sys.exit(1)

if __name__ == "__main__":
    main()