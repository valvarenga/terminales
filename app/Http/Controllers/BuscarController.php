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

        // Demand statistics contain no IP addresses or visitor identities.
        $searchKey = $origen->id.':'.$destino->id;
        $previous = $request->session()->get('last_route_search', []);
        if (($previous['key'] ?? null) !== $searchKey || ($previous['at'] ?? 0) < now()->timestamp - 60) {
            \Illuminate\Support\Facades\DB::table('route_searches')->insert([
                'origin_id' => $origen->id, 'destination_id' => $destino->id,
                'origin_name' => $origen->nombre, 'destination_name' => $destino->nombre,
                'result_count' => $itinerarios->count(), 'created_at' => now(),
            ]);
            $request->session()->put('last_route_search', ['key' => $searchKey, 'at' => now()->timestamp]);
        }

        return view('rutas.resultados', compact('origen', 'destino', 'itinerarios'));
    }
}
