<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Historial;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class HyperparameterController extends Controller
{
    public function index()
    {
        $historial = Historial::latest()->paginate(10);
        return view('hyperparameters.index', compact('historial'));
    }

    public function create()
    {
        $lotes = SenEntrenamiento::all();
        return view('hyperparameters.create', compact('lotes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'epocas' => 'required|integer|min:1|max:500',
            'altura' => 'required|integer|min:32|max:256',
            'anchura' => 'required|integer|min:32|max:256',
            'batch_size' => 'required|integer|min:1|max:64',
            'pasos' => 'required|integer|min:10|max:1000',
            'clases' => 'required|integer|min:2|max:20',
            'rescale' => 'required|numeric|between:0.001,1',
            'zoom_range' => 'required|numeric|between:0,0.5',
            'horizontal_flip' => 'required|boolean',
            'vertical_flip' => 'required|boolean',
            'kernels1' => 'required|integer|min:8|max:128',
            'kernels2' => 'required|integer|min:8|max:256',
            'kernels3' => 'required|integer|min:8|max:512',
            'dropout_rate' => 'required|numeric|between:0.1,0.9',
        ]);

        try {
            // Eliminar modelo anterior si existe
            Storage::disk('ftp')->delete(['modelo.cnn', 'pesos.cnn']);

            // Ejecutar entrenamiento
            $process = new Process([
                'C:\ProgramData\anaconda3\envs\prototipo_cnn\python.exe',
                base_path('scripts/train_cnn.py'),
                json_encode($validated)
            ]);
            $process->setTimeout(3600); // 1 hora

            $output = '';
            $process->run(function ($type, $buffer) use (&$output) {
                $output .= $buffer;
                logger()->info($buffer); // Registrar en logs de Laravel
            });

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            $result = json_decode($process->getOutput(), true);

            // Registrar en historial
            Historial::create([
                'hiperparametros' => json_encode($validated),
                'modelo' => 'modelo.cnn',
                'pesos' => 'pesos.cnn',
                'acierto' => $result['accuracy'],
                'perdida' => $result['loss'],
                'tiempo_entrenamiento' => $result['training_time'] ?? null,
                'user_id' => auth()->id()
            ]);

            return redirect()->route('hyperparameters.index')
                ->with('success', 'Modelo entrenado y guardado correctamente');

        } catch (\Exception $e) {
            return back()->withInput()
                ->withErrors(['error' => 'Error en el entrenamiento: '.$e->getMessage()]);
        }
    }

    public function show($id)
    {
        $historial = Historial::findOrFail($id);
        return view('hyperparameters.show', compact('historial'));
    }
}