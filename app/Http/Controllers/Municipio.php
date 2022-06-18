<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Departamentos;
use App\Models\Municipios;

class Municipio extends Controller
{
    //
    public function index()
    {
        $departamentos = Departamentos::all();
        return view('ruta.municipio' , compact('departamentos'));
    }
    public function store(Request $request)
    {
        $municipio = new Municipios();
        $municipio->nombre = $request->nombre;
        $municipio->departamento_id = $request->departamento_id;
        $municipio->save();
        return redirect()->route('ruta.index');
    }
}
