<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Events\UsuarioCreado;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = Usuario::paginate(10);
        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        return view('usuarios.create');
    }

    public function store(Request $request)
    {
        //Se reciben y validan los datos recibidos desde el formulario
        $validated = $request->validate([
            'nombre' => 'required|max:45',
            'apellidos' => 'required|max:60',
            'correo' => 'required|email|unique:Usuarios,correo|max:45',
            'password' => ['required', 'confirmed', Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
            ],
            'rol' => 'required|in:Administrador,Coordinador,Alumno',
        ]);

        //Se encripta la contraseña recibida desde el formulario y se almacena en la base de datos
        $validated['contraseña'] = Hash::make($validated['password']);

        //Se realiza la transacción para crear el usuario en la base de datos
        $usuario = \DB::transaction(function () use ($validated) {
            return Usuario::create($validated);
        });

        // Se enviara un correo con la informacion del usuario creado
        event(new UsuarioCreado($usuario));

        //Se redirige al listado de usuarios
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

        \DB::transaction(function () use ($validated) {
            $usuario->update($validated);
        });

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado exitosamente');
    }

    public function destroy(string $id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->delete();

        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado exitosamente');
    }
}