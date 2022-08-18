<?php

namespace App\Http\Controllers;

use App\Models\Departamentos;
use App\Models\Municipios;
use App\Models\Terminales;
use App\Models\Autobuses;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;



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
            'file_T' => 'required|image|max:2048'
        ],$message =['image'=> 'El archivo debe ser una imagen',
        'max'=> 'Ha superado el tamaño limite']);

        //guardar los datos del formulario en la base de datos
        $terminal = new Terminales();
        $terminal->nombre = $request->nombre;
        $terminal->slug= $slug;
        $terminal->hora_apertura = $request->hora_apertura;
        $terminal->hora_cierre = $request->hora_cierre;
        $terminal->departamento_id = $request->departamento;
        $terminal->municipio_id = $request->municipio;
        $imagenes= $request->file('file_T')->store('public/imagenes/terminal'); //guarda la imagen carpeta local
        $url=Storage::url($imagenes); //captura la url de la img
       $terminal->url_T=$url;
        $terminal->save();

        return redirect()->route('ruta.index');
    }

    public function show(){
        $terminales = Terminales::all();
        return view('terminales.showterminales', compact('terminales'));
    }

    public function verterminal(Terminales $terminales){
        
        $departamento = Departamentos::find($terminales->departamento_id);
        $municipio = Municipios::find($terminales->municipio_id);
        
        //return $terminales;
        return view('terminales.verterminal', compact('terminales','departamento','municipio'));
    }

    public function edit(Terminales $terminal)
    {
        //obtiene el departamento de la terminal
        $departamento = Departamentos::find($terminal->departamento_id);
        //obtiene todos los departamentos
        $todos_departamentos=Departamentos::all()->where('id','<>',$terminal->departamento_id);
        //obtiene el municipio de la terminal
        $municipio = Municipios::find($terminal->municipio_id);
        //obtiene todos lo municipios
        $todos_municipios = Municipios::all()->where('id','<>',$terminal->municipio_id);
        //obtiene el municipio de la terminal;
        //$departamentos->terminales;
         //return $departamento;

        return view('terminales.edit',compact('terminal','municipio','todos_municipios', 'departamento', 'todos_departamentos'));
    }

    public function update(Request $request, Terminales $terminal)
    {

        $slug = Str::slug($request->nombre);
        $terminal->nombre = $request->nombre;
        $terminal->slug=$slug;
        $terminal->hora_apertura = $request->hora_apertura;
        $terminal->hora_cierre = $request->hora_cierre;
        $terminal->departamento_id = $request->departamento;
        $terminal->municipio_id = $request->municipio;
        $terminales=$terminal;
        $terminal->save();
        return $this->verterminal($terminales);
        
    }

    public function destroy(Terminales $terminales)
    {
        $terminales->delete();
        return redirect()->route('show_terminal');   
    }


}

