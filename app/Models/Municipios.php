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
}
