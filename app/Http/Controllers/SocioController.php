<?php

namespace App\Http\Controllers;

use App\services\SocioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class SocioController extends Controller
{
    private $service;
    public function __construct()
    {
        $this->service = new SocioService();
    }


    public function store(Request $request)
    {
        return Db::transaction(function() use ($request){
            $id = $this->service->crear($request->all());
            if($request->hasFile('foto'))
                Storage::putFileAs('public/socios', $request->foto, 'foto'.$id.'.jpg');

            return $id;
        });
    }

    public function show($id)
    {
        return $this->service->find($id);
    }

    public function update($id, Request $request)
    {
        return Db::transaction(function() use ($request, $id){
            $this->service->update($request->all(), $id);

            if($request->hasFile('foto'))
                Storage::putFileAs('public/socios', $request->foto, 'foto'.$id.'.jpg');
        });
    }

    public function all()
    {
        return $this->service->all();
    }

    public function comprar(Request $request)
    {
         DB::transaction(function () use ($request){
            $this->service->comprar($request->all(), $request['userId']);
        });
    }

    public function acceder(Request $request)
    {
        return DB::transaction(function () use ($request){
           return $this->service->acceder($request->all());
        });
    }

    public function accesos($idSocio)
    {
        return $this->service->accesos($idSocio);
    }

    public function sociosConCompras()
    {
        return $this->service->sociosConCompra();
    }
}
