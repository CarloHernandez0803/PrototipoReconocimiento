<?php

namespace App\Http\Controllers;
use App\Models\Incidencia;
use App\Models\Resolucion;
use App\Events\SeguimientoFalloActualizado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResolucionController extends Controller
{
    public function create(Incidencia $incidencia)
    {
        return view('resoluciones.create', compact('incidencia'));
    }

    public function store(Request $request, Incidencia $incidencia)
    {
        $validated = $request->validate([
            'estado' => 'required|in:Pendiente,En Proceso,Resuelto',
            'comentario' => 'required|string|max:1000',
        ]);

        $resolucion = Resolucion::create([
            'incidencia' => $incidencia->id_incidencia,
            'id_administrador' => Auth::id(),
            'estado' => $validated['estado'],
            'comentario' => $validated['comentario'],
            'fecha_resolucion' => now(),
        ]);

        event(new SeguimientoFalloActualizado($resolucion));

        return redirect()->route('incidencias.show', $incidencia->id_incidencia)->with('success', 'La resolución de la incidencia ha sido actualizada.');
    }
}
