<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Sesion;
// use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
            'contraseña' => 'required',
        ]);

        $usuario = Usuario::where('correo', $request->correo)->first();

        if (!$usuario || !Hash::check($request->contraseña, $usuario->contraseña)) {
            return back()->withErrors(['correo' => 'Las credenciales proporcionadas son incorrectas.']);
        }

        $sesion = new Sesion([
            'token_sesion' => Str::random(60),
            'usuario' => $usuario->id_usuario,
            'fecha_inicio' => now(),
        ]);
        $sesion->save();

        session([
            'user_id' => $usuario->id_usuario,
            'user_role' => $usuario->rol,
            'session_id' => $sesion->id_sesion,
            'last_activity' => now(),
        ]);

        /* Notificacion::create([
            'tipo_notificacion' => 'Creación de Cuenta',
            'contenido' => 'Inicio de sesión exitoso',
            'fecha_envio' => now(),
            'usuario' => $usuario->id_usuario
        ]); */

        return $this->redirectBasedOnRole($usuario->rol);
    }

    protected function redirectBasedOnRole($rol)
    {
        switch ($rol) {
            case 'Administrador':
                return redirect()->route('Administrador.dashboard');
            case 'Coordinador':
                return redirect()->route('Coordinador.dashboard');
            case 'Alumno':
                return redirect()->route('Alumno.dashboard');
            default:
                return redirect('/');
        }
    }

    public function logout(Request $request)
    {
        if ($request->session()->has('session_id')) {
            $sesion = Sesion::where('id_sesion', $request->session()->get('session_id'))->first();
            if ($sesion) {
                $sesion->fecha_fin = now();
                $sesion->save();
            }
        }
        
        $request->session()->flush();
        return redirect('/');
    }
}