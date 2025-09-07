<?php

namespace App\Http\Controllers;

use App\Models\Incidencia;
use Illuminate\Http\Request;
use App\Models\Resolucion;
use App\Models\Usuario;
use App\Events\ReporteFalloRegistrado;
use Illuminate\Support\Facades\Auth;

class IncidenciaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->rol === 'Administrador') {
            $incidencias = Incidencia::with('resoluciones')->paginate(10);;
        } else {
            $incidencias = Incidencia::where('coordinador', $user->id_usuario)->with('resoluciones')->paginate(10);
        }
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
        ]);

        $incidencia = Incidencia::create([
            'tipo_incidencia' => $validated['tipo_incidencia'],
            'descripcion' => $validated['descripcion'],
            'coordinador' => Auth::id(),
        ]);

        event(new ReporteFalloRegistrado($incidencia));

        return redirect()->route('incidencias.index')->with('success', 'Incidencia creada exitosamente');
    }

    public function show(string $id)
    {
        $incidencia = Incidencia::with('resoluciones')->findOrFail($id);
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
            'tipo_experiencia' => 'required|in:Error de Sistema,Problema de Rendimiento,Fallo de Seguridad,Actualizaciones Fallidas,Incidencias en Datos,Problema de Usabilidad,Solicitudes de Mejora,Otros',
            'descripcion' => 'required|string',
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
        $incidencias = Incidencia::with(['resoluciones'])->paginate(10);
        return view('incidencias.timeline', compact('incidencias'));
    }
}
