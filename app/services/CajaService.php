<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 31/08/18
 * Time: 21:31
 */
namespace App\services;

use Carbon\Carbon;

class CajaService
{
    public static function ingreso($monto, $concepto, $observacion, $tipoPago)
    {
        \App\Movimiento::create(['ingreso' => $monto, 'concepto' => $concepto, 'observacion' => $observacion, 'tipo_pago' => $tipoPago, 'fecha' => Carbon::today()->toDateString()]);
    }

    public static function egreso($monto, $concepto, $observacion, $tipoPago)
    {
        \App\Movimiento::create(['egreso' => $monto, 'concepto' => $concepto, 'observacion' => $observacion, 'tipo_pago' => $tipoPago, 'fecha' => Carbon::today()->toDateString()]);
    }

    public static function movimientos($fechaInicio, $fechaFin)
    {
        return \App\Movimiento::whereBetween('fecha', [$fechaInicio, $fechaFin])->get();
    }
}