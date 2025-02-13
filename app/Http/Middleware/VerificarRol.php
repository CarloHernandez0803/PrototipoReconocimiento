<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerificarRol
{
    public function handle(Request $request, Closure $next, $rol)
    {
        \Log::info('Middleware verificar.rol called', [ 'user' => Auth::check() ? Auth::user()->id : 'No user', 'user_rol' => Auth::check() ? Auth::user()->rol : 'No rol', 'required_rol' => $rol ]);
    
        $usuario = Auth::user();

        if (!$usuario) {
            return redirect('/login')->withErrors(['error' => 'Debes iniciar sesión.']);
        }

        if ($usuario->rol !== $rol) {
            \Log::error('Rol no coincide', ['esperado' => $rol, 'actual' => $usuario->rol]);
            abort(403, 'Acceso no autorizado.');
        }

        return $next($request);
    }
}
