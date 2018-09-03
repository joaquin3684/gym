<?php

namespace App\Http\Controllers;

use App\services\VendibleService;
use Illuminate\Http\Request;

class VendiblesController extends Controller
{

    private $service;
    public function __construct()
    {
        $this->service = new VendibleService();
    }


    public function clases()
    {
        return $this->service->clases();
    }

    public function articulos()
    {
        return $this->service->articulos();
    }
}
