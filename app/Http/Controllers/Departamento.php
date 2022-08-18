<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Departamentos;
use App\Models\Municipios;
use App\Models\Terminales;
use App\Trait\Recursos;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

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
            'file_D' => 'required|image|max:2048'
        ],$message =['required'=>'el campo :attribute es requerido', 'image'=> 'El archivo debe ser una imagen',
        'max'=> 'Ha superado el tamaño limite']);

       $departamento = new Departamentos();
        $departamento->nombre = $request->nombre;
        $departamento->slug= $slug;
        $imagenes= $request->file('file_D')->store('public/imagenes/departamento'); //guarda la imagen carpeta local
        $url=Storage::url($imagenes); //captura la url de la img
       $departamento->url=$url;
       $departamento->save();
        
        
        return redirect()->route('ruta.index');
    }

    public function update(Request $request, Departamentos $departamento)
    {
        $slug = Str::slug($request->nombre);

        $request -> validate([
            'nombre' => 'required',
        ]);

        $departamento->nombre = $request->nombre_departamento;
        $departamento->slug= $slug;
        $departamento->save();

        return response()->json(['success'=>'Departamento actualizado correctamente.']);

    }

    public function show(){
        $departamentos = Departamentos::all();
        return view('departamentos.show_departamentos', compact('departamentos'));
    }
    
    public function ver_departamento(Departamentos $departamento)
    {
        $departamentos = Departamentos::find($departamento->id);
        return view('departamentos.edit', compact('departamentos'));
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
        
        return view('departamentos.terminales_departamentos', compact('terminales'));
    }

    public function buscar_autobuses(Terminales $terminal){
        $autobuses = $terminal->autobuses;
        //return $autobuses;
        return view('departamentos.terminales_departamentos', compact('terminal','autobuses'));
    }


}
