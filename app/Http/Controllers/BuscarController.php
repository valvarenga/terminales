<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Post;
use Illuminate\Support\Facades\DB;
use App\Models\Terminales;
use App\Models\Municipios;
use App\Models\Autobuses;
use League\CommonMark\Normalizer\SlugNormalizer;

class BuscarController extends Controller
{
    public function index(Request $request){
        
       $municipio_id=$request->get('id_municipio');
        $terminales=Terminales::all()->where('municipio_id',$municipio_id);
        if(count($terminales)>=2){
            return view('buscar.buscar', compact('terminales',));
        }
        elseif(count($terminales)==1){
            
            $terminal=$terminales;
            foreach($terminal as $ter){
                $nombre= $ter->slug;
                return redirect(route('departamento.autobuses', $ter));
            }
        }
        else
        {
           echo "No hay terminales";
        }
            
                
    }        
        
    

}
