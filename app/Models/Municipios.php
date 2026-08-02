<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Municipios extends Model
{
    use HasFactory;
    public function getRouteKeyName()
    {
        return 'slug';
    }

    //relación uno a muchos inversa
    public function departamentos(){
        return $this->belongsTo('App\Models\Departamentos', 'departamento_id');
    }

    public function terminales(){
        return $this->hasMany('App\Models\Terminales', 'municipio_id');
    }

    public function autobusesOrigen()
    {
        return $this->hasMany(Autobuses::class, 'municipio_origen_id');
    }

    public function autobusesDestino()
    {
        return $this->hasMany(Autobuses::class, 'municipio_destino_id');
    }
}
