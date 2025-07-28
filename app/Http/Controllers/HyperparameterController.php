<?php

namespace App\Http\Controllers;

use App\Jobs\TrainCnnJob;
use App\Models\Historial;
use App\Models\SenEntrenamiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class HyperparameterController extends Controller
{
    public function index()
    {
        $historial = Historial::orderBy('fecha_creacion', 'desc')->paginate(10);
        return view('hyperparameters.index', compact('historial'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'epocas' => 'required|integer|min:1|max:500',
            'altura' => 'required|integer|min:32|max:256',
            'anchura' => 'required|integer|min:32|max:256',
            'batch_size' => 'required|integer|min:1|max:64',
            'clases' => 'required|integer|min:2|max:20',
            'learning_rate' => 'required|numeric|between:0.0001,0.1',
            'rescale' => 'required|numeric|between:0.001,1',
            'zoom_range' => 'required|numeric|between:0,0.5',
            'horizontal_flip' => 'required|boolean',
            'vertical_flip' => 'required|boolean',
            'kernels1' => 'required|integer|min:8|max:128',
            'kernels2' => 'required|integer|min:8|max:256',
            'kernels3' => 'required|integer|min:8|max:512',
            'dropout_rate' => 'required|numeric|between:0.1,0.9',
        ]);

        $validated['epocas'] = (int)$validated['epocas'];
        $validated['altura'] = (int)$validated['altura'];
        $validated['anchura'] = (int)$validated['anchura'];
        $validated['batch_size'] = (int)$validated['batch_size'];
        $validated['clases'] = (int)$validated['clases'];
        $validated['kernels1'] = (int)$validated['kernels1'];
        $validated['kernels2'] = (int)$validated['kernels2'];
        $validated['kernels3'] = (int)$validated['kernels3'];
        
        $validated['learning_rate'] = (float)$validated['learning_rate'];
        $validated['rescale'] = (float)$validated['rescale'];
        $validated['zoom_range'] = (float)$validated['zoom_range'];
        $validated['dropout_rate'] = (float)$validated['dropout_rate'];

        // Los booleanos a veces llegan como '1' o '0', los convertimos
        $validated['horizontal_flip'] = filter_var($validated['horizontal_flip'], FILTER_VALIDATE_BOOLEAN);
        $validated['vertical_flip'] = filter_var($validated['vertical_flip'], FILTER_VALIDATE_BOOLEAN);
        
        // Creamos el historial sin la columna 'estado'
        $historial = Historial::create([
            'hiperparametros' => json_encode($validated),
            'modelo' => 'pendiente',
            'pesos' => 'pendiente',
            'acierto' => 0,
            'perdida' => 0,
            'tiempo_entrenamiento' => 0,
            'usuario' => auth()->id(),
        ]);

        TrainCnnJob::dispatch($historial, $validated);

        return response()->json([
            'status' => 'success',
            'message' => 'El entrenamiento ha sido encolado y comenzará en breve.',
            'training_id' => $historial->id_historial
        ]);
    }

    public function show($id)
    {
        $historial = Historial::findOrFail($id);
        return view('hyperparameters.show', compact('historial'));
    }

    /**
     * Verificamos el estado del entrenamiento y actualizamos la BD
     * 
     * @param Request $request Recibe el id del entrenamiento a verificar
     * @return \Illuminate\Http\JsonResponse Actualiza la BD con el estado del entrenamiento y devuelve un JSON
     */
    public function checkProgress(Request $request)
    {
        $request->validate(['training_id' => 'required|integer']);
        $trainingId = $request->training_id;

        $progressFile = storage_path('app/training_progress_' . $trainingId . '.json');

        if (!File::exists($progressFile)) {
            return response()->json(['status' => 'pending', 'message' => 'Esperando a que el trabajador inicie la tarea...'], 202);
        }

        $progress = json_decode(File::get($progressFile), true);
        
        // Si el entrenamiento terminó, actualizamos la BD
        if (in_array($progress['status'], ['completed', 'error'])) {
            $historial = Historial::findOrFail($trainingId);
            
            // Ya no verificamos el estado, solo actualizamos si el modelo aún está 'pendiente'
            if ($historial->modelo === 'pendiente') {
                if ($progress['status'] === 'completed') {
                    $historial->update([
                        'modelo' => $progress['model_file'] ?? 'N/A',
                        'pesos' => $progress['weights_file'] ?? 'N/A',
                        'acierto' => ($progress['accuracy'] ?? 0) * 100,
                        'perdida' => $progress['loss'] ?? 0,
                        'tiempo_entrenamiento' => $progress['training_time'] ?? 0,
                    ]);
                }
            }
        }
        
        return response()->json($progress);
    }
}