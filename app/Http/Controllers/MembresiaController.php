<?php

namespace App\Http\Controllers;

use App\services\MembresiaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MembresiaController extends Controller
{

    private $service;
    public function __construct()
    {
        $this->service = new MembresiaService();
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

    public function membresias()
    {
        return $this->service->membresias();
    }

}
