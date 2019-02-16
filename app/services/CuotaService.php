<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 14/02/19
 * Time: 02:50
 */

namespace App\services;


use App\Cuota;
use App\Venta;
use Carbon\Carbon;

class CuotaService
{

    public function crearHastaFecha(Venta $venta, $vencimiento, $nroCuotas)
    {
        $ultimoVencimiento = Carbon::createFromFormat('Y-m-d', $vencimiento);
        $fVto = Carbon::today()->addDays(30);
        $fInicio = Carbon::today();
        if($nroCuotas == 1)
        {
            $this->crear($venta, 1, $fVto->toDateString(), $fInicio->toDateString(), true, $venta->precio/$nroCuotas);

        } else {

            for ($i = 1; $i <= $nroCuotas; $i++) {
                if ($i == $nroCuotas)
                    $this->crear($venta, $i, $ultimoVencimiento->toDateString(), $fInicio->toDateString(), false, $venta->precio/$nroCuotas);
                else {
                    if ($i == 1)
                        $this->crear($venta, $i, $fVto->toDateString(), $fInicio->toDateString(), true, $venta->precio/$nroCuotas);
                    else
                        $this->crear($venta, $i, $fVto->toDateString(), $fInicio->toDateString(), false, $venta->precio/$nroCuotas);
                }

                $fVto->addDays(30);
                $fInicio->addDays(30);
            }
        }
    }

    public function crear(Venta $venta, $nroCuota, $vto, $inicio, $estado, $precio)
    {
        return Cuota::create([
            'id_venta' => $venta->id,
            'nro_cuota' => $nroCuota,
            'fecha_vto' => $vto,
            'fecha_inicio' =>  $inicio,
            'pagada' => $estado,
            'pago' => $precio
        ]);
    }

    public function pagarCuota(Cuota $cuota)
    {
        $cuota->pagada = 1;
        $cuota->save();
    }
}