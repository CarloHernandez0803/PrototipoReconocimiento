import os
import logging
from ftplib import FTP

# Configurar logging
logging.basicConfig(
    level=logging.DEBUG,
    format='%(asctime)s - %(levelname)s - %(message)s'
)

# Configuración (usa tus credenciales reales)
FTP_CONFIG = {
    'host': '',
    'user': '',
    'passwd': '',
    'port': 21,
    'remote_path': '/datasets/entrenamientos'  # Ajusta esta ruta
}

def test_connection():
    """Prueba básica de conexión FTP"""
    try:
        ftp = FTP()
        ftp.connect(FTP_CONFIG['host'], FTP_CONFIG['port'])
        ftp.login(FTP_CONFIG['user'], FTP_CONFIG['passwd'])
        print("¡Conexión FTP exitosa!")
        
        # Listar contenido del directorio
        print("\nContenido del directorio raíz:")
        print(ftp.nlst())
        
        # Verificar directorio de entrenamientos
        print(f"\nContenido de {FTP_CONFIG['remote_path']}:")
        ftp.cwd(FTP_CONFIG['remote_path'])
        print(ftp.nlst())
        
        ftp.quit()
        return True
    except Exception as e:
        print(f"Error en conexión FTP: {str(e)}")
        return False

if __name__ == "__main__":
    print("=== Iniciando prueba de FTP ===")
    if test_connection():
        print("\nPrueba completada con éxito")
    else:
        print("\nPrueba fallida")