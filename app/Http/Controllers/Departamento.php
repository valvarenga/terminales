<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Departamentos;
use App\Models\Municipios;
use App\Models\Terminales;
use App\Trait\Recursos;
use Illuminate\Support\Str;

class Departamento extends Controller
{
    use Recursos;
    //
    public function index()
    {
        return view('departamentos.departamento');
    }

    public function store(Request $request)
    {
        $slug = Str::slug($request->nombre);
        
        $request -> validate([
            'nombre' => 'required',
        ]);

        $departamento = new Departamentos();
        
        $departamento->nombre = $request->nombre;
        $departamento->slug= $slug;

        $departamento->save();
        
        return redirect()->route('ruta.index');
    }

    public function edit(Request $request)
    {
        
    }

    public function show(){
        $departamentos = Departamentos::all();
        return view('departamentos.show', compact('departamentos'));
    }


    public function listar()
    {
        $departamentos = Departamentos::all();
        return view('departamentos.listar_departamentos', compact('departamentos'));
    }

    public function departamentos_municipios(Departamentos $departamento){
        $municipios= $departamento->municipios;
        //return $departamento;
        return view('departamentos.municipios', compact('departamento','municipios'));
    }

    public function departamento_terminales(Departamentos $departamento, Municipios $municipio){
        $terminales= $departamento->terminales()->where('municipio_id', $municipio->id)->get();
        
        //$autobuses= $this->buscar_autobuses($terminales);
        //$busqueda_terminales= $this->buscar_autobus($terminales->id);
        
        //return $busqueda_terminales;
        //return $terminales;
        return view('departamentos.terminales_departamentos', compact('terminales'));
    }

    public function buscar_autobuses(Terminales $terminal){
        $autobuses = $terminal->autobuses;
        //return $autobuses;
        return view('departamentos.terminales_departamentos', compact('terminal','autobuses'));
    }


}
