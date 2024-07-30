<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cantones;
use App\Models\Parroquias;
use App\Models\Proyectos;
use App\Models\Recintos;
use App\Models\Estados;
use App\Models\Comunidades;
use App\Models\Obras;
use Illuminate\Support\Facades\Storage;
use App\Models\Imageable;

class ProyectoController extends Controller
{
    public function __construct()
    {
        $this->model_proyectos = new Proyectos();
        $this->model_imageable = new Imageable();
        $this->model_cantones = new Cantones();
        $this->model_recintos = new Recintos();
        $this->model_parroquias = new Parroquias();
        $this->model_estados = new Estados();
        $this->model_comunidades = new Comunidades();
        $this->model_obras = new Obras();
    }
    public function index(Request $request)
    {
        return view('layouts.app');
    }
    public function cargarCantones(Request $request)
    {
        $datos["cantones"] = $this->model_cantones->select('*')->where('activo', 1)->get();
        return response()->json(['estado' => 'success', 'data' => $datos]);
    }
    public function cargarParroquias(Request $request)
    {
        $datos["parroquias"] = $this->model_parroquias->select('*')->where('activo', 1)->where('id_canton', $request->canton_id)->get();
        return response()->json(['estado' => 'success', 'data' => $datos]);
    }
    public function cargarRecintos(Request $request)
    {
        $datos["recintos"] = $this->model_recintos->select('*')->where('activo', 1)->where('id_parroquia', $request->parroquia_id)->get();
        return response()->json(['estado' => 'success', 'data' => $datos]);
    }
    public function cargarEstados(Request $request)
    {
        $datos["estados"] = $this->model_estados->select('*', 'estado as nombre')->get();
        return response()->json(['estado' => 'success', 'data' => $datos]);
    }
    public function cargarComunidades(Request $request)
    {
        $datos["comunidades"] = $this->model_comunidades->select('*', 'comunidad as nombre')->get();
        return response()->json(['estado' => 'success', 'data' => $datos]);
    }
    public function cargarObras(Request $request)
    {
        $datos["obras"] = $this->model_obras->select('*', 'obra as nombre')->get();
        return response()->json(['estado' => 'success', 'data' => $datos]);
    }
    public function cargar(Request $request)
    {
        $datos["proyectos"] = $this->model_proyectos
            ->with(['canton', 'parroquia', 'recinto', 'comunidad', 'estado', 'obra'])
            ->get();
        return response()->json(['estado' => 'success', 'data' => $datos]);
    }
    public function guardar(Request $request)
    {
        \DB::beginTransaction();
        try {
            $data = $request->all();

            $data["updated_at"] = now();
            $model = $this->model_proyectos->create($data);

            foreach ($data["adjunto_file"] ?? [] as $key => $file) {

                $adjunto_file = $file;
                $adjunto_nombre = $data["adjunto_nombre"][$key];

                if ($adjunto_file) {
                    $path = Storage::putFileAs(
                        "public/" . config('app.upload_paths.proyecto.imagen'),
                        $adjunto_file,
                        $model->id . '-' . $adjunto_nombre
                    );
                    if (!$path) {
                        \DB::rollback();
                        return response()->json(array("estado" => "error",  "mensaje" => __('Hubo un error al guardar')));
                    }
                    //$model->update(['image' => $model->id . '-' . $adjunto_nombre]);
                    $this->model_imageable->create([
                        'url' => $model->id . '-' . $adjunto_nombre,
                        'imageable_id' => $model->id,
                        'imageable_type' => 'App\Models\Proyectos',
                    ]);
                }
            }
            if ($model) {
                \DB::commit();
                return response()->json(array("estado" => "success", "mensaje" => __('Registro guardado')));
            } else {
                \DB::rollback();
                return response()->json(array("estado" => "error",  "mensaje" => __('Hubo un error al guardar')));
            }
        } catch (\Throwable $th) {
            \DB::rollback();
            return response()->json(array("estado" => "error",  "mensaje" => __('Hubo un error al guardar') . $th->getMessage()));
        }
    }
    public function buscar(Request $request)
    {
        $datos = $this->model_proyectos
            ->where('id', $request["id"])
            ->with([
                'canton', 'parroquia', 'recinto', 'comunidad', 'estado', 'obra',
                'imagen'
            ])
            ->select('*', 'id_canton as canton_id', 'id_parroquia as parroquia_id', 'id_recinto as recinto_id', 'id_estado as estado_id', 'id_comunidad as comunidad_id', 'id_obra as obra_id', \DB::raw('date_format(fecha_inicio,"%Y-%m-%dT%H:%i:%s") as fecha_inicio'), \DB::raw('date_format(fecha_fin,"%Y-%m-%dT%H:%i:%s") as fecha_fin'))
            ->first();
        /* => function ($q) { 
                 $q->select('*',\DB::raw("CONCAT('" . env('APP_URL') . "/storage/" . config('app.upload_paths.proyecto.imagen') . "/',url) as image"));
            } */
        return response()->json($datos);
    }
    public function eliminar(Request $request)
    {
        try {
            $data = $request->all();
            $model = $this->model_proyectos->where('id', $data["id"])->first();
            $imageable = $this->model_imageable
                ->where('imageable_id', $model->id)
                ->where('imageable_type', 'App\Models\Proyectos')->get();
            if ($imageable) {
                foreach ($imageable as $key => $file) {
                    $exists = Storage::disk('public')->exists(config('app.upload_paths.proyecto.imagen') . '/' . $file->url);
                    if ($exists) {
                        $delete = Storage::disk('public')->delete(config('app.upload_paths.proyecto.imagen') . '/' . $file->url);
                        if (!$delete) {
                            return response()->json(array("estado" => "error",  "mensaje" => __('Hubo un error al eliminar')));
                        }
                    }
                    $file->delete();
                }
            }

            $response = $model->delete();
            if ($response) {
                return response()->json(array("estado" => "success", "mensaje" => __('Registro eliminado')));
            } else {
                return response()->json(array("estado" => "error",  "mensaje" => __('Hubo un error al eliminar')));
            }
        } catch (\Throwable $th) {
            return response()->json(array("estado" => "error",  "mensaje" => __('Hubo un error al eliminar') . $th->getMessage()));
        }
    }
    public function editar(Request $request)
    {
        \DB::beginTransaction();
        try {
            $data = $request->all();
            $adjunto_file = $data["adjunto_file"] ?? [];
            $adjunto_nombre = $data["adjunto_nombre"] ?? [];
            $imagen_id = $data["imagen_id"] ?? [];
            unset($data["adjunto_nombre"]);
            unset($data["adjunto_file"]);
            unset($data["imagen_id"]);
            $model = $this->model_proyectos->where('id', $data["id"])->first();

            foreach ($adjunto_file as $key => $file) {
                $adjunto_nombre = $adjunto_nombre[$key];

                if ($file) {
                    $imagen_id = $imagen_id[$key];
                    $imageable = $this->model_imageable
                        ->where('id', $imagen_id)
                        ->first();
                    if ($imageable) {
                        $exists = Storage::disk('public')->exists(config('app.upload_paths.proyecto.imagen') . '/' . $imageable->url);
                        if ($exists) {
                            $delete = Storage::disk('public')->delete(config('app.upload_paths.proyecto.imagen') . '/' . $imageable->url);
                            if (!$delete) {
                                \DB::rollback();
                                return response()->json(array("estado" => "error", "mensaje" => __('Hubo un error al modificar')));
                            }
                        }
                        $imageable
                        ->update([
                            'imageable_id' => $model->id,
                            'imageable_type' => 'App\Models\Proyectos',
                            'url' => $model->id . '-' . $adjunto_nombre,
                        ]);
                    }else{
                        $this->model_imageable
                            ->create([
                            'imageable_id' => $model->id,
                            'imageable_type' => 'App\Models\Proyectos',
                            'url' => $model->id . '-' . $adjunto_nombre,
                        ]);
                    }
                    $path = Storage::putFileAs(
                        "public/" . config('app.upload_paths.proyecto.imagen'),
                        $file,
                        $model->id . '-' . $adjunto_nombre
                    );
                    if (!$path) {
                        \DB::rollback();
                        return response()->json(array("estado" => "error",  "mensaje" => __('Hubo un error al modificar')));
                    }
                    
                }
            }
            $data["updated_at"] = now();
            $response = $this->model_proyectos->where('id', $data["id"])->update($data);
            if ($response) {
                \DB::commit();
                return response()->json(array("estado" => "success", "mensaje" => __('Registro modificado')));
            } else {
                \DB::rollback();
                return response()->json(array("estado" => "error",  "mensaje" => __('Hubo un error al modificar')));
            }
        } catch (\Throwable $th) {
            \DB::rollback();
            return response()->json(array("estado" => "error",  "mensaje" => __('Hubo un error al modificar') . $th->getMessage()));
        }
    }
}
