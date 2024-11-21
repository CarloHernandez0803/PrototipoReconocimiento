<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Sesion;

class VerificarInactividad
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->session()->has('last_activity')) 
        {
            $lastActivity = $request->session()->get('last_activity');
            $inactivityLimit = 20 * 60;

            if (time() - $lastActivity > $inactivityLimit) 
            {
                $sesion = Sesion::find($request->session()->get('session_id'));
                if ($sesion) 
                {
                    $sesion->fecha_fin = now();
                    $sesion->save();
                }

                $request->session()->flush();
                return redirect()->route('login')->with('error', 'Su sesión ha expirado por inactividad.');
            }
        }

        $request->session()->put('last_activity', time());
        return $next($request);
    }
}
