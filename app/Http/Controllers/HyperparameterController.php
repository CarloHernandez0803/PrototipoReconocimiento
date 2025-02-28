<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Historial;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class HyperparameterController extends Controller
{
    public function index()
    {
        $historial = Historial::orderBy('fecha_creacion', 'desc')->get();
        return view('hyperparameters.index', compact('historial'));
    }

    public function train(Request $request)
    {
        $validated = $request->validate([
            'epocas' => 'required|integer',
            'altura' => 'required|integer',
            'anchura' => 'required|integer',
            'batch_size' => 'required|integer',
            'pasos' => 'required|integer',
            'clases' => 'required|integer',
        ]);

        $process = new Process([
            'python3',
            base_path('scripts/train_cnn.py'),
            $validated['epocas'],
            $validated['altura'],
            $validated['anchura'],
            $validated['batch_size'],
            $validated['pasos'],
            $validated['clases'],
        ]);

        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        Historial::create([
            'hiperparametros' => json_encode($validated),
            'modelo' => 'cnn.h5',
            'pesos' => 'cnn_pesos.h5',
            'acierto' => 95.5, // Ejemplo
            'perdida' => 0.15, // Ejemplo
            'tiempo_entrenamiento' => 120, // Ejemplo
            'usuario' => auth()->id(),
        ]);

        return redirect()->route('hyperparameters.index')->with('success', 'Entrenamiento completado exitosamente');
    }

    public function details($id)
    {
        $historial = Historial::findOrFail($id);
        return view('hyperparameters.details', compact('historial'));
    }
}