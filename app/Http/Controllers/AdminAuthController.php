<?php

namespace App\Http\Controllers;

use App\Models\SugerenciaTerminal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;

class AdminAuthController extends Controller
{
    public function showLogin(Request $request)
    {
        return view('admin.login', [
            'redirect' => $request->query('redirect', route('admin.dashboard')),
        ]);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $expectedUsername = config('admin.username');
        $passwordHash = config('admin.password_hash');

        $user = Schema::hasTable('users')
            ? User::where('email', strtolower($data['username']))->first()
            : null;
        if ($user && $user->is_active && in_array($user->role, ['admin', 'editor'], true) && Hash::check($data['password'], $user->password)) {
            $request->session()->regenerate();
            $request->session()->put([
                'admin_authenticated' => true,
                'admin_user_id' => $user->id,
                'admin_session_version' => $user->session_version,
                'admin_role' => $user->role,
                'admin_actor' => $user->email,
            ]);

            return redirect($this->safeRedirect($request->input('redirect')));
        }

        if (! is_string($expectedUsername) || ! is_string($passwordHash) || $expectedUsername === '' || $passwordHash === '') {
            Log::critical('Administrative login attempted without configured credentials.');

            return back()->withErrors([
                'username' => 'El acceso administrativo no esta configurado. Contacte al administrador del sitio.',
            ]);
        }

        if (hash_equals($expectedUsername, $data['username']) && Hash::check($data['password'], $passwordHash)) {
            $request->session()->forget(['admin_user_id', 'admin_session_version']);
            $request->session()->put('admin_authenticated', true);
            $request->session()->put('admin_role', 'admin');
            $request->session()->put('admin_actor', $expectedUsername);
            $request->session()->regenerate();

            return redirect($this->safeRedirect($request->input('redirect')));
        }

        return back()->withErrors([
            'username' => 'Usuario o contraseña inválidos.',
        ])->withInput();
    }

    public function logout(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    public function dashboard()
    {
        return app(AdminDashboardController::class)->index(request());
    }

    public function suggestionPhoto(SugerenciaTerminal $sugerencia)
    {
        abort_unless($sugerencia->foto, 404);

        $path = 'sugerencias-terminales/'.basename($sugerencia->foto);
        if (Storage::disk('local')->exists($path)) {
            return Storage::disk('local')->response($path, null, ['Cache-Control' => 'private, no-store']);
        }

        // Legacy uploads remain protected by the directory access rule above.
        $legacyPath = public_path($sugerencia->foto);
        abort_unless(is_file($legacyPath), 404);

        return response()->file($legacyPath, ['Cache-Control' => 'private, no-store']);
    }

    private function safeRedirect(?string $redirect): string
    {
        if (is_string($redirect) && str_starts_with($redirect, '/') && ! str_starts_with($redirect, '//')) {
            return $redirect;
        }

        return route('admin.dashboard');
    }
}
