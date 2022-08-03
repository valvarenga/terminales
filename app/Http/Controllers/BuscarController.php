<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Municipios;
use App\Models\Terminales;

class BuscarController extends Controller
{
    public function index(Request $request){
        return $request;
        
        //$terminales=Terminales::where('municipio_id','=',$request->id)->get();
       // return $terminales;
        //return view('buscar.buscar', compact('terminales')); 
    }
}
