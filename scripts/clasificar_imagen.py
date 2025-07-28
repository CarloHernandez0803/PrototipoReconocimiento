import os
import sys
import json
import time
import argparse

# --- Configuración de Estabilidad ---
os.environ['CUDA_VISIBLE_DEVICES'] = '-1'
os.environ['TF_ENABLE_ONEDNN_OPTS'] = '0'
os.environ['TF_CPP_MIN_LOG_LEVEL'] = '2'

import numpy as np
np.random.seed(0)

from tensorflow.keras.models import Sequential
from tensorflow.keras.layers import Dense, Dropout, Flatten, Conv2D, MaxPooling2D
from tensorflow.keras.preprocessing.image import load_img, img_to_array

CLASS_NAMES = ['Advertencia', 'Informativa', 'Restrictiva', 'Semáforo', 'Tráfico']

def build_model(params):
    # (Esta función no necesita cambios)
    model = Sequential([
        Conv2D(params['kernels1'], (3,3), padding="same", input_shape=(params['altura'], params['anchura'], 3), activation='relu'),
        MaxPooling2D(pool_size=(2,2)),
        Conv2D(params['kernels2'], (3,3), padding="same", activation='relu'),
        MaxPooling2D(pool_size=(2,2)),
        Conv2D(params['kernels3'], (3,3), padding="same", activation='relu'),
        MaxPooling2D(pool_size=(2,2)),
        Flatten(),
        Dense(256, activation='relu'),
        Dropout(params.get('dropout_rate', 0.5)),
        Dense(params['clases'], activation='softmax')
    ])
    return model

def classify(params_path, weights_path, image_path):
    start_time = time.time()
    with open(params_path, 'r') as f:
        params = json.load(f)
    model = build_model(params)
    model.load_weights(weights_path)
    img = load_img(image_path, target_size=(params['altura'], params['anchura']))
    img_array = img_to_array(img)
    img_array /= 255.0
    img_array = np.expand_dims(img_array, axis=0)
    prediction = model.predict(img_array, verbose=0)[0]
    predicted_index = np.argmax(prediction)
    return {
        "clase": CLASS_NAMES[predicted_index],
        "confianza": float(prediction[predicted_index]),
        "tiempo": round(time.time() - start_time, 4)
    }

if __name__ == "__main__":
    parser = argparse.ArgumentParser(description="Clasifica una imagen.")
    parser.add_argument('--params_file', type=str, required=True)
    parser.add_argument('--weights_path', type=str, required=True)
    parser.add_argument('--image_path', type=str, required=True)
    # --- CAMBIO: Se elimina el argumento --log_file ---
    
    args = parser.parse_args()
    try:
        result = classify(args.params_file, args.weights_path, args.image_path)
        print(json.dumps(result))
    except Exception as e:
        print(json.dumps({"error": str(e)}))
        sys.exit(1)