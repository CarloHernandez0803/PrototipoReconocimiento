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
            $solicitudes = Solicitud::where('coordinador', $user->id_usuario)->paginate(10);
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
            'alumno' => 'required|exists:Usuarios,id_usuario', // Verifica que el alumno exista
        ]);

        $validated['estado'] = 'Pendiente';
        $validated['coordinador'] = $user->id_usuario;

        $solicitud = Solicitud::create($validated);

        event(new SolicitudPruebaRecibida($solicitud));

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
        $alumnos = Usuario::where('rol', 'Alumno')->get();
        return view('solicitudes.edit', compact('solicitud', 'alumnos'));
    }

    public function update(Request $request, string $id)
    {
        $solicitud = Solicitud::findOrFail($id);
        $user = Auth::user();
        
        if ($user->rol === 'Administrador') {
            $validated = $request->validate([
                'estado' => 'required|in:Pendiente,Aprobada',
                'fecha_respuesta' => 'nullable|date',
                'fecha_solicitud' => 'required|date',
                'alumno' => 'required|exists:Usuarios,id_usuario',
            ]);

            if ($validated['estado'] === 'Aprobada') {
                $validated['fecha_respuesta'] = now();
                $validated['administrador'] = $user->id_usuario;
            }

            event(new SolicitudPruebaRespondida($solicitud));

        } else {
            $validated = $request->validate([
                'fecha_solicitud' => 'required|date',
                'alumno' => 'required|exists:Usuarios,id_usuario',
            ]);
        }

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
