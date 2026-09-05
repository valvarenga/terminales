<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminAccess
{
    public function handle(Request $request, Closure $next)
    {
        if (session('admin_authenticated')) {
            if (session()->has('admin_user_id')) {
                $user = \App\Models\User::find(session('admin_user_id'));
                if (! $user || ! $user->is_active || ! in_array($user->role, ['admin', 'editor'], true)
                    || (int) session('admin_session_version') !== (int) $user->session_version) {
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    return redirect()->route('admin.login')->with('error', 'Tu acceso cambió. Inicia sesión nuevamente.');
                }
                session(['admin_role' => $user->role, 'admin_actor' => $user->email]);
            }
            if ($request->isMethod('DELETE') && session('admin_role', 'admin') !== 'admin') {
                abort(403, 'Solo un administrador puede eliminar registros.');
            }
            return $next($request);
        }

        $redirect = $request->getRequestUri();
        $loginUrl = route('admin.login', ['redirect' => $redirect]);

        return redirect($loginUrl)->with('error', 'Debe iniciar sesión para acceder a esta sección.');
    }
}
