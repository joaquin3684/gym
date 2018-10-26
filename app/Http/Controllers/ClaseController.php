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



    public function update(Request $request, $id)
    {
        Db::transaction(function() use ($request, $id){
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

    public function registrarAlumnos(Request $request)
    {
        $this->service->registrarAlumnos($request->all());
    }

    public function sacarAlumnos(Request $request)
    {
        $this->service->sacarAlumnos($request->all());
    }
 
}
