<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BuscarController extends Controller
{
    public function index(){
        return view('buscar.buscar'); 
    }
}
