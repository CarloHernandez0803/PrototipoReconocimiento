<?php

namespace App\Http\Controllers;

use App\Models\Incidencia;
use Illuminate\Http\Request;

class PreguntaController extends Controller
{
    public function index()
    {
        $incidencias = Incidencia::all();
        return view('preguntas.index', compact('preguntas'));
    }

    public function create()
    {
        return view('preguntas.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'categoria' => 'required|in:Funcionalidad del Sistema,Reportes de Errores,Solicitudes de Mejora,Otros',
            'estado' => 'required|in:Pendiente,Respondida,Resuelta',
            'respuesta' => 'nullable|string',
            'usuario' => 'nullable|exists:Usuarios,id_usuario',
        ]);

        Pregunta::create($validated);

        return redirect()->route('preguntas.index')->with('success', 'Pregunta creada exitosamente');
    }

    public function show(string $id)
    {
        $pregunta = Pregunta::findOrFail($id);
        return view('preguntas.show', compact('pregunta'));
    }

    public function edit(string $id)
    {
        $pregunta = Pregunta::findOrFail($id);
        return view('preguntas.edit', compact('pregunta'));
    }

    public function update(Request $request, string $id)
    {
        $pregunta = Pregunta::findOrFail($id);
        
        $validated = $request->validate([
            'titulo' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
            'categoria' => 'nullable|in:Funcionalidad del Sistema,Reportes de Errores,Solicitudes de Mejora,Otros',
            'estado' => 'nullable|in:Pendiente,Respondida,Resuelta',
            'respuesta' => 'nullable|string',
            'usuario' => 'nullable|exists:Usuarios,id_usuario',
        ]);

        $pregunta->update($validated);

        return redirect()->route('preguntas.index')->with('success', 'Pregunta actualizada exitosamente');
    }

    public function destroy(string $id)
    {
        $pregunta = Pregunta::findOrFail($id);
        $pregunta->delete();

        return redirect()->route('preguntas.index')->with('success', 'Pregunta eliminada exitosamente');
    }
}
