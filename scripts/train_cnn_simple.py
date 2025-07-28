import os
import sys
import json
import time
import logging
import argparse
import shutil
from ftplib import FTP, error_perm
from datetime import datetime

# --- Variables de Entorno y otras importaciones ---
os.environ['TF_CPP_MIN_LOG_LEVEL'] = '2'
os.environ['TF_ENABLE_ONEDNN_OPTS'] = '0'

from tensorflow.keras.preprocessing.image import ImageDataGenerator
from tensorflow.keras.models import Sequential
from tensorflow.keras.layers import Dense, Dropout, Flatten, Conv2D, MaxPooling2D
from tensorflow.keras.optimizers import Adam
from tensorflow.keras.callbacks import Callback
import numpy as np

np.random.seed(0)

def update_progress(file_path, status, **kwargs):
    progress = {'status': status, 'timestamp': time.time()}
    progress.update(kwargs)
    try:
        with open(file_path, 'w') as f:
            json.dump(progress, f, indent=4)
    except IOError as e:
        logging.error(f"No se pudo escribir en el archivo de progreso: {e}")
        
class ProgressCallback(Callback):
    def __init__(self, progress_file, total_epochs):
        super().__init__()
        self.progress_file = progress_file
        self.total_epochs = total_epochs

    def on_epoch_end(self, epoch, logs=None):
        logs = logs or {}
        current_epoch = epoch + 1
        percent = int(25 + (current_epoch / self.total_epochs) * 70)
        
        update_progress(
            self.progress_file,
            'training',
            message=f"Entrenando... Época {current_epoch} de {self.total_epochs}",
            percent=min(95, percent),
            current_epoch=current_epoch,
            total_epochs=self.total_epochs,
            accuracy=logs.get('accuracy'),
            loss=logs.get('loss'),
            val_accuracy=logs.get('val_accuracy'),
            val_loss=logs.get('val_loss')
        )

def download_ftp_directory(ftp, remote_path, local_path):
    if not os.path.exists(local_path):
        os.makedirs(local_path)
    for item_name, facts in ftp.mlsd(path=remote_path, facts=['type']):
        if item_name in ('.', '..'):
            continue
        local_item_path = os.path.join(local_path, item_name)
        remote_item_path = f"{remote_path}/{item_name}"
        if facts['type'] == 'dir':
            download_ftp_directory(ftp, remote_item_path, local_item_path)
        elif facts['type'] == 'file':
            with open(local_item_path, 'wb') as local_file:
                ftp.retrbinary(f"RETR {remote_item_path}", local_file.write)

