<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class AuditLogger
{
    // Never persist passwords, tokens, request bodies or session contents.
    public const FIELDS = [
        'nombre', 'name', 'email', 'role', 'is_active', 'placa', 'categoria',
        'hora_salida', 'hora_llegada', 'origen', 'destino', 'tarifa',
        'municipio_origen_id', 'municipio_destino_id', 'departamento_id', 'municipio_id',
        'hora_apertura', 'hora_cierre', 'latitud', 'longitud', 'url', 'url_M', 'url_T',
        'nombre_terminal', 'ubicacion', 'foto', 'estado', 'motivo_revision',
        'revisada_por', 'revisada_at', 'terminal_id', 'terminales',
    ];

    public static function record(Model $model, string $action, array $before = [], array $after = []): void
    {
        $request = request();
        $actor = $request->hasSession() && $request->session()->get('admin_authenticated')
            ? $request->session()->get('admin_actor', config('admin.username') ?: 'Administrador')
            : (app()->runningInConsole() ? 'Sistema / consola' : 'Visitante');

        AuditLog::create([
            'actor' => $actor,
            'entity' => $model->getTable(),
            'entity_id' => $model->getKey(),
            'label' => mb_substr((string) ($model->nombre ?? $model->nombre_terminal ?? $model->name ?? $model->getKey()), 0, 255),
            'action' => $action,
            'before' => Arr::only($before, self::FIELDS),
            'after' => Arr::only($after, self::FIELDS),
            'created_at' => now(),
        ]);
    }
}
