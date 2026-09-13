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

    return view(
        'rutas.resultados',
        compact('origen', 'destino', 'itinerarios')
    );
}
}