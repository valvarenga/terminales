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
            'foto' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $image = $request->file('foto');
        $filename = Str::uuid().'.'.$image->extension();
        $image->move(public_path('imagenes/sugerencias-terminales'), $filename);

        SugerenciaTerminal::create([
            'nombre_terminal' => $data['nombre_terminal'],
            'ubicacion' => $data['ubicacion'] ?? null,
            'foto' => 'imagenes/sugerencias-terminales/'.$filename,
        ]);

        return redirect()->route('home')->with('success', 'Gracias. Tu foto fue enviada para revisión administrativa.');
    }
}
