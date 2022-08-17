<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departamentos extends Model
{
    use HasFactory;
   // protected $fillable=[''];
    public function getRouteKeyName()
    {
        return 'slug';
    }
    

    //relación uno a muchos
    public function municipios(){
        return $this->hasMany('App\Models\Municipios', 'departamento_id');
    }

    public function terminales(){
        return $this->hasMany('App\Models\Terminales', 'departamento_id');
    }
}
