<?php

namespace App\Http\Controllers;

use App\Models\Departamentos;
use App\Models\Municipios;
use App\Models\Terminales;
use App\Models\Autobuses;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


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
        return view('terminales.terminal', compact('departamentos','municipios'));
    }
    


    public function store(Request $request)
    {
        //convierte el nombre a slug(url amigable)
        $slug = Str::slug($request->nombre);

        //Validación del formulario
        $request -> validate([
            'nombre' => 'required',
            'hora_apertura' => 'required',
            'hora_cierre' => 'required',
            'departamento' => 'required',
            'municipio' => 'required',
        ]);

        //guardar los datos del formulario en la base de datos
        $terminal = new Terminales();
        $terminal->nombre = $request->nombre;
        $terminal->slug= $slug;
        $terminal->hora_apertura = $request->hora_apertura;
        $terminal->hora_cierre = $request->hora_cierre;
        $terminal->departamento_id = $request->departamento;
        $terminal->municipio_id = $request->municipio;
        $terminal->save();

        return redirect()->route('ruta.index');
    }
    public function show(){
        $terminales = Terminales::all();
        return view('terminales.showterminales', compact('terminales'));
    }

    public function edit(Terminales $terminal)
    {
        $departamentos = Departamentos::pluck('nombre', 'id');
        $municipios = Municipios::all();   
        return view('terminales.edit',compact('terminal', 'departamentos', 'municipios'));
    }
    public function update(Request $request, Terminales $terminal)
    {


        $terminal->nombre = $request->nombre;
        $terminal->hora_apertura = $request->hora_apertura;
        $terminal->hora_cierre = $request->hora_cierre;
        $terminal->departamento_id = $request->departamento;
        $terminal->municipio_id = $request->municipio;
        $terminal->save();
        return redirect()->route('terminales.show');
    }
}
