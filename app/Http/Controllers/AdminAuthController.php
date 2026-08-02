<?php

namespace App\Http\Controllers;

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
            session()->put('admin_authenticated', true);
            session()->regenerate();

            $redirect = $request->input('redirect', route('admin.dashboard'));

            return redirect($redirect);
        }

        return back()->withErrors([
            'username' => 'Usuario o contraseña inválidos.',
        ])->withInput();
    }

    public function logout()
    {
        session()->forget('admin_authenticated');

        return redirect()->route('home');
    }

    public function dashboard()
    {
        return view('admin.dashboard');
    }
}
