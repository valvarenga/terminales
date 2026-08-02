<?php

namespace App\Http\Controllers;

use App\Models\Departamentos;

class PeticionAjaxController extends Controller
{
    public function ajax_municipios(Departamentos $departamento)
    {
        return response()->json($departamento->municipios()->orderBy('nombre')->get(['id', 'nombre']));
    }
}
