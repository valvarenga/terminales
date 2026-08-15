<?php

namespace App\Http\Controllers;

use App\Models\SugerenciaTerminal;
use Illuminate\Http\Request;

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

        $expectedUsername = env('ADMIN_USERNAME', 'admin');
        $expectedPassword = env('ADMIN_PASSWORD', 'admin123');

        if ($data['username'] === $expectedUsername && $data['password'] === $expectedPassword) {
            $request->session()->put('admin_authenticated', true);
            $request->session()->regenerate();

            return redirect()->route('admin.dashboard');
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
        return view('admin.dashboard', [
            'sugerenciasTerminales' => SugerenciaTerminal::latest()->get(),
        ]);
    }
}
