<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    // Rutas protegidas por autenticación 
    public function showLogin()
    {
        return view('auth.login'); // Ruta a la vista de inicio de sesión
    }

    // Ruta para iniciar sesión
    public function login(Request $request)
    {
        // Validar los datos de inicio de sesión
        $credentials = $request->validate([
            'correo' => 'required|email',
            'contraseña' => 'required',
        ]);

        // Iniciar sesión
        $usuario = Usuario::where('correo', $request->correo)->first();  

        // Verificar que el usuario exista
        if (!$usuario) {
            return back()->withErrors(['correo' => 'Usuario no encontrado.']);
        }

        // Verificar la contraseña
        if (!Hash::check($request->contraseña, $usuario->contraseña)) {
            return back()->withErrors(['contraseña' => 'La contraseña proporcionada es incorrecta.']);
        }

        // Iniciar sesión
        Auth::login($usuario);

        // Redirigir a la vista correspondiente basado en el rol
        return $this->redirectBasedOnRole($usuario->rol);
    }

    protected function redirectBasedOnRole($rol)
    {
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
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}

