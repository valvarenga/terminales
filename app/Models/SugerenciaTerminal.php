<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SugerenciaTerminal extends Model
{
    use HasFactory;

    protected $table = 'sugerencias_terminales';

    protected $fillable = ['nombre_terminal', 'ubicacion', 'foto'];
}
