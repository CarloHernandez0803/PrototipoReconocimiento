<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
<<<<<<< HEAD
=======
use Illuminate\Validation\Rules\Password;
>>>>>>> 202c96f (Quinta version proyecto)

class UsuarioController extends Controller
{
    public function index()
    {
<<<<<<< HEAD
        $usuarios = Usuario::all();
=======
        $usuarios = Usuario::paginate(10);
>>>>>>> 202c96f (Quinta version proyecto)
        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        return view('usuarios.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|max:45',
            'apellidos' => 'required|max:60',
            'correo' => 'required|email|unique:Usuarios,correo|max:45',
<<<<<<< HEAD
            'contraseña' => 'required|min:6',
=======
            'contraseña' => ['required', 'confirmed', Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
            ],
>>>>>>> 202c96f (Quinta version proyecto)
            'rol' => 'required|in:Administrador,Coordinador,Alumno',
        ]);

        $validated['contraseña'] = Hash::make($validated['contraseña']);

<<<<<<< HEAD
        Usuario::create($validated);
=======
        \DB::transaction(function () use ($validated) {
            Usuario::create($validated);
        });
>>>>>>> 202c96f (Quinta version proyecto)

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado exitosamente');
    }

    public function show(string $id)
    {
        $usuario = Usuario::findOrFail($id);
        return view('usuarios.show', compact('usuario'));
    }

    public function edit(string $id)
    {
        $usuario = Usuario::findOrFail($id);
        return view('usuarios.edit', compact('usuario'));
    }

    public function update(Request $request, string $id)
    {
        $usuario = Usuario::findOrFail($id);
        
        $validated = $request->validate([
            'nombre' => 'required|max:45',
            'apellidos' => 'required|max:60',
            'correo' => 'required|email|max:45|unique:Usuarios,correo,'.$usuario->id_usuario.',id_usuario',
            'rol' => 'required|in:Administrador,Coordinador,Alumno',
        ]);

        if ($request->filled('contraseña')) {
            $validated['contraseña'] = Hash::make($request->contraseña);
        }

<<<<<<< HEAD
        $usuario->update($validated);
=======
        \DB::transaction(function () use ($validated) {
            $usuario->update($validated);
        });
>>>>>>> 202c96f (Quinta version proyecto)

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado exitosamente');
    }

    public function destroy(string $id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->delete();

        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado exitosamente');
    }
}