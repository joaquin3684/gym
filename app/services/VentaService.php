<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 31/08/18
 * Time: 22:01
 */
namespace App\services;

class VentaService
{
    public function ventas($fechaInicio, $fechaFin)
    {
        return \App\Venta::with('socio', 'vendible')->whereBetween('fecha', [$fechaInicio, $fechaFin])->get();
    }
}