<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminOnly
{
    public function handle(Request $request, Closure $next)
    {
        abort_unless($request->session()->get('admin_authenticated') && $request->session()->get('admin_role', 'admin') === 'admin', 403, 'Esta acción requiere permisos de administrador.');

        return $next($request);
    }
}
