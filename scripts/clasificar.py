# scripts/clasificar.py
import os
import sys
import json
import time
import numpy as np
from keras.models import load_model
from keras.preprocessing import image
from keras.applications.imagenet_utils import preprocess_input
from ftplib import FTP
import tempfile

def load_ftp_image(image_path, target_size=(100, 100)):
    """Carga imagen desde FTP sin guardar localmente"""
    ftp = FTP(os.getenv('FTP_HOST'))
    ftp.login(os.getenv('FTP_USER'), os.getenv('FTP_PASS'))
    
    with tempfile.NamedTemporaryFile(suffix='.jpg') as tmp:
        ftp.retrbinary(f'RETR {image_path}', tmp.write)
        img = image.load_img(tmp.name, target_size=target_size)
        x = image.img_to_array(img)
        return np.expand_dims(x, axis=0)

def main():
    image_path = sys.argv[1]  # Ej: 'datasets/pruebas/Semáforo/imagen1.jpg'
    
    try:
        # Cargar modelo único
        model = load_model('modelo.cnn')
        
        # Procesar imagen
        img_array = preprocess_input(load_ftp_image(image_path))
        
        # Clasificar
        start = time.time()
        preds = model.predict(img_array)
        end = time.time()
        
        # Obtener clase con mayor probabilidad
        class_idx = np.argmax(preds[0])
        confidence = preds[0][class_idx]
        
        print(json.dumps({
            'clase': ['Semáforo', 'Restrictiva', 'Advertencia', 'Tráfico', 'Informativa'][class_idx],
            'confianza': float(confidence),
            'tiempo': round(end - start, 4)
        }))
        
    except Exception as e:
        print(json.dumps({'error': str(e)}))
        sys.exit(1)

if __name__ == "__main__":
    main()