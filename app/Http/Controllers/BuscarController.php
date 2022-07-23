<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Municipios;
use App\Models\Terminales;

class BuscarController extends Controller
{
    public function index( Request $request){
        $municipio= $request->get('id');
        $terminales=Terminales::where('municipio_id',$municipio)->get();
        //return $request->all();
        return view('buscar.buscar', compact('terminales')); 
    }
}
