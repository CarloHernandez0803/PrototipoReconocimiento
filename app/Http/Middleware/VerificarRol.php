<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerificarRol
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!$request->session()->has('user_role')) {
            return redirect('login');
        }

        $userRole = $request->session()->get('user_role');

        if (!in_array($userRole, $roles)) {
            abort(403, 'No tiene permiso para acceder a esta sección.');
        }

        return $next($request);
    }
}
