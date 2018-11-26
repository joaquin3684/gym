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
    public static function ingreso($monto, $concepto, $observacion, $tipoPago, $idUsuario)
    {
        \App\Movimiento::create(['ingreso' => $monto, 'concepto' => $concepto, 'observacion' => $observacion, 'tipo_pago' => $tipoPago, 'fecha' => Carbon::today()->toDateString(), 'id_usuario' => $idUsuario]);
    }

    public static function egreso($monto, $concepto, $observacion, $tipoPago, $idUsuario)
    {
        \App\Movimiento::create(['egreso' => $monto, 'concepto' => $concepto, 'observacion' => $observacion, 'tipo_pago' => $tipoPago, 'fecha' => Carbon::today()->toDateString(), 'id_usuario' => $idUsuario]);
    }

    public static function movimientos($fechaInicio, $fechaFin)
    {
        return \App\Movimiento::with('usuario')->whereBetween('fecha', [$fechaInicio, $fechaFin])->get();
    }
}