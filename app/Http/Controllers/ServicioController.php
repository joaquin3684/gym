<?php

namespace App\Http\Controllers;

use App\services\ServicioService;
use App\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServicioController extends Controller
{
    private $service;
    public function __construct()
    {
        $this->service = new ServicioService();
    }

    public function store(Request $request)
    {
       return  Db::transaction(function() use ($request){
           return $this->service->crear($request['nombre'], $request['creditos_minimos'], $request['registra_entrada'], $request['dias']);
        });
    }

    public function update(Request $request, $id)
    {
        return Db::transaction(function() use ($request, $id){
            $this->service->update($request['nombre'], $request['creditos_minimos'], $request['registra_entrada'], $request['dias'], Servicio::find($id));
        });
    }

    public function delete(Request $request)
    {
        $this->service->delete(Servicio::find($request['id']));
    }

    public function find($id)
    {
        return $this->service->find($id);
    }

    public function servicios()
    {
        return $this->service->servicios();
    }

    public function registrarEntradas(Request $request)
    {
        Db::transaction(function() use ($request){
            $this->service->registrarEntradas($request->all());
        });
    }

    public function devolverEntradas(Request $request)
    {
        Db::transaction(function() use ($request){
            $this->service->devolverEntradas($request->all());
        });
    }

    public function accesos($idSocio)
    {
        return $this->service->accesos($idSocio);
    }
}
