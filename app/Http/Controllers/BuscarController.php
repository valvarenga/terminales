<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Post;
use Illuminate\Support\Facades\DB;
use App\Models\Terminales;
use App\Models\Municipios;

class BuscarController extends Controller
{
    public function index(Request $request){
        
       $municipio_id=$request->get('id_municipio');
        $municipio= new Municipios();
        $terminales=Terminales::all()->where('municipio_id',$municipio_id);
        return view('buscar.buscar', compact('terminales',));
    }

}
