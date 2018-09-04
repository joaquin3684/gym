<?php

namespace App\Http\Controllers;

use App\services\SocioService;
use Illuminate\Http\Request;


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
        $this->service->comprar($request->all());
    }
}
