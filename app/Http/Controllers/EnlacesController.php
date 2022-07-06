<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EnlacesController extends Controller
{
    //
    public function index()
    {
        return view('enlaces.acerca');
    }

    public function show()
    {
        $enlaces = Enlace::all();
        return view('enlaces.contacto', compact('enlaces'));
    }
}
