<?php

namespace App\Http\Controllers;

use App\Models\Experiencia;
use Illuminate\Http\Request;

class ExperienciaController extends Controller
{
    public function index()
    {
<<<<<<< HEAD
        $experiencias = Experiencia::all();
=======
        $experiencias = Experiencia::paginate(10);
>>>>>>> 202c96f (Quinta version proyecto)
        return view('experiencias.index', compact('experiencias'));
    }

    public function create()
    {
        return view('experiencias.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipo_experiencia' => 'required|in:Positiva,Negativa,Neutra',
            'descripcion' => 'required|string',
            'impacto' => 'required|in:Alto,Medio,Bajo',
            'usuario' => 'nullable|exists:Usuarios,id_usuario',
        ]);

        Experiencia::create($validated);

        return redirect()->route('experiencias.index')->with('success', 'Experiencia de usuario creada exitosamente');
    }

    public function show(string $id)
    {
        $experiencia = Experiencia::findOrFail($id);
        return view('experiencias.show', compact('experiencia'));
    }

    public function edit(string $id)
    {
        $experiencia = Experiencia::findOrFail($id);
        return view('experiencias.edit', compact('experiencia'));
    }

    public function update(Request $request, string $id)
    {
        $experiencia = Experiencia::findOrFail($id);
        
        $validated = $request->validate([
            'tipo_experiencia' => 'required|in:Positiva,Negativa,Neutra',
            'descripcion' => 'required|string',
            'impacto' => 'required|in:Alto,Medio,Bajo',
            'usuario' => 'nullable|exists:Usuarios,id_usuario',
        ]);

        $experiencia->update($validated);

        return redirect()->route('experiencias.index')->with('success', 'Experiencia de usuario actualizada exitosamente');
    }

    public function destroy(string $id)
    {
        $experiencia = Experiencia::findOrFail($id);
        $experiencia->delete();

        return redirect()->route('experiencias.index')->with('success', 'Experiencia de usuario eliminada exitosamente');
    }
}
