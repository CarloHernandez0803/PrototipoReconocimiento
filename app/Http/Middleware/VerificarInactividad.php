<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Sesion;

class VerificarInactividad
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->session()->has('last_activity')) {
            if (time() - $request->session()->get('last_activity') > 1200) {
                $request->session()->flush();
                return redirect('/login')->with('error', 'Sesión expirada por inactividad.');
            }
        }
        $request->session()->put('last_activity', time());
        return $next($request);
    }
}
