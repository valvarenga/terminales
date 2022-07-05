<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Departamentos;
use App\Models\Municipios;
use Illuminate\Support\Str;

class Municipio extends Controller
{
    //
    public function index()
    {
        $departamentos = Departamentos::all();
        return view('municipios.municipio' , compact('departamentos'));
    }
    public function store(Request $request)
    {
        $slug = Str::slug($request->nombre);

        $request -> validate([
            'nombre' => 'required',
            'departamento_id' => 'required',
        ]);
        
        $municipio = new Municipios();
        $municipio->nombre = $request->nombre;
        $municipio->slug= $slug;
        $municipio->departamento_id = $request->departamento_id;
        $municipio->save();
        return redirect()->route('ruta.index');
    }

    public function show()
    {
        $municipios = Municipios::all();
        return view('municipios.show', compact('municipios'));
    }
    public function edit(Request $request)
    {
        
    }
}
