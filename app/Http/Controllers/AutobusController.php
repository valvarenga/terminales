<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Autobuses;
use App\Models\Terminales;
class AutobusController extends Controller
{
    //
    public function index()
    {
        $terminales = Terminales::all();

        return view('autobus.index', compact('terminales'));
    }

    public function store(Request $request)
    {
        $autobus = new Autobuses();
        $autobus->nombre = $request->nombre;
        $autobus->placa = $request->placa;
        $autobus->hora_salida = $request->hora_salida;
        $autobus->hora_llegada = $request->hora_llegada;
        $autobus->terminal_id= $request->terminal;
        $autobus->save();
        
        return redirect()->route('ruta.index');
    }
}
