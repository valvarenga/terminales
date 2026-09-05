<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AuditLogController extends Controller
{
    public const ENTITIES = ['autobuses' => 'Autobuses', 'terminales' => 'Terminales', 'municipios' => 'Municipios', 'departamentos' => 'Departamentos', 'sugerencias_terminales' => 'Sugerencias', 'users' => 'Usuarios'];

    public function index(Request $request)
    {
        $filters = $request->validate([
            'entity' => ['nullable', Rule::in(array_keys(self::ENTITIES))],
            'q' => ['nullable', 'string', 'max:100'],
            'from' => ['nullable', 'date_format:Y-m-d'],
            'to' => ['nullable', 'date_format:Y-m-d', ...($request->filled('from') ? ['after_or_equal:from'] : [])],
        ]);
        $logs = AuditLog::query()
            ->when($filters['entity'] ?? null, fn ($q, $value) => $q->where('entity', $value))
            ->when($filters['q'] ?? null, fn ($q, $value) => $q->where(fn ($q) => $q->where('actor', 'like', '%'.$value.'%')->orWhere('label', 'like', '%'.$value.'%')))
            ->when($filters['from'] ?? null, fn ($q, $value) => $q->whereDate('created_at', '>=', $value))
            ->when($filters['to'] ?? null, fn ($q, $value) => $q->whereDate('created_at', '<=', $value))
            ->latest('id')->paginate(25)->withQueryString();

        return view('admin.history', ['logs' => $logs, 'entities' => self::ENTITIES]);
    }
}
