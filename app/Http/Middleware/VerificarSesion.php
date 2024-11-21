<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Sesion;

class VerificarSesion
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->session()->has('user_id')) 
        {
            return redirect()->route('login');
        }

        $sesion = Sesion::find($request->session()->get('session_id'));
        
        if (!$sesion || $sesion->fecha_fin) 
        {
            $request->session()->flush();
            return redirect()->route('login')->with('error', 'Su sesión ha expirado.');
        }

        return $next($request);
    }
}
