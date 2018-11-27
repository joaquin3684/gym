<?php

namespace App\Http\Controllers;

use App\services\ProductosService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = new ProductosService();
    }

    public function store(Request $request)
    {
        return  Db::transaction(function() use ($request){
            $id = $this->service->crear($request->all());
            if($request->hasFile('foto'))
                Storage::putFileAs('public/productos', $request->foto, 'foto'.$id.'.jpg');

            return $id;
        });
    }

    public function update(Request $request, $id)
    {
        Db::transaction(function() use ($request, $id){
            $this->service->update($request->all(), $id);
            if($request->hasFile('foto'))
                Storage::putFileAs('public/productos', $request->foto, 'foto'.$id.'.jpg');

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

    public function all()
    {
        return $this->service->all();
    }

    public function comprar(Request $request)
    {
        Db::transaction(function() use ($request){
            $this->service->comprar($request->all(), $request['userId']);
        });
    }

    public function vender(Request $request)
    {
        Db::transaction(function() use ($request){
            $this->service->vender($request->all(), $request['userId']);
        });
    }

    public function registrosDeStock(Request $request)
    {
        return $this->service->registrosDeStock($request->all());
    }
}
