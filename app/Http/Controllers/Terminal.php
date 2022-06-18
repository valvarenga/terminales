<?php

namespace App\Http\Controllers;

use App\Models\Departamentos;
use App\Models\Municipios;
use App\Models\Terminales;
use App\Models\Autobuses;
use Illuminate\Http\Request;

class Terminal extends Controller
{
    //
    public function index()
    {

        return view('ruta.index');
    }

    public function newterminal()
    {
      $departamentos = Departamentos::all();
      $municipios = Municipios::all();
        return view('ruta.terminal', compact('departamentos','municipios'));
    }
    


    public function store(Request $request)
    {
        $terminal = new Terminales();
        $terminal->nombre = $request->nombre;
        $terminal->hora_apertura = $request->hora_apertura;
        $terminal->hora_cierre = $request->hora_cierre;
        $terminal->departamento_id = $request->departamento;
        $terminal->municipio_id = $request->municipio;
        $terminal->save();

        return redirect()->route('ruta.index');
    }
}
