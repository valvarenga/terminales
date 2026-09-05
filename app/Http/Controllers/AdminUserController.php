<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class AdminUserController extends Controller
{
    public function index()
    {
        return view('admin.users', ['users' => User::whereIn('role', ['admin', 'editor'])->orderBy('name')->paginate(20)]);
    }

    public function store(Request $request)
    {
        $data = $this->validateUser($request);
        $data['password'] = Hash::make($data['password']);
        User::create($data);

        return redirect()->route('admin.users.index')->with('success', 'Usuario creado correctamente.');
    }

    public function edit(User $user)
    {
        abort_unless(in_array($user->role, ['admin', 'editor'], true), 404);

        return view('admin.user-edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        abort_unless(in_array($user->role, ['admin', 'editor'], true), 404);
        $data = $this->validateUser($request, $user);
        if ((int) $request->session()->get('admin_user_id') === $user->id && (! $data['is_active'] || $data['role'] !== 'admin')) {
            return back()->withInput()->withErrors(['role' => 'No puedes desactivar tu cuenta ni quitarte el permiso de administrador.']);
        }

        DB::transaction(function () use ($user, $data, $request) {
            $user = User::whereKey($user->id)->lockForUpdate()->firstOrFail();
            $passwordChanged = ! empty($data['password']);
            if ($passwordChanged) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }
            $user->fill($data);
            if ($passwordChanged || $user->isDirty(['role', 'is_active', 'email'])) {
                $user->session_version++;
            }
            $user->save();
            if ((int) $request->session()->get('admin_user_id') === $user->id) {
                $request->session()->put('admin_session_version', $user->session_version);
            }
        });

        return redirect()->route('admin.users.index')->with('success', 'Usuario actualizado. Los cambios de acceso invalidan sus sesiones anteriores.');
    }

    private function validateUser(Request $request, ?User $user = null): array
    {
        $request->merge(['email' => strtolower((string) $request->input('email')), 'is_active' => $request->boolean('is_active')]);

        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user?->id)],
            'role' => ['required', Rule::in(['admin', 'editor'])],
            'is_active' => ['required', 'boolean'],
            'password' => [$user ? 'nullable' : 'required', 'string', 'max:128', 'confirmed', Password::min(12)->letters()->numbers()],
        ]);
    }
}
