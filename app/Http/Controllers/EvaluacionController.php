<?php

namespace App\Http\Controllers;

use App\Models\Evaluacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvaluacionController extends Controller
{
    public function index()
    {
        $evaluaciones = Evaluacion::paginate(10);
        return view('evaluaciones.index', compact('evaluaciones'));
    }

    public function create()
    {
        return view('evaluaciones.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'categoria_senal' => 'required|in:Semáforo,Restrictiva,Advertencia,Tráfico,Informativa',
            'senales_correctas' => 'required|integer',
            'senales_totales' => 'required|integer',
            'calificacion_media' => 'required|integer|min:1|max:5',
            'comentarios' => 'nullable|string',
        ]);

        $evaluacion = Evaluacion::create([
            ...$validated,
            'alumno' => Auth::id(),
        ]);

        return redirect()->route('evaluaciones.index')->with('success', 'Evaluacion a la red creada exitosamente');
    }

    public function show(string $id)
    {
        $evaluacion = Evaluacion::findOrFail($id);
        return view('evaluaciones.show', compact('evaluacion'));
    }

    public function edit(string $id)
    {
        $evaluacion = Evaluacion::findOrFail($id);
        return view('evaluaciones.edit', compact('evaluacion'));
    }

    public function update(Request $request, string $id)
    {
        $evaluacion = Evaluacion::findOrFail($id);
        
        $validated = $request->validate([
            'categoria_senal' => 'required|in:Semáforo,Restrictiva,Advertencia,Tráfico,Informativa',
            'senales_correctas' => 'required|integer',
            'senales_totales' => 'required|integer',
            'calificacion_media' => 'required|integer|min:1|max:5',
            'comentarios' => 'nullable|string',
        ]);

        $evaluacion->update($validated);

        return redirect()->route('evaluaciones.index')->with('success', 'Evaluacion a la red actualizada exitosamente');
    }

    public function destroy(string $id)
    {
        $evaluacion = Evaluacion::findOrFail($id);
        $evaluacion->delete();

        return redirect()->route('evaluaciones.index')->with('success', 'Evaluacion a la red eliminada exitosamente');
    }
}
