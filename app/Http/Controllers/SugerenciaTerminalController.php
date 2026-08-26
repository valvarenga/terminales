<?php

namespace App\Http\Controllers;

use App\Models\SugerenciaTerminal;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SugerenciaTerminalController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre_terminal' => ['required', 'string', 'max:255'],
            'ubicacion' => ['nullable', 'string', 'max:255'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $photoPath = null;
        if ($request->hasFile('foto')) {
            $image = $request->file('foto');
            $filename = Str::uuid().'.'.$image->extension();
            $photoPath = $image->storeAs('sugerencias-terminales', $filename, 'local');
        }

        SugerenciaTerminal::create([
            'nombre_terminal' => $data['nombre_terminal'],
            'ubicacion' => $data['ubicacion'] ?? null,
            'foto' => $photoPath,
        ]);

        return redirect()->route('home')->with('success', 'Gracias. Tu foto fue enviada para revisión administrativa.');
    }
}
