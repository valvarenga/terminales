<?php

namespace App\Trait;

use App\Models\Terminales;



trait Recursos
{
    public function buscar_terminal(Terminales $terminal)
    {
        $terminales = $terminal->autobuses;
        return $terminales;
        //return view('terminales.showterminales', compact('terminales'));
    }

    Public function buscar_autobus(Terminales $terminal)
    {
        $autobuses = $terminal->autobuses;
        return $autobuses;
        //return view('terminales.showterminales', compact('terminales'));
    }
}