<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Autobuses extends Model
{
    use HasFactory;

    public function getRouteKeyName()
    {
        return 'slug';
    }
    


    public function terminales(){
        return $this->belongsToMany('App\Models\Terminales','autobus_terminal','autobus_id','terminal_id');
    }
}
