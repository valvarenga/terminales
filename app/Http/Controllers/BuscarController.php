<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Post;
use Illuminate\Support\Facades\DB;

class BuscarController extends Controller
{
    public function index(){
        $busc = DB::table('autobuses')->get();
        return view('buscar.buscar',compact('busc')); 
    }

}
