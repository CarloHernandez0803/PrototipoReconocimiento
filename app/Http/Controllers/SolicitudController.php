<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;
use Illuminate\Http\Request;

class SolicitudController extends Controller
{
    public function index()
    {
        $solicitudes = Solicitud::all();
        return view('solicitudes.index', compact('solicitudes'));
    }

    public function create()
    {
        return view('solicitudes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'estado' => 'required|in:Pendiente,Aprobada',
            'administrador' => 'nullable|exists:Usuarios,id_usuario',
            'coordinador' => 'required|exists:Usuarios,id_usuario',
        ]);

        Solicitud::create($validated);

        return redirect()->route('solicitudes.index')->with('success', 'Solicitud de prueba creado exitosamente');
    }

    public function show(string $id)
    {
        $solicitud = Solicitud::findOrFail($id);
        return view('solicitudes.show', compact('solicitud'));
    }

    public function edit(string $id)
    {
        $solicitud = Solicitud::findOrFail($id);
        return view('solicitudes.edit', compact('solicitud'));
    }

    public function update(Request $request, string $id)
    {
        $solicitud = Solicitud::findOrFail($id);
        
        $validated = $request->validate([
            'estado' => 'nullable|in:Pendiente,Aprobada',
            'administrador' => 'nullable|exists:Usuarios,id_usuario',
            'coordinador' => 'nullable|exists:Usuarios,id_usuario',
        ]);

        $solicitud->update($validated);

        return redirect()->route('solicitudes.index')->with('success', 'Solicitud de prueba actualizado exitosamente');
    }

    public function destroy(string $id)
    {
        $solicitud = Solicitud::findOrFail($id);
        $solicitud->delete();

        return redirect()->route('solicitudes.index')->with('success', 'Solicitud de prueba eliminado exitosamente');
    }
}
