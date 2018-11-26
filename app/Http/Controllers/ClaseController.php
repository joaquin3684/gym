<?php

namespace App\Http\Controllers;

use App\services\ClaseService;
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
            return $this->service->crear($request->all());
        });
    }


    public function update(Request $request, $id)
    {
      return  Db::transaction(function() use ($request, $id){
            $this->service->update($request->all(), $id);
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

    public function all()
    {
        return $this->service->all();
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
            $this->service->registrarEntradas($request->all());
        });
    }

    public function devolverEntrada(Request $request)
    {
        DB::transaction(function($request){
            $this->service->devolverEntradas($request->all());
        });

    }
 
}
