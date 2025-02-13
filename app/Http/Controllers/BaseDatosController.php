<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BaseDatosController extends Controller
{
    public function index()
    {
        return view('base_datos.base_datos');
    }

    public function backup()
    {
        try 
        {
            // Nombre del archivo de respaldo
            $filename = "backup_" . now()->format('Y-m-d_H-i-s') . ".sql";

            // Escapar credenciales
            $username = escapeshellarg(env('DB_USERNAME'));
            $password = escapeshellarg(env('DB_PASSWORD'));
            $host = escapeshellarg(env('DB_HOST'));
            $database = escapeshellarg(env('DB_DATABASE'));

            // Ejecutar comando de respaldo y capturar la salida y errores
            $command = "mysqldump --user={$username} --password={$password} --host={$host} {$database} 2>&1";
            $output = shell_exec($command);

            if (empty($output)) {
                throw new \Exception("Error al ejecutar mysqldump: No se generó ningún contenido.");
            }

            // Verificar si hay errores en la salida
            if (strpos($output, 'ERROR') !== false) {
                throw new \Exception("Error en mysqldump: " . $output);
            }

            // Crear una respuesta de descarga
            return response()->streamDownload(
                function () use ($output) {
                    echo $output; // Enviar el contenido del respaldo al navegador
                },
                $filename,
                [
                    'Content-Type' => 'application/sql',
                    'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                ]
            );
        } 
        catch (\Exception $e) 
        {
            Log::error("Error en backup: " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function restore(Request $request)
    {
        try 
        {
            if (!$request->hasFile('backup_file')) {
                return response()->json(['message' => 'No se ha subido un archivo válido.'], 400);
            }

            $file = $request->file('backup_file');
            if ($file->getClientOriginalExtension() !== 'sql') {
                return response()->json(['message' => 'El archivo debe tener extensión .sql.'], 400);
            }

            // Leer el contenido del archivo
            $content = file_get_contents($file->getRealPath());

            // Escapar credenciales
            $username = escapeshellarg(env('DB_USERNAME'));
            $password = escapeshellarg(env('DB_PASSWORD'));
            $host = escapeshellarg(env('DB_HOST'));
            $database = escapeshellarg(env('DB_DATABASE'));

            // Ejecutar comando de restauración
            $command = "mysql --user={$username} --password={$password} --host={$host} {$database}";
            $process = proc_open($command, [
                0 => ['pipe', 'r'], // Entrada estándar (stdin)
                1 => ['pipe', 'w'], // Salida estándar (stdout)
                2 => ['pipe', 'w'], // Salida de error (stderr)
            ], $pipes);

            if (is_resource($process)) {
                fwrite($pipes[0], $content); // Escribir el contenido del archivo en stdin
                fclose($pipes[0]);

                $output = stream_get_contents($pipes[1]); // Capturar la salida estándar
                $error = stream_get_contents($pipes[2]); // Capturar la salida de error

                fclose($pipes[1]);
                fclose($pipes[2]);
                proc_close($process);

                if (!empty($error)) {
                    throw new \Exception("Error al restaurar la base de datos: " . $error);
                }

                return response()->json(['message' => 'Base de datos restaurada correctamente.']);
            } else {
                throw new \Exception("No se pudo iniciar el proceso de restauración.");
            }
        } 
        catch (\Exception $e) 
        {
            Log::error("Error en restore: " . $e->getMessage());
            return response()->json(['message' => 'Error al restaurar la base de datos.'], 500);
        }
    }
}