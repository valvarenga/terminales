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
       
        return view('index');
    }
}
