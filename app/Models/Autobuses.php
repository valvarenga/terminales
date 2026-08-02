<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Autobuses extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'categoria', 'placa', 'origen', 'hora_salida', 'destino', 'hora_llegada', 'municipio_origen_id', 'municipio_destino_id'];

    public function getRouteKeyName()
    {
        return 'slug';
    }
    


    public function terminales(){
        return $this->belongsToMany('App\Models\Terminales','autobus_terminal','autobus_id','terminal_id');
    }

    public function origenMunicipio()
    {
        return $this->belongsTo(Municipios::class, 'municipio_origen_id');
    }

    public function destinoMunicipio()
    {
        return $this->belongsTo(Municipios::class, 'municipio_destino_id');
    }

    
}
