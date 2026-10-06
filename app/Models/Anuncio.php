<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Anuncio extends Model
{
    protected $table = 'anuncios';

    protected $fillable = ['titulo', 'negocio', 'imagen', 'enlace', 'activo', 'orden'];

    protected $casts = ['activo' => 'boolean', 'orden' => 'integer'];

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }
}
