<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cantones;
use App\Models\Obras;
use App\Models\Parroquias;
use App\Models\Proyectos;
use App\Models\Recintos;

class HomeController extends Controller
{
    public function __construct(){
        $this->model_cantones = new Cantones();
        $this->model_recintos = new Recintos();
        $this->model_parroquias = new Parroquias();
        $this->model_proyectos = new Proyectos();
        $this->model_obras = new Obras();
    }
    public function index(Request $request){
        return view('layouts.web');
    }
    public function cargarFiltros(Request $request){
        $datos["cantones"] = $this->model_cantones->select('*')->where('activo',1)->get();
        return response()->json(['estado'=>'success','data'=>$datos]);
    }
    public function cargarParroquias(Request $request){
        $datos["parroquias"] = $this->model_parroquias->select('*')->where('activo',1)->where('id_canton',$request->canton_id)->get();
        return response()->json(['estado'=>'success','data'=>$datos]);
    }
    public function cargarRecintos(Request $request){
        $datos["recintos"] = $this->model_recintos->select('*')->where('activo',1)->where('id_parroquia',$request->parroquia_id)->get();
        return response()->json(['estado'=>'success','data'=>$datos]);
    }
    public function cargarObras(Request $request){
      $datos["obras"] = $this->model_obras->select('*')->get();
      return response()->json(['estado'=>'success','data'=>$datos]);
  }
    public function cargarProyectos(Request $request){
        $datos["proyectos"] = $this->model_proyectos;
        if($request->canton_id ?? false){
            $datos["proyectos"] = $datos["proyectos"]->where('id_canton',$request->canton_id);
        }
        if($request->parroquia_id ?? false){
            $datos["proyectos"] = $datos["proyectos"]->where('id_parroquia',$request->parroquia_id);
        }
        if($request->obra_id ?? false){
          $datos["proyectos"] = $datos["proyectos"]->where('id_obra',$request->obra_id);
      }
        if($request->comunidad_id ?? false){
            $datos["proyectos"] = $datos["proyectos"]->where('id_comunidad',$request->comunidad_id);
        }
        if($request->proyecto ?? false){
            $datos["proyectos"] = $datos["proyectos"]->where('proyecto','LIKE','%'.$request->proyecto.'%');
        }
        $datos["proyectos"] = $datos["proyectos"]
        ->with(['canton','parroquia','recinto','comunidad','estado','obra',
        'imagen'=>function($q){
            $q->select('*',\DB::raw("CONCAT('" . env('APP_URL') . "/storage/" . config('app.upload_paths.proyecto.imagen') . "/',url) as image"));
        }])
        ->select('*')
        ->get();
        return response()->json(['estado'=>'success','data'=>$datos]);
    }
}
