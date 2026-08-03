<?php

namespace App\Http\Controllers;

use App\Models\Departamentos;

class PeticionAjaxController extends Controller
{
    public function ajax_municipios($departamento_id)
    {
        $departamento = Departamentos::findOrFail($departamento_id);

        return response()->json($departamento->municipios()->orderBy('nombre')->get(['id', 'nombre']));
    }
}
