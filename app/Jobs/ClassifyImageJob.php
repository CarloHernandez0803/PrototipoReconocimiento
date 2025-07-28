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
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class ClassifyImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 300;
    protected $classificationId;

    public function __construct(string $classificationId)
    {
        $this->classificationId = $classificationId;
    }

    public function handle(): void
    {
        Log::info("Iniciando ClassifyImageJob para el ID de caché: {$this->classificationId}");
        
        $jobData = Cache::get($this->classificationId);
        if (!$jobData) {
            Log::error("No se encontraron datos en caché para el ID: {$this->classificationId}");
            return;
        }
        
        $historial = Historial::findOrFail($jobData['historial_id']);
        $paramsFile = storage_path('logs/' . $this->classificationId . '_params.json');
        $ruta_imagen_absoluta = public_path($jobData['imagen_path']);

        try {
            file_put_contents($paramsFile, $historial->hiperparametros);

            $ruta_pesos = storage_path("app/public/models/{$historial->id_historial}/{$historial->pesos}");

            $arguments = [
                'C:\Users\carlo\.conda\envs\prototipo\python.exe',
                base_path('scripts/clasificar_imagen.py'),
                '--params_file', $paramsFile,
                '--weights_path', $ruta_pesos,
                '--image_path', $ruta_imagen_absoluta,
            ];

            $process = new Process($arguments);
            $process->setEnv(['PYTHONHASHSEED' => 0]);
            $process->setTimeout(120);
            $process->run();

            if (!$process->isSuccessful()) {
                throw new \RuntimeException('Error en script de Python: ' . $process->getErrorOutput());
            }

            $output = $process->getOutput();
            $resultado = json_decode($output, true);

            if ($resultado === null) {
                throw new \RuntimeException("La salida del script no fue un JSON válido. Salida: '{$output}'");
            }
            if (isset($resultado['error'])) {
                throw new \RuntimeException('Script de Python devolvió un error: ' . $resultado['error']);
            }

            $jobData['estado'] = 'completado';
            $jobData['resultado'] = $resultado;
            Cache::put($this->classificationId, $jobData, now()->addMinutes(10));

            Log::info("ClassifyImageJob completado para el ID de caché: {$this->classificationId}");

        } catch (\Exception $e) {
            Log::error("Error en ClassifyImageJob para ID {$this->classificationId}: " . $e->getMessage());
            $jobData['estado'] = 'error';
            Cache::put($this->classificationId, $jobData, now()->addMinutes(10));
            $this->fail($e);
        } finally {
            if (File::exists($paramsFile)) {
                File::delete($paramsFile);
            }
        }
    }
}