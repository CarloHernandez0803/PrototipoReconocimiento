<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Sesion;

class VerificarSesion
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->session()->has('session_id')) {
            return redirect('/login')->with('error', 'Debes iniciar sesión.');
        }

        $sesion = Sesion::find($request->session()->get('session_id'));
        if (!$sesion || $sesion->fecha_fin) {
            $request->session()->forget('session_id');
            return redirect('/login')->with('error', 'Tu sesión ha expirado.');
        }

        return $next($request);
    }
}
