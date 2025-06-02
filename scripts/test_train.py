import json
import subprocess

# Parámetros de ejemplo
params = {
    "rescale": 0.00392156862745098,  # 1/255
    "zoom_range": 0.2,
    "horizontal_flip": 1,
    "vertical_flip": 0,
    "altura": 100,
    "anchura": 100,
    "batch_size": 16,
    "clases": 5,
    "epocas": 2,  # Solo 2 épocas para prueba
    "pasos": 10,
    "kernels1": 16,
    "kernels2": 32,
    "kernels3": 64,
    "dropout_rate": 0.3
}

# Convertir a JSON
params_json = json.dumps(params)

# Ejecutar script
result = subprocess.run(
    ['python', 'train_cnn.py', params_json],
    capture_output=True,
    text=True
)

# Mostrar resultados
print("Salida:", result.stdout)
print("Errores:", result.stderr)