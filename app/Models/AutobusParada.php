<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AutobusParada extends Model
{
    protected $table = 'autobus_paradas';

    protected $fillable = ['municipio_id', 'posicion', 'hora_paso', 'tarifa_acumulada'];

    protected $casts = ['posicion' => 'integer', 'tarifa_acumulada' => 'decimal:2'];

    public function autobus()
    {
        return $this->belongsTo(Autobuses::class, 'autobus_id');
    }

    public function municipio()
    {
        return $this->belongsTo(Municipios::class, 'municipio_id');
    }
}
