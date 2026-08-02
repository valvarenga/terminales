<?php

namespace App\Http\Controllers;

use App\Models\Departamentos;
use App\Models\Municipios;
use App\Models\Terminales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class Terminal extends Controller
{
    public function index()
    {
        return view('ruta.index');
    }

    public function newterminal()
    {
        return view('terminales.terminal', [
            'departamentos' => Departamentos::orderBy('nombre')->get(),
            'municipios' => collect(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request, true);
        $terminal = new Terminales();
        $terminal->fill($data);
        $terminal->slug = Str::slug($data['nombre']);
        $terminal->url_T = Storage::url($request->file('file_T')->store('public/imagenes/terminal'));
        $terminal->save();

        return redirect()->route('show_terminal')->with('success', 'Terminal creada correctamente.');
    }

    public function show()
    {
        return view('terminales.showterminales', ['terminales' => Terminales::orderBy('nombre')->get()]);
    }

    public function verterminal(Terminales $terminales)
    {
        $terminales->load(['departamentos', 'municipios']);
        return view('terminales.verterminal', [
            'terminales' => $terminales,
            'departamento' => $terminales->departamentos,
            'municipio' => $terminales->municipios,
        ]);
    }

    public function edit(Terminales $terminal)
    {
        return view('terminales.edit', [
            'terminal' => $terminal,
            'departamento' => $terminal->departamentos,
            'municipio' => $terminal->municipios,
            'todos_departamentos' => Departamentos::whereKeyNot($terminal->departamento_id)->orderBy('nombre')->get(),
            'todos_municipios' => Municipios::where('departamento_id', $terminal->departamento_id)->whereKeyNot($terminal->municipio_id)->orderBy('nombre')->get(),
        ]);
    }

    public function update(Request $request, Terminales $terminal)
    {
        $data = $this->validatedData($request);
        $terminal->fill($data);
        $terminal->slug = Str::slug($data['nombre']);
        $terminal->save();

        return redirect()->route('ver.terminal', $terminal)->with('success', 'Terminal actualizada correctamente.');
    }

    public function destroy(Terminales $terminales)
    {
        $terminales->delete();
        return redirect()->route('show_terminal')->with('success', 'Terminal eliminada correctamente.');
    }

    private function validatedData(Request $request, bool $requireImage = false): array
    {
        $rules = [
            'nombre' => ['required', 'string', 'max:255'],
            'hora_apertura' => ['required', 'date_format:H:i'],
            'hora_cierre' => ['required', 'date_format:H:i'],
            'departamento' => ['required', 'exists:departamentos,id'],
            'municipio' => [
                'required',
                Rule::exists('municipios', 'id')->where(fn ($query) => $query->where('departamento_id', $request->input('departamento'))),
            ],
            'file_T' => [$requireImage ? 'required' : 'nullable', 'image', 'max:2048'],
        ];
        $data = $request->validate($rules);

        return [
            'nombre' => $data['nombre'],
            'hora_apertura' => $data['hora_apertura'],
            'hora_cierre' => $data['hora_cierre'],
            'departamento_id' => $data['departamento'],
            'municipio_id' => $data['municipio'],
        ];
    }
}
