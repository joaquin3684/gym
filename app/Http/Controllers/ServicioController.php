<?php

namespace App\Http\Controllers;

use App\services\ServicioService;
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
        Db::transaction(function() use ($request){
            $this->service->crear($request->all());
        });
    }

    public function update(Request $request, $id)
    {
        Db::transaction(function() use ($request, $id){
            $this->service->update($request->all(), $id);
        });
    }

    public function delete(Request $request)
    {
        $this->service->delete($request['id']);
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
