<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Terminales extends Model
{
    use HasFactory;

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function municipios(){
        return $this->belongsTo('App\Models\Municipios','municipio_id');
    }

    public function departamentos(){
        return $this->belongsTo('App\Models\Departamentos','departamento_id');
    }

    public function autobuses(){
        return $this->belongsToMany('App\Models\Autobuses','autobus_terminal','terminal_id','autobus_id');
    }
}
