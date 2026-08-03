<?php

namespace App\Http\Controllers;

use App\Models\Departamentos;
use App\Models\Municipios;
use App\Models\Terminales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class Departamento extends Controller
{
    public function index()
    {
        return view('departamentos.departamento');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255', 'unique:departamentos,nombre'],
            'file_D' => ['nullable', 'image', 'max:2048'],
        ]);

        $departamento = new Departamentos();
        $departamento->nombre = $data['nombre'];
        $departamento->slug = Str::slug($data['nombre']);

        if ($request->hasFile('file_D')) {
            $departamento->url = Storage::url($request->file('file_D')->store('public/imagenes/departamento'));
        }

        $departamento->save();

        return redirect()->route('departamentos.show')->with('success', 'Departamento creado correctamente.');
    }

    public function update(Request $request, Departamentos $departamento)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255', Rule::unique('departamentos', 'nombre')->ignore($departamento)],
        ]);

        $departamento->nombre = $data['nombre'];
        $departamento->slug = Str::slug($data['nombre']);
        $departamento->save();

        return redirect()->route('departamento.ver', $departamento)->with('success', 'Departamento actualizado correctamente.');
    }

    public function destroy(Departamentos $departamento)
    {
        if ($departamento->municipios()->exists() || $departamento->terminales()->exists()) {
            return back()->withErrors(['departamento' => 'No se puede eliminar un departamento que todavía tiene municipios o terminales.']);
        }

        $departamento->delete();

        return redirect()->route('departamentos.show')->with('success', 'Departamento eliminado correctamente.');
    }

    public function show()
    {
        return view('departamentos.show_departamentos', [
            'departamentos' => Departamentos::orderBy('nombre')->get(),
        ]);
    }

    public function ver_departamento(Departamentos $departamento)
    {
        return view('departamentos.edit', compact('departamento'));
    }

    public function listar()
    {
        return view('departamentos.listar_departamentos', [
            'departamentos' => Departamentos::orderBy('nombre')->get(),
        ]);
    }

    public function departamentos_municipios(Departamentos $departamento)
    {
        return view('departamentos.municipios', [
            'departamento' => $departamento,
            'municipios' => $departamento->municipios()->orderBy('nombre')->get(),
        ]);
    }

    public function departamento_terminales(Departamentos $departamento, Municipios $municipio)
    {
        abort_unless($municipio->departamento_id === $departamento->id, 404);

        return view('departamentos.terminales_departamentos', [
            'terminales' => $municipio->terminales()->orderBy('nombre')->get(),
        ]);
    }

    public function buscar_autobuses(Terminales $terminal)
    {
        return view('departamentos.terminales_departamentos', [
            'terminal' => $terminal,
            'autobuses' => $terminal->autobuses()->orderBy('hora_salida')->get(),
        ]);
    }
}
