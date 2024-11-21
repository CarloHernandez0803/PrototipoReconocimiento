<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = Usuario::all();
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
            'contraseña' => 'required|min:6',
            'rol' => 'required|in:Administrador,Coordinador,Alumno',
        ]);

        $validated['contraseña'] = Hash::make($validated['contraseña']);

        Usuario::create($validated);

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

        $usuario->update($validated);

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado exitosamente');
    }

    public function destroy(string $id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->delete();

        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado exitosamente');
    }
}