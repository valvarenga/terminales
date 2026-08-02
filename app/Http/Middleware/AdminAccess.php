<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminAccess
{
    public function handle(Request $request, Closure $next)
    {
        if (session('admin_authenticated')) {
            return $next($request);
        }

        $redirect = $request->getRequestUri();
        $loginUrl = route('admin.login', ['redirect' => $redirect]);

        return redirect($loginUrl)->with('error', 'Debe iniciar sesión para acceder a esta sección.');
    }
}
