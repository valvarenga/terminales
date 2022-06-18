<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Terminales;
use App\Models\Autobuses;

class IndexController extends Controller
{
    //
    public function index()
    {
        $terminales = Terminales::all();
        $autobuses = Autobuses::all();
        return view('index', compact('terminales','autobuses'));
    }
}
