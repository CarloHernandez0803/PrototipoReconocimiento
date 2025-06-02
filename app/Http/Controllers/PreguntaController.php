<?php

namespace App\Http\Controllers;

use App\Models\Pregunta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PreguntaController extends Controller
{
    public function index()
    {
        $preguntas = Pregunta::paginate(10);
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
            'estado' => 'Pendiente',
            'respuesta' => 'nullable|string',
        ]);

        $pregunta = Pregunta::create([
            ...$validated,
            'usuario' => Auth::id(),
        ]);

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
            'fecha_act' => 'nullable|date',
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
