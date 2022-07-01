<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Departamentos;

class Departamento extends Controller
{
    //
    public function index()
    {
        return view('departamentos.departamento');
    }

    public function store(Request $request)
    {
        $request -> validate([
            'nombre' => 'required',
        ]);

        $departamento = new Departamentos();
        
        $departamento->nombre = $request->nombre;

        $departamento->save();
        
        return redirect()->route('ruta.index');
    }

    public function edit(Request $request)
    {
        
    }
}
