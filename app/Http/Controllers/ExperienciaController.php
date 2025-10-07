<?php

namespace App\Http\Controllers;

use App\Models\Experiencia;
use Illuminate\Http\Request;
use App\Events\ExperienciaUsuarioRegistrada;
use Illuminate\Support\Facades\Auth;

class ExperienciaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->rol === 'Administrador') {
            $experiencias = Experiencia::paginate(10);
        } else {
            $experiencias = Experiencia::where('usuario', $user->id_usuario)->paginate(10);
        }
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
        ]);

        $experiencia = Experiencia::create([
            ...$validated,
            'usuario' => Auth::id(),
        ]);

        $experienciaFresh = Experiencia::find($experiencia->id_experiencia);

        event(new ExperienciaUsuarioRegistrada($experienciaFresh));

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
