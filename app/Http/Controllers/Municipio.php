<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Departamentos;
use App\Models\Municipios;
use App\Models\Terminales;
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

        //return $municipios;
        return view('municipios.show', compact('municipios'));

    }

    public function ver(Municipios $municipio)
    {
        $departamento = $municipio->departamentos;
        return view('municipios.ver', compact('municipio','departamento'));
    }
    public function edit(Municipios $municipio)
    {
        $departamento= $municipio->departamentos;
        $todos_departamentos=Departamentos::all()->where('id','<>',$municipio->departamento_id);
        //return $departamento;
        return view('municipios.edit', compact('municipio','departamento','todos_departamentos'));

    }

    public function update(Request $request, Municipios $municipio)
    {
        $slug = Str::slug($request->nombre);
        $request -> validate([
            'nombre' => 'required',
            'departamento_id' => 'required',
        ]);
        $municipio->nombre = $request->nombre;
        $municipio->slug= $slug;
        $municipio->departamento_id = $request->departamento_id;
        $municipio->save();
        return $this->ver($municipio);
    }
    public function municipio_terminales(Municipio $municipio){
        $terminales= $municipio->terminales;
        return $terminales;
        //return view('departamentos.terminales_departamentos', compact('municipio'));
    }

    //Funcion que busca los departamentos desde el cuadro de busqueda
    public function search(Request $request)
    {
        $term = $request->get('term');
        
        $municipios = Municipios::where('nombre', 'like', '%' . $term . '%')->get();
        $data = [];

        foreach ($municipios as $municipio) {
            $data[] = [
                'id' => $municipio->id,
                'value' => $municipio->nombre
            ];
        }
        return $data;

        
    }


}

 