def train(params, ftp_config, output_dir, progress_file, historial_id):
    start_time = time.time()
    local_dataset_dir = os.path.join(output_dir, 'dataset')

    try:
        # ... (La primera parte de la función `train` no cambia: logging, descarga, generadores, etc.) ...
        log_file = os.path.join(output_dir, f'training_{historial_id}.log')
        logging.basicConfig(level=logging.INFO, format='%(asctime)s - %(levelname)s - %(message)s', handlers=[logging.FileHandler(log_file), logging.StreamHandler()])
        logging.info(f"Iniciando entrenamiento para Historial ID: {historial_id}")
        update_progress(progress_file, 'downloading', message="Descargando datasets...", percent=10)
        
        train_path = os.path.join(local_dataset_dir, 'Entrenamiento')
        validation_path = os.path.join(local_dataset_dir, 'Validacion')

        logging.info("Conectando al servidor FTP...")
        with FTP(ftp_config['ftp_host'], timeout=None) as ftp:
            ftp.login(user=ftp_config['ftp_user'], passwd=ftp_config['ftp_pass'])
            ftp.set_pasv(True)
            logging.info(f"Descargando de '{ftp_config['ftp_train_dir']}' a '{train_path}'")
            download_ftp_directory(ftp, ftp_config['ftp_train_dir'], train_path)
            logging.info(f"Descargando de '{ftp_config['ftp_validation_dir']}' a '{validation_path}'")
            download_ftp_directory(ftp, ftp_config['ftp_validation_dir'], validation_path)
        logging.info("Descarga completada.")

        if not os.path.exists(train_path) or not os.path.exists(validation_path):
            raise FileNotFoundError("Las carpetas 'Entrenamiento' y 'Validacion' no se encontraron.")

        update_progress(progress_file, 'preparing', message="Preparando generadores de imágenes...", percent=20)
        
        train_datagen = ImageDataGenerator(rescale=params.get('rescale', 1./255))
        validation_datagen = ImageDataGenerator(rescale=params.get('rescale', 1./255))
        train_generator = train_datagen.flow_from_directory(train_path, target_size=(params['altura'], params['anchura']), batch_size=params['batch_size'], class_mode='categorical')
        validation_generator = validation_datagen.flow_from_directory(validation_path, target_size=(params['altura'], params['anchura']), batch_size=params['batch_size'], class_mode='categorical')
        
        logging.info("Construyendo la arquitectura de la red neuronal...")
        model = Sequential()
        model.add(Conv2D(params['kernels1'], (3,3), padding="same", input_shape=(params['altura'], params['anchura'], 3), activation='relu'))
        model.add(MaxPooling2D(pool_size=(2,2)))
        model.add(Conv2D(params['kernels2'], (3,3), padding="same", activation='relu'))
        model.add(MaxPooling2D(pool_size=(2,2)))
        model.add(Conv2D(params['kernels3'], (3,3), padding="same", activation='relu'))
        model.add(MaxPooling2D(pool_size=(2,2)))
        model.add(Flatten())
        model.add(Dense(256, activation='relu'))
        model.add(Dropout(params.get('dropout_rate', 0.5)))
        model.add(Dense(params['clases'], activation='softmax'))
        model.compile(loss='categorical_crossentropy', optimizer=Adam(learning_rate=params.get('learning_rate', 0.001)), metrics=['accuracy'])
        model.summary(print_fn=logging.info)

        update_progress(progress_file, 'training', message="El entrenamiento ha comenzado...", percent=25)
        progress_reporter = ProgressCallback(progress_file, params['epocas'])
        logging.info("Iniciando el proceso model.fit()...")
        
        history = model.fit(
            train_generator,
            epochs=params['epocas'],
            validation_data=validation_generator,
            verbose=2,
            callbacks=[progress_reporter]
        )
        
        update_progress(progress_file, 'saving', message="Guardando modelo...", percent=98)
        
        # 1. Obtener la fecha actual en formato YYYYMMDD (ej. 20250725)
        date_str = datetime.now().strftime("%Y%m%d")
        
        # 2. Construir los nuevos nombres de archivo
        model_filename = f"modelo_entrenado_{historial_id}_{date_str}.h5"
        weights_filename = f"pesos_{historial_id}_{date_str}.h5"
        
        model_path = os.path.join(output_dir, model_filename)
        weights_path = os.path.join(output_dir, weights_filename)
        
        logging.info(f"Guardando modelo en: {model_path}")
        logging.info(f"Guardando pesos en: {weights_path}")
        
        model.save(model_path)
        model.save_weights(weights_path)
        
        final_accuracy = history.history['val_accuracy'][-1] if history.history['val_accuracy'] else 0
        final_loss = history.history['val_loss'][-1] if history.history['val_loss'] else 0

        # 3. Enviar los nuevos nombres de archivo al reporte de progreso
        update_progress(
            progress_file, 'completed',
            message="Entrenamiento completado exitosamente.",
            model_file=model_filename,
            weights_file=weights_filename,
            accuracy=float(final_accuracy),
            loss=float(final_loss),
            training_time=time.time() - start_time,
            percent=100
        )
        # ------------------------------------
        
        logging.info("Proceso completado con éxito.")

    except Exception as e:
        error_msg = f"ERROR INESPERADO: {str(e)}"
        logging.error(error_msg, exc_info=True)
        update_progress(progress_file, 'error', message=error_msg)
        sys.exit(1)
    
    finally:
        if os.path.exists(local_dataset_dir):
            logging.info(f"Limpiando directorio del dataset: {local_dataset_dir}")
            shutil.rmtree(local_dataset_dir)

if __name__ == "__main__":
    parser = argparse.ArgumentParser(description="Script de entrenamiento de CNN simplificado.")
    parser.add_argument('--params_file', type=str, required=True)
    parser.add_argument('--ftp_config_file', type=str, required=True)
    parser.add_argument('--output_dir', type=str, required=True)
    parser.add_argument('--progress_file', type=str, required=True)
    parser.add_argument('--historial_id', type=str, required=True)
    
    args = parser.parse_args()
    
    try:
        with open(args.params_file, 'r') as f:
            params_dict = json.load(f)
        with open(args.ftp_config_file, 'r') as f:
            ftp_config_dict = json.load(f)
        train(params_dict, ftp_config_dict, args.output_dir, args.progress_file, args.historial_id)
    except Exception as e:
        update_progress(args.progress_file, 'error', message=f"Error crítico al iniciar: {str(e)}")
        sys.exit(1)