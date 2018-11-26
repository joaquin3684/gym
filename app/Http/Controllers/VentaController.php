<?php

namespace App\Http\Controllers;

use App\services\VentaService;
use App\Venta;
use Illuminate\Http\Request;

class VentaController extends Controller
{

    private $service;
    public function __construct()
    {
        $this->service = new VentaService();
    }

    public function ventas(Request $request)
    {
        $fechaInicio = $request['fechaInicio'];
        $fechaFin = $request['fechaFin'];
        return $this->service->ventas($fechaInicio, $fechaFin);
    }

    public function historialCompra($idSocio)
    {
        return $this->service->historialCompra($idSocio);
    }


}
