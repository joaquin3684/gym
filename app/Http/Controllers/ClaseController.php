<?php

namespace App\Http\Controllers;

use App\Clase;
use App\services\ClaseService;
use App\Servicio;
use App\Socio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClaseController extends Controller
{
    private $service;
    public function __construct()
    {
        $this->service = new ClaseService();
    }

    public function create(Request $request)
    {
        return  Db::transaction(function() use ($request){

            $fecha = $request['fecha'];
            $desde = $request['desde'];
            $hasta = $request['hasta'];
            $entradaDesde = $request['entradaDesde'];
            $entradaHasta = $request['entradaHasta'];
            $estado = $request['estado'];
            $servicio = Servicio::find($request['idServicio']);
            $profesores = $request['profesores'];


            $clase = $this->service->crear($fecha, $servicio, $desde, $hasta, $entradaDesde, $entradaHasta, $estado, $profesores);
            return $clase->id;
        });
    }


    public function update(Request $request, $id)
    {
       Db::transaction(function() use ($request, $id){

          $fecha = $request['fecha'];
          $desde = $request['desde'];
          $hasta = $request['hasta'];
          $entradaDesde = $request['entradaDesde'];
          $entradaHasta = $request['entradaHasta'];
          $estado = $request['estado'];
          $servicio = Servicio::find($request['idServicio']);
          $profesores = $request['profesores'];
          $clase = Clase::find($id);

          $this->service->update($fecha, $servicio, $desde, $hasta, $entradaDesde, $entradaHasta, $estado, $profesores, $clase);

      });
    }

    public function clasesDelDia()
    {
        return $this->service->clasesDelDia();
    }

    public function clasesEnTranscurso()
    {
        return $this->service->clasesEnTranscurso();
    }

    public function clasesFuturas()
    {
        return $this->service->clasesFuturas();
    }

    public function all(Request $request)
    {
        return $this->service->all($request['fechaDesde'], $request['fechaHasta']);
    }

    public function registrarAlumnos(Request $request)
    {
        DB::transaction(function ($request){
            $this->service->registrarAlumnos($request->all());

        });
    }

    public function sacarAlumnos(Request $request)
    {
        DB::transaction(function ($request) {

            $this->service->sacarAlumnos($request->all());
        });
    }

    public function registrarEntrada(Request $request)
    {
        DB::transaction(function ($request) {
            $socios = Socio::whereIn('id', $request['socios'])->get();
            $clase = Clase::find($request['idClase']);
            $this->service->registrarEntradas($clase, $socios);
        });
    }

    public function devolverEntrada(Request $request)
    {
        DB::transaction(function($request){
            $socios = Socio::whereIn('id', $request['socios'])->get();
            $clase = Clase::find($request['idClase']);
            $this->service->devolverEntradas($clase, $socios);
        });

    }
 
}
