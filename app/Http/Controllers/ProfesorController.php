<?php

namespace App\Http\Controllers;

use App\services\ProfesorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfesorController extends Controller
{
    private $service;
    public function __construct()
    {
        $this->service = new ProfesorService();
    }


    public function store(Request $request)
    {
        return Db::transaction(function() use ($request){
            $this->service->crear($request->all());
        });
    }


    public function show($id)
    {
        return $this->service->find($id);
    }

    public function update($id, Request $request)
    {
        return Db::transaction(function() use ($request, $id) {

            $this->service->update($request->all(), $id);
        });
    }

    public function all()
    {
        return $this->service->all();
    }

    public function delete(Request $request)
    {
        Db::transaction(function() use ($request) {

            $this->service->delete($request['id']);
        });
    }

    public function profesoresAPagar()
    {
        $this->service->profesoresAPagar();
    }

}
