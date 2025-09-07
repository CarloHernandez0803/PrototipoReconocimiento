<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;
use App\Models\Usuario;

class CustomNewPasswordController extends Controller
{
    /**
     * Manejar la solicitud de nueva contraseña
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'correo' => 'required|email',
            'contraseña' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Usar el sistema de Laravel pero con nuestros campos
        $status = Password::broker()->reset(
            [
                'email' => $request->correo,
                'password' => $request->contraseña,
                'password_confirmation' => $request->contraseña_confirmation,
                'token' => $request->token,
            ],
            function ($user, $password) {
                $user->forceFill([
                    'contraseña' => Hash::make($password)
                ])->save();

                event(new PasswordReset($user));
            }
        );

        return $status == Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('status', 'Contraseña restablecida correctamente.')
                    : back()->withErrors(['correo' => 'El token de restablecimiento es inválido o ha expirado.']);
    }
}