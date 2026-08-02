<?php

namespace App\Http\Controllers;

use App\Models\Municipios;
use App\Services\RouteFinder;
use Illuminate\Http\Request;

class BuscarController extends Controller
{
    public function index(Request $request, RouteFinder $routeFinder)
    {
        $data = $request->validate([
            'origen_id' => ['required', 'integer', 'different:destino_id', 'exists:municipios,id'],
            'destino_id' => ['required', 'integer', 'exists:municipios,id'],
        ]);

        $origen = Municipios::findOrFail($data['origen_id']);
        $destino = Municipios::findOrFail($data['destino_id']);
        $itinerarios = $routeFinder->find($origen->id, $destino->id);

        return view('rutas.resultados', compact('origen', 'destino', 'itinerarios'));
    }
}
