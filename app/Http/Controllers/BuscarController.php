<?php

namespace App\Http\Controllers;

use App\Models\Municipios;
use App\Services\RouteFinder;
use Illuminate\Http\Request;

class BuscarController extends Controller
{

public function index(Request $request, RouteFinder $routeFinder)
{
    $origenId = $request->input('origen_id');
    $destinoId = $request->input('destino_id');

    $origenNombre = trim((string) $request->input('origen', ''));
    $destinoNombre = trim((string) $request->input('destino', ''));

    $resolver = function ($nombre) {
        if ($nombre === '') {
            return null;
        }

        return Municipios::whereRaw(
            'LOWER(TRIM(nombre)) = ?',
            [mb_strtolower(trim($nombre))]
        )->first();
    };

    // Buscar por ID
    $origen = $origenId
        ? Municipios::find($origenId)
        : null;

    $destino = $destinoId
        ? Municipios::find($destinoId)
        : null;

    // Si no hay ID, buscar por nombre
    if (!$origen) {
        $origen = $resolver($origenNombre);
    }

    if (!$destino) {
        $destino = $resolver($destinoNombre);
    }

    $request->merge(['origen_id' => $origen?->id, 'destino_id' => $destino?->id]);
    $request->validate([
        'origen_id' => ['required', 'integer', 'different:destino_id', 'exists:municipios,id'],
        'destino_id' => ['required', 'integer', 'exists:municipios,id'],
    ]);

    // Origen no encontrado
    if (!$origen) {
        return redirect()
            ->to(route('home') . '#buscar-ruta')
            ->withInput()
            ->with('error', 'No pudimos identificar el municipio de origen.');
    }

    // Destino no encontrado
    if (!$destino) {
        return redirect()
            ->to(route('home') . '#buscar-ruta')
            ->withInput()
            ->with('error', 'No pudimos identificar el municipio de destino.');
    }

    // Mismo municipio
    if ($origen->id === $destino->id) {
        return redirect()
            ->to(route('home') . '#buscar-ruta')
            ->withInput()
            ->with('error', 'El origen y el destino deben ser diferentes.');
    }

    // Buscar rutas
    $itinerarios = $routeFinder->find(
        $origen->id,
        $destino->id
    );

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

    return view(
        'rutas.resultados',
        compact('origen', 'destino', 'itinerarios')
    );
}
}
