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
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'correo' => 'required|email',
            'contraseña' => 'required',
        ]);

        $usuario = Usuario::where('correo', $request->correo)->first();  

        if (!$usuario) {
            return back()->withErrors(['correo' => 'Usuario no encontrado.']);
        }

        if (!Hash::check($request->contraseña, $usuario->contraseña)) {
            return back()->withErrors(['contraseña' => 'La contraseña proporcionada es incorrecta.']);
        }

        Auth::login($usuario);

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

