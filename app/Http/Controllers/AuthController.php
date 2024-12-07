<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
<<<<<<< HEAD
use App\Models\Sesion;
// use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
=======
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
>>>>>>> 202c96f (Quinta version proyecto)

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
<<<<<<< HEAD
        $request->validate([
=======
        $credentials = $request->validate([
>>>>>>> 202c96f (Quinta version proyecto)
            'correo' => 'required|email',
            'contraseña' => 'required',
        ]);

<<<<<<< HEAD
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
=======
        $usuario = Usuario::where('correo', $request->correo)->first();  

        if (!$usuario) {
            return back()->withErrors(['correo' => 'Usuario no encontrado.']);
        }

        if (!Hash::check($request->contraseña, $usuario->contraseña)) {
            return back()->withErrors(['contraseña' => 'La contraseña proporcionada es incorrecta.']);
        }

        Auth::login($usuario);
>>>>>>> 202c96f (Quinta version proyecto)

        return $this->redirectBasedOnRole($usuario->rol);
    }

    protected function redirectBasedOnRole($rol)
    {
<<<<<<< HEAD
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
=======
        \Log::info('Redirecting based on role', ['role' => $rol]);

        /*switch ($rol) {
            case 'Administrador':
                return redirect()->route('dashboard.administrador');
                break;
            case 'Coordinador':
                return redirect()->route('dashboard.coordinador');
                break;
            case 'Alumno':
                return redirect()->route('dashboard.alumno');
                break;
            default:
                return redirect('/');
        }*/

        return redirect()->route('dashboard');
>>>>>>> 202c96f (Quinta version proyecto)
    }

    public function logout(Request $request)
    {
<<<<<<< HEAD
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
=======
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}

>>>>>>> 202c96f (Quinta version proyecto)
