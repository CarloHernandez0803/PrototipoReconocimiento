import os
import sys
import json
import traceback
import tempfile
import numpy as np
from ftplib import FTP  # Volvemos a FTP estándar
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
        self.host = os.getenv('FTP_HOST', 'ftpupload.net')
        self.user = os.getenv('FTP_USERNAME', 'ezyro_38892559')
        self.passwd = os.getenv('FTP_PASSWORD', 'd65b1ec10a1')
        self.port = int(os.getenv('FTP_PORT', '21'))
        self.timeout = int(os.getenv('FTP_TIMEOUT', '60'))
        self.ftp = None  # Inicializamos la conexión aquí
        
        if not all([self.host, self.user, self.passwd]):
            missing = []
            if not self.host: missing.append('FTP_HOST')
            if not self.user: missing.append('FTP_USERNAME')
            if not self.passwd: missing.append('FTP_PASSWORD')
            raise ValueError(f"Credenciales FTP faltantes: {', '.join(missing)}")

    def connect(self):
        """Establece una única conexión persistente"""
        if self.ftp is not None:
            try:
                self.ftp.voidcmd("NOOP")
                return True
            except:
                self.ftp.quit()
                self.ftp = None
        
        try:
            self.ftp = FTP()
            self.ftp.connect(self.host, self.port, self.timeout)
            self.ftp.login(self.user, self.passwd)
            logger.info(f"Conexión FTP establecida con {self.user}@{self.host}")
            return True
        except Exception as e:
            logger.error(f"Error conectando a FTP: {str(e)}")
            if self.ftp:
                self.ftp.quit()
                self.ftp = None
            return False

    def download_directory(self, remote_path, local_path):
        """Versión corregida para manejo de rutas"""
        if not self.connect():
            return False
            
        try:
            # Normalizar rutas
            remote_path = remote_path.replace('\\', '/').strip('/')
            original_dir = self.ftp.pwd()
            
            try:
                # Intentar acceso directo
                self.ftp.cwd(remote_path)
            except Exception as e:
                # Si falla, intentar navegación paso a paso
                parts = remote_path.split('/')
                for part in parts:
                    try:
                        self.ftp.cwd(part)
                    except Exception as e:
                        logger.error(f"No se puede acceder a {part}: {str(e)}")
                        self.ftp.cwd(original_dir)
                        return False
            
            items = [item for item in self.ftp.nlst() if item not in ('.', '..')]
            os.makedirs(local_path, exist_ok=True)
            
            for item in items:
                try:
                    local_item = os.path.join(local_path, item)
                    
                    # Verificar si es directorio
                    is_dir = False
                    try:
                        self.ftp.cwd(item)
                        self.ftp.cwd('..')
                        is_dir = True
                    except:
                        pass
                    
                    if is_dir:
                        logger.info(f"Procesando directorio: {item}")
                        self.download_directory(f"{remote_path}/{item}", local_item)
                    elif item.lower().endswith(('.jpg', '.jpeg', '.png')):
                        logger.info(f"Descargando archivo: {item}")
                        with open(local_item, 'wb') as f:
                            self.ftp.retrbinary(f"RETR {item}", f.write)
                except Exception as e:
                    logger.warning(f"Error procesando {item}: {str(e)}")
                    continue
            
            self.ftp.cwd(original_dir)
            return True
            
        except Exception as e:
            logger.error(f"Error fatal en descarga: {str(e)}")
            return False

    def upload_model(self, local_model_path, local_weights_path):
        """Sube archivos manteniendo la conexión"""
        if not self.connect():
            return False
            
        try:
            # Subir modelo
            with open(local_model_path, 'rb') as f:
                self.ftp.storbinary('STOR modelo.cnn', f)
            # Subir pesos
            with open(local_weights_path, 'rb') as f:
                self.ftp.storbinary('STOR pesos.cnn', f)
            return True
        except Exception as e:
            logger.error(f"Error subiendo archivos: {str(e)}")
            return False

    def __del__(self):
        """Destructor para cerrar conexión"""
        if self.ftp is not None:
            self.ftp.quit()

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
            remote_train_path = 'datasets/entrenamientos'
            
            if not ftp.download_directory(remote_train_path, train_dir):
                raise Exception("Error descargando datos de entrenamiento")
            
            logger.info("Datos de entrenamiento descargados")
            
            # 4. Configurar generador de imágenes
            train_datagen = ImageDataGenerator(
                rescale=params.get('rescale', 1./255),  # Valor por defecto
                zoom_range=params.get('zoom_range', 0.2),
                horizontal_flip=params.get('horizontal_flip', False),
                vertical_flip=params.get('vertical_flip', False),
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
            model_path = os.path.join(temp_dir, 'modelo.h5')
            weights_path = os.path.join(temp_dir, 'pesos.h5')
            
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