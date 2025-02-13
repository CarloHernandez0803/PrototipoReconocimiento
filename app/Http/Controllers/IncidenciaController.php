<?php

namespace App\Http\Controllers;

use App\Models\Incidencia;
use Illuminate\Http\Request;
use App\Models\Resolucion;
use App\Models\Usuario;

class IncidenciaController extends Controller
{
    public function index()
    {
        $incidencias = Incidencia::with('resolucion')->paginate(10);
        return view('incidencias.index', compact('incidencias'));
    }

    public function create()
    {
        return view('incidencias.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipo_experiencia' => 'required|in:Error de Sistema,Problema de Rendimiento,Fallo de Seguridad,Actualizaciones Fallidas,Incidencias en Datos,Problema de Usabilidad,Solicitudes de Mejora,Otros',
            'descripcion' => 'required|string',
            'coordinador' => 'nullable|exists:Usuarios,id_usuario',
        ]);

        Incidencia::create($validated);

        return redirect()->route('incidencias.index')->with('success', 'Incidencia creada exitosamente');
    }

    public function show(string $id)
    {
        $incidencia = Incidencia::with('resolucion')->findOrFail($id);
        return view('incidencias.show', compact('incidencia'));
    }

    public function edit(string $id)
    {
        $incidencia = Incidencia::findOrFail($id);
        return view('incidencias.edit', compact('incidencia'));
    }

    public function update(Request $request, string $id)
    {
        $incidencia = Incidencia::findOrFail($id);
        
        $validated = $request->validate([
            'tipo_experiencia' => 'nullable|in:Error de Sistema,Problema de Rendimiento,Fallo de Seguridad,Actualizaciones Fallidas,Incidencias en Datos,Problema de Usabilidad,Solicitudes de Mejora,Otros',
            'descripcion' => 'nullable|string',
            'coordinador' => 'nullable|exists:Usuarios,id_usuario',
        ]);

        $incidencia->update($validated);

        return redirect()->route('incidencias.index')->with('success', 'Incidencia actualizada exitosamente');
    }

    public function destroy(string $id)
    {
        $incidencia = Incidencia::findOrFail($id);
        $incidencia->delete();

        return redirect()->route('incidencias.index')->with('success', 'Incidencia eliminada exitosamente');
    }

    public function timeline()
    {
        $incidencias = Incidencia::with(['resolucion'])->paginate(10);
        return view('incidencias.timeline', compact('incidencias'));
    }
}
