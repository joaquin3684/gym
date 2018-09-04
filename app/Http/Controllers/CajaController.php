<?php

namespace App\Http\Controllers;

use App\services\CajaService;
use Illuminate\Http\Request;

class CajaController extends Controller
{


    public function ingreso(Request $request)
    {
        CajaService::ingreso($request['monto'], $request['concepto'], $request['observacion'], $request['tipoPago']);
    }

    public function egreso(Request $request)
    {
        CajaService::ingreso($request['monto'], $request['concepto'], $request['observacion'], $request['tipoPago']);
    }

    public function movimientos(Request $request)
    {
        $fechaInicio = $request['fechaInicio'];
        $fechaFin = $request['fechaFin'];

        return CajaService::movimientos($fechaInicio, $fechaFin);
    }
}
