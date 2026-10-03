<?php

namespace App\Http\Controllers;

use App\Models\Municipios;
use App\Services\RouteFinder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BuscarController extends Controller
{
    public function index(Request $request, RouteFinder $routeFinder)
    {
        $origen = $this->resolveMunicipio($request->input('origen_id'), trim((string) $request->input('origen', '')));
        $destino = $this->resolveMunicipio($request->input('destino_id'), trim((string) $request->input('destino', '')));

        // Both IDs come from existing models; repeating exists queries is unnecessary.
        $request->merge(['origen_id' => $origen?->id, 'destino_id' => $destino?->id]);
        $request->validate([
            'origen_id' => ['required', 'integer', 'different:destino_id'],
            'destino_id' => ['required', 'integer'],
        ]);

        $itinerarios = $routeFinder->find($origen->id, $destino->id);
        $searchKey = $origen->id.':'.$destino->id;
        $previous = $request->session()->get('last_route_search', []);
        $timestamp = now()->timestamp;
        if (($previous['key'] ?? null) !== $searchKey || ($previous['at'] ?? 0) < $timestamp - 60) {
            DB::table('route_searches')->insert([
                'origin_id' => $origen->id, 'destination_id' => $destino->id,
                'origin_name' => $origen->nombre, 'destination_name' => $destino->nombre,
                'result_count' => $itinerarios->count(), 'created_at' => now(),
            ]);
            $request->session()->put('last_route_search', ['key' => $searchKey, 'at' => $timestamp]);
        }

        return view('rutas.resultados', compact('origen', 'destino', 'itinerarios'));
    }

    private function resolveMunicipio($id, string $nombre): ?Municipios
    {
        if (is_scalar($id) && $id && ($municipio = Municipios::find($id))) {
            return $municipio;
        }

        return $nombre === '' ? null : Municipios::whereRaw(
            'LOWER(TRIM(nombre)) = ?', [mb_strtolower($nombre)]
        )->first();
    }
}
