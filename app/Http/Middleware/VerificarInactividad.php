<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerificarInactividad
{
    public function handle(Request $request, Closure $next)
    {
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

        return $next($request);
    }
}
