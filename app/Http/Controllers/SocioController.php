<?php

namespace App\Http\Controllers;

use App\services\SocioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class SocioController extends Controller
{
    private $service;
    public function __construct()
    {
        $this->service = new SocioService();
    }


    public function store(Request $request)
    {
        $this->service->crear($request->all());
    }


    public function show($id)
    {
        return $this->service->find($id);
    }

    public function update($id, Request $request)
    {
        $this->service->update($request->all(), $id);
    }

    public function all()
    {
        return $this->service->all();
    }

    public function comprar(Request $request)
    {
        DB::transaction(function () use ($request){
            $this->service->comprar($request->all());
        });
    }

    public function acceder(Request $request)
    {
        return DB::transaction(function () use ($request){
           return $this->service->acceder($request->all());
        });
    }

    public function historialCompra($idSocio)
    {
        return $this->service->historialCompra($idSocio);
    }


}
