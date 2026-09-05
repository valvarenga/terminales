<?php

namespace App\Http\Controllers;

use App\Models\Departamentos;
use App\Models\Municipios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class Municipio extends Controller
{
    public function index()
    {
        return view('municipios.municipio', ['departamentos' => Departamentos::orderBy('nombre')->get()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255', 'unique:municipios,nombre'],
            'departamento_id' => ['required', 'exists:departamentos,id'],
            'file_M' => ['nullable', 'image', 'max:2048'],
            'latitud' => ['nullable', 'numeric', 'between:-90,90'],
            'longitud' => ['nullable', 'numeric', 'between:-180,180'],
        ]);

        $municipio = new Municipios();
        $municipio->nombre = $data['nombre'];
        $municipio->slug = Str::slug($data['nombre']);
        $municipio->departamento_id = $data['departamento_id'];
        $municipio->latitud = $data['latitud'] ?? null;
        $municipio->longitud = $data['longitud'] ?? null;
        $municipio->url_M = null;

        if ($request->hasFile('file_M')) {
            $municipio->url_M = Storage::url($request->file('file_M')->store('public/imagenes/municipio'));
        }

        $municipio->save();

        return redirect()->route('municipio.show')->with('success', 'Municipio creado correctamente.');
    }

    public function show()
    {
        return view('municipios.show', ['municipios' => Municipios::with('departamentos')->orderBy('nombre')->get()]);
    }

    public function ver(Municipios $municipio)
    {
        return view('municipios.ver', ['municipio' => $municipio, 'departamento' => $municipio->departamentos]);
    }

    public function edit(Municipios $municipio)
    {
        return view('municipios.edit', [
            'municipio' => $municipio,
            'departamento' => $municipio->departamentos,
            'todos_departamentos' => Departamentos::orderBy('nombre')->get(),
        ]);
    }

    public function update(Request $request, Municipios $municipio)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255', Rule::unique('municipios', 'nombre')->ignore($municipio)],
            'file_M' => ['nullable', 'image', 'max:2048'],
            'remove_photo' => ['nullable', 'boolean'],
            'departamento_id' => ['required', 'exists:departamentos,id'],
            'latitud' => ['nullable', 'numeric', 'between:-90,90'],
            'longitud' => ['nullable', 'numeric', 'between:-180,180'],
        ]);

        DB::transaction(function () use ($request, $municipio, $data) {
            if ((int) $municipio->departamento_id !== (int) $data['departamento_id'] &&
                ($municipio->terminales()->exists() || $municipio->autobusesOrigen()->exists() || $municipio->autobusesDestino()->exists())) {
                throw ValidationException::withMessages(['departamento_id' => 'No se puede trasladar un municipio con terminales o servicios asociados. Reasigne primero sus relaciones.']);
            }
            $nombreCambio = $municipio->nombre !== $data['nombre'];
            $municipio->nombre = $data['nombre'];
            $municipio->departamento_id = $data['departamento_id'];
            $municipio->latitud = $data['latitud'] ?? null;
            $municipio->longitud = $data['longitud'] ?? null;
            if ($request->boolean('remove_photo')) {
                $municipio->url_M = null;
            }
            if ($request->hasFile('file_M')) {
                $municipio->url_M = Storage::url($request->file('file_M')->store('public/imagenes/municipio'));
            }
            $municipio->save();
            if ($nombreCambio) {
                // Save each service so the audit observer records its changed label.
                $municipio->autobusesOrigen()->each(function ($autobus) use ($municipio) {
                    $autobus->origen = $municipio->nombre;
                    $autobus->save();
                });
                $municipio->autobusesDestino()->each(function ($autobus) use ($municipio) {
                    $autobus->destino = $municipio->nombre;
                    $autobus->save();
                });
            }
        });

        return redirect()->route('municipio.ver', $municipio)->with('success', 'Municipio actualizado correctamente.');
    }

    public function destroy(Municipios $municipio)
    {
        if ($municipio->terminales()->exists()) {
            return back()->withErrors(['municipio' => 'No se puede eliminar un municipio que todavía tiene terminales.']);
        }

        $municipio->delete();

        return redirect()->route('municipio.show')->with('success', 'Municipio eliminado correctamente.');
    }

    public function search(Request $request)
    {
        $term = $request->input('term', '');

        return Municipios::where('nombre', 'like', '%' . $term . '%')
            ->orderBy('nombre')
            ->limit(10)
            ->get()
            ->map(fn (Municipios $municipio) => ['id' => $municipio->id, 'value' => $municipio->nombre]);
    }
}
