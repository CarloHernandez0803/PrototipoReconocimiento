<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Historial;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File; // Asegúrate de importar File

class TrainCnnJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $historial;
    protected $validatedData;

    public function __construct(Historial $historial, array $validatedData)
    {
        $this->historial = $historial;
        $this->validatedData = $validatedData;
    }

    public function handle(): void
    {
        $historialId = $this->historial->id_historial;
        Log::info("Iniciando TrainCnnJob para historial ID: {$historialId}");

        $outputDir = storage_path("app/public/models/{$historialId}");
        $progressFile = storage_path("app/training_progress_{$historialId}.json");
        File::ensureDirectoryExists($outputDir);

        // --- CAMBIO: CREAR ARCHIVO DE PROGRESO INICIAL ---
        // Esto le da al frontend una respuesta inmediata.
        file_put_contents($progressFile, json_encode([
            'status' => 'iniciado',
            'message' => 'Worker ha iniciado el proceso...',
            'percent' => 5,
            'timestamp' => time()
        ]));
        // --------------------------------------------------

        $paramsFile = $outputDir . '/params.json';
        $ftpConfigFile = $outputDir . '/ftp_config.json';
        
        try {
            file_put_contents($paramsFile, json_encode($this->validatedData));
            file_put_contents($ftpConfigFile, json_encode([
                'ftp_host' => env('FTP_HOST'),
                'ftp_user' => env('FTP_USERNAME'),
                'ftp_pass' => env('FTP_PASSWORD'),
                'ftp_train_dir' => 'datasets/entrenamientos',
                'ftp_validation_dir' => 'datasets/pruebas'
            ]));

            $python = 'C:\Users\carlo\.conda\envs\prototipo\python.exe';
            $script = base_path('scripts/train_cnn_simple.py');

            $arguments = [
                $python, $script,
                '--params_file', $paramsFile,
                '--ftp_config_file', $ftpConfigFile,
                '--output_dir', $outputDir,
                '--progress_file', $progressFile,
                '--historial_id', (string)$historialId,
            ];
            
            $process = new Process($arguments);
            $process->setWorkingDirectory(base_path());
            $process->setTimeout(3600);
            $process->run();

            if (!$process->isSuccessful()) {
                throw new \Exception("El proceso de Python falló: " . $process->getErrorOutput());
            }

            Log::info("TrainCnnJob completado exitosamente para historial ID: {$historialId}");

        } catch (\Exception $e) {
            Log::error("Error en TrainCnnJob para historial ID {$historialId}: " . $e->getMessage());
            $this->fail($e);
        } finally {
            if (File::exists($paramsFile)) File::delete($paramsFile);
            if (File::exists($ftpConfigFile)) File::delete($ftpConfigFile);
        }
    }
}