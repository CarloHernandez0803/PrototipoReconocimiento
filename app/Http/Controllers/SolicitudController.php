<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;
<<<<<<< HEAD
use Illuminate\Http\Request;
=======
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
>>>>>>> 202c96f (Quinta version proyecto)

class SolicitudController extends Controller
{
    public function index()
    {
<<<<<<< HEAD
        $solicitudes = Solicitud::all();
=======
        $user = Auth::user();

        if ($user->rol === 'Administrador') {
            $solicitudes = Solicitud::paginate(10);
        }
        else {
            $solicitudes = Solicitud::where('coordinador', $user->id_usuario)->paginate(10);
        }
        
>>>>>>> 202c96f (Quinta version proyecto)
        return view('solicitudes.index', compact('solicitudes'));
    }

    public function create()
    {
<<<<<<< HEAD
        return view('solicitudes.create');
=======
        $alumnos = Usuario::where('rol', 'Alumno')->get();
        return view('solicitudes.create', compact('alumnos'));
>>>>>>> 202c96f (Quinta version proyecto)
    }

    public function store(Request $request)
    {
<<<<<<< HEAD
        $validated = $request->validate([
            'estado' => 'required|in:Pendiente,Aprobada',
            'administrador' => 'nullable|exists:Usuarios,id_usuario',
            'coordinador' => 'required|exists:Usuarios,id_usuario',
        ]);

=======
        $user = Auth::user();
        
        $validated = $request->validate([
            'fecha_solicitud' => 'required|date',
            'alumno' => 'required|exists:Usuarios,id_usuario', // Verifica que el alumno exista
        ]);

        $validated['estado'] = 'Pendiente';
        $validated['coordinador'] = $user->id_usuario;

>>>>>>> 202c96f (Quinta version proyecto)
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
<<<<<<< HEAD
        return view('solicitudes.edit', compact('solicitud'));
=======
        $alumnos = Usuario::where('rol', 'Alumno')->get();
        return view('solicitudes.edit', compact('solicitud', 'alumnos'));
>>>>>>> 202c96f (Quinta version proyecto)
    }

    public function update(Request $request, string $id)
    {
        $solicitud = Solicitud::findOrFail($id);
<<<<<<< HEAD
        
        $validated = $request->validate([
            'estado' => 'nullable|in:Pendiente,Aprobada',
            'administrador' => 'nullable|exists:Usuarios,id_usuario',
            'coordinador' => 'nullable|exists:Usuarios,id_usuario',
        ]);
=======
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

        } else {
            $validated = $request->validate([
                'fecha_solicitud' => 'required|date',
                'alumno' => 'required|exists:Usuarios,id_usuario',
            ]);
        }
>>>>>>> 202c96f (Quinta version proyecto)

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
