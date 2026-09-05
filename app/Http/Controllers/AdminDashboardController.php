<?php

namespace App\Http\Controllers;

use App\Models\Autobuses;
use App\Models\Departamentos;
use App\Models\Municipios;
use App\Models\SugerenciaTerminal;
use App\Models\Terminales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->validate(['days' => ['nullable', 'integer', 'in:7,30,90']]);
        $days = (int) ($data['days'] ?? 30);
        $searches = DB::table('route_searches')->where('created_at', '>=', now()->subDays($days));
        $totalSearches = (clone $searches)->count();
        $emptySearches = (clone $searches)->where('result_count', 0)->count();
        $popular = (clone $searches)
            ->select('origin_id', 'destination_id', 'origin_name', 'destination_name')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('origin_id', 'destination_id', 'origin_name', 'destination_name')
            ->orderByDesc('total')->limit(8)->get();
        $unserved = (clone $searches)->where('result_count', 0)
            ->select('origin_name', 'destination_name')
            ->selectRaw('COUNT(*) as total')->groupBy('origin_name', 'destination_name')
            ->orderByDesc('total')->limit(8)->get();
        $totals = [
            'Departamentos' => Departamentos::count(),
            'Municipios' => Municipios::count(),
            'Terminales' => Terminales::count(),
            'Servicios' => Autobuses::count(),
        ];
        $pendingSuggestions = SugerenciaTerminal::where('estado', 'pendiente')->count();
        $withoutFare = Autobuses::whereNull('tarifa')->count();
        $incompleteServices = Autobuses::whereNull('municipio_origen_id')->orWhereNull('municipio_destino_id')->count();
        $attentionServices = Autobuses::where(function ($query) {
            $query->whereNull('tarifa')->orWhereNull('municipio_origen_id')->orWhereNull('municipio_destino_id');
        })->orderBy('nombre')->limit(8)->get();

        return view('admin.dashboard', compact('days', 'totalSearches', 'emptySearches', 'popular', 'unserved', 'totals', 'pendingSuggestions', 'withoutFare', 'incompleteServices', 'attentionServices'));
    }
}
