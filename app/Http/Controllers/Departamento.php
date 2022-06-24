<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Departamentos;

class Departamento extends Controller
{
    //
    public function index()
    {
        return view('ruta.departamento');
    }

    public function store(Request $request)
    {
        $departamento = new Departamentos();
        
        $departamento->nombre = $request->nombre;

        $departamento->save();
        
        return redirect()->route('ruta.index');
    }

    public function edit(Request $request)
    {
        
    }
}
