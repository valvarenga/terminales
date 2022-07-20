<?php

namespace App\Http\Controllers;

use App\Models\Departamentos;
use App\Models\Municipios;
use Illuminate\Http\Request;

class PeticionAjaxController extends Controller
{
    public function ajax_municipios(Departamentos $departamento)
    {
        
        $municipios = Municipios::where('departamento_id', $departamento->id)->get();
        return response()->json($municipios, 200);
    }
}
