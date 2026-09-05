<?php

namespace App\Observers;

use App\Services\AuditLogger;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class AuditObserver
{
    public function created(Model $model): void
    {
        AuditLogger::record($model, 'creado', [], $model->getAttributes());
    }

    public function updated(Model $model): void
    {
        $changes = Arr::only($model->getChanges(), AuditLogger::FIELDS);
        if ($changes) {
            AuditLogger::record($model, 'actualizado', Arr::only($model->getRawOriginal(), array_keys($changes)), $changes);
        }
        if ($model instanceof \App\Models\User && $model->wasChanged('password')) {
            AuditLogger::record($model, 'contraseña_actualizada');
        }
    }

    public function deleted(Model $model): void
    {
        AuditLogger::record($model, 'eliminado', $model->getAttributes());
    }
}
