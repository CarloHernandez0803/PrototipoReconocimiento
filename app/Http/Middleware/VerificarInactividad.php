<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
<<<<<<< HEAD
use App\Models\Sesion;
=======
use Illuminate\Support\Facades\Auth;
>>>>>>> 202c96f (Quinta version proyecto)

class VerificarInactividad
{
    public function handle(Request $request, Closure $next)
    {
<<<<<<< HEAD
        if ($request->session()->has('last_activity')) {
            if (time() - $request->session()->get('last_activity') > 1200) {
                $request->session()->flush();
                return redirect('/login')->with('error', 'Sesión expirada por inactividad.');
            }
        }
        $request->session()->put('last_activity', time());
=======
        $timeout = config('session.lifetime') * 60;

        if ($request->session()->has('last_activity')) {
            $lastActivity = $request->session()->get('last_activity');
            $inactiveTime = time() - $lastActivity;

            if ($inactiveTime > $timeout) {
                Auth::logout(); 
                $request->session()->flush(); 
                return redirect('/login')->withErrors(['error' => 'Tu sesión ha expirado por inactividad.']);
            }
        }

        $request->session()->put('last_activity', time());

>>>>>>> 202c96f (Quinta version proyecto)
        return $next($request);
    }
}
