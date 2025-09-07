<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\SolicitudPruebaRecibida;
use App\Events\SolicitudPruebaRespondida;

class SolicitudController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->rol === 'Administrador') {
            $solicitudes = Solicitud::paginate(10);
        } 
        else {
            if ($user->rol === 'Coordinador') {
                $solicitudes = Solicitud::where('coordinador', $user->id_usuario)->paginate(10);
            } else {
                $solicitudes = Solicitud::where('alumno', $user->id_usuario)->paginate(10);
            }
        }
        return view('solicitudes.index', compact('solicitudes'));
    }

    public function create()
    {
        $alumnos = Usuario::where('rol', 'Alumno')->get();
        return view('solicitudes.create', compact('alumnos'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'fecha_solicitud' => 'required|date',
            'alumno' => 'required|exists:Usuarios,id_usuario',
        ]);

        $validated['estado'] = 'Pendiente';
        $validated['coordinador'] = $user->id_usuario;

        $solicitud = Solicitud::create($validated);

        event(new SolicitudPruebaRecibida($solicitud));

        return redirect()->route('solicitudes.index')->with('success', 'Solicitud de prueba creada exitosamente');
    }

    public function show(string $id)
    {
        $solicitud = Solicitud::findOrFail($id);
        return view('solicitudes.show', compact('solicitud'));
    }
    
    public function edit(string $id)
    {
        $solicitud = Solicitud::findOrFail($id);
        $alumnos = Usuario::where('rol', 'Alumno')->get();
        return view('solicitudes.edit', compact('solicitud', 'alumnos'));
    }

    public function update(Request $request, string $id)
    {
        $solicitud = Solicitud::findOrFail($id);
        $user = Auth::user();
        
        $dataToUpdate = [];

        if ($user->rol === 'Coordinador' && $user->id_usuario === $solicitud->coordinador) {
            $validated = $request->validate([
                'fecha_solicitud' => 'required|date',
                'alumno' => 'required|exists:Usuarios,id_usuario',
            ]);
            $dataToUpdate = $validated;
        }

        if ($user->rol === 'Administrador') {
            $validated = $request->validate([
                'estado' => 'required|in:Pendiente,Aprobada',
            ]);
            $dataToUpdate = $validated;

            if ($validated['estado'] === 'Aprobada') {
                $dataToUpdate['fecha_respuesta'] = now();
                $dataToUpdate['administrador'] = $user->id_usuario;
            } else {
                $dataToUpdate['fecha_respuesta'] = null;
                $dataToUpdate['administrador'] = null;
            }
        }
        
        if (empty($dataToUpdate)) {
            return redirect()->route('solicitudes.index')->with('error', 'No tienes permisos para realizar esta acción.');
        }

        $solicitud->update($dataToUpdate);

        event(new SolicitudPruebaRespondida($solicitud));

        return redirect()->route('solicitudes.index')->with('success', 'Solicitud de prueba actualizada exitosamente');
    }

    public function destroy(string $id)
    {
        $solicitud = Solicitud::findOrFail($id);
        $solicitud->delete();
        return redirect()->route('solicitudes.index')->with('success', 'Solicitud de prueba eliminada exitosamente');
    }
}