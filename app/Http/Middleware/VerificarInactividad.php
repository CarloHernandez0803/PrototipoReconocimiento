<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VerificarInactividad
{
    public function handle(Request $request, Closure $next): Response
    {
        // Tiempo de inactividad en minutos
        $timeout = 20;

        // Ignorar esta verificación en la página de login para evitar bucles de redirección
        if ($request->routeIs('login')) {
            return $next($request);
        }

        // Verificar si el usuario está autenticado y si la sesión tiene una marca de tiempo
        if (Auth::check() && session('last_activity')) {
            $tiempoDeInactividad = time() - session('last_activity');

            // Si el tiempo de inactividad supera el límite (convertido a segundos)
            if ($tiempoDeInactividad > ($timeout * 60)) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                             ->with('error', 'Tu sesión ha expirado por inactividad.');
            }
        }

        // Actualizar la marca de tiempo de la última actividad en la sesión
        session(['last_activity' => time()]);

        return $next($request);
    }
}