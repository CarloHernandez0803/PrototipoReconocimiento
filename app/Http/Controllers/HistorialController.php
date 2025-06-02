<?php

namespace App\Http\Controllers;

use App\Models\Historial;
use Illuminate\Http\Request;

class HistorialController extends Controller
{
    public function index()
    {
        $historial = Historial::orderBy('fecha_creacion', 'desc')->get();
        return view('modulo_entrenamiento.index', compact('mod_entrenamiento'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'learning_rate' => 'required|numeric|between:0,1',
            'batch_size' => 'required|integer|min:1',
            'epochs' => 'required|integer|min:1',
            'optimizer' => 'required|string|in:adam,sgd,rmsprop',
            'momentum' => 'required_if:optimizer,sgd|nullable|numeric|between:0,1',
            'model_architecture' => 'required|string|in:resnet50,vgg16,mobilenet',
            'acierto' => 'required|numeric|between:0,100',
            'perdida' => 'required|numeric|between:0,100',
            'tiempo_entrenamiento' => 'required|integer|min:1'
        ]);

        $hiperparametros = [
            'learning_rate' => $request->learning_rate,
            'batch_size' => $request->batch_size,
            'epochs' => $request->epochs,
            'optimizer' => $request->optimizer,
            'momentum' => $request->momentum,
            'model_architecture' => $request->model_architecture
        ];

        $modeloPath = "models/" . $request->model_architecture . "_" . date('Y-m-d_H-i-s') . ".h5";
        $pesosPath = "weights/" . $request->model_architecture . "_" . date('Y-m-d_H-i-s') . ".weights";

        $historial = Historial::create([
            'hiperparametros' => json_encode($hiperparametros),
            'modelo' => $modeloPath,
            'pesos' => $pesosPath,
            'acierto' => $request->acierto,
            'perdida' => $request->perdida,
            'tiempo_entrenamiento' => $request->tiempo_entrenamiento,
            'usuario' => Auth::id()
        ]);

        return redirect()->route('modulo_entrenamiento.index')->with('success', 'Entrenamiento registrado exitosamente.');
    }

    public function show($id)
    {
        $entrenamiento = Historial::with('usuario')->findOrFail($id);
            
        return view('modulo_entrenamiento.show', compact('entrenamiento'));
    }
}
