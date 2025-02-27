<?php

namespace App\Http\Controllers;
use App\Models\Incidencia;
use App\Models\Resolucion;
use App\Events\SeguimientoFalloActualizado;
use Illuminate\Http\Request;

class ResolucionController extends Controller
{
    public function create($id)
    {
        $incidencia = Incidencia::findOrFail($id);
        return view('resoluciones.create', compact('incidencia'));
    }

    public function store(Request $request, $id)
    {
        $incidencia = Incidencia::findOrFail($id);

        $validated = $request->validate([
            'estado' => 'required|in:Pendiente,En Proceso,Resuelto',
        ]);

        Resolucion::create([
            'estado' => $validated['estado'],
            'fecha_resolucion' => now(),
            'incidencia' => $incidencia->id_incidencia,
        ]);

        return redirect()->route('incidencias.show', $incidencia->id_incidencia)->with('success', 'Resolución registrada exitosamente');
    }
    
    public function edit($id)
    {
        $resolucion = Resolucion::findOrFail($id);
        return view('resoluciones.edit', compact('resolucion'));
    }

    public function update(Request $request, $id)
    {
        $resolucion = Resolucion::findOrFail($id);

        $validated = $request->validate([
            'estado' => 'required|in:Pendiente,En Proceso,Resuelto',
        ]);

        $resolucion->update([
            'estado' => $validated['estado'],
            'fecha_resolucion' => now(),
        ]);

        event(new SeguimientoFalloActualizado($resolucion));

        return redirect()->route('incidencias.show', $resolucion->incidencia)->with('success', 'Resolución actualizada exitosamente');
    }
}
