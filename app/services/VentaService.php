<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 31/08/18
 * Time: 22:01
 */
namespace App\services;

use App\Venta;

class VentaService
{
    public function ventas($fechaInicio, $fechaFin)
    {
        return Venta::with('socio', 'membresia', 'descuentoMembresia', 'descuentoSocio')->whereBetween('fecha', [$fechaInicio, $fechaFin])->get();
    }

    public function historialCompra($idSocio)
    {
        return Venta::with('socio', 'membresia', 'descuentoMembresia', 'descuentoSocio')->where('id_socio', $idSocio)->get();
    }
}