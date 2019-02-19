<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 14/02/19
 * Time: 02:50
 */

namespace App\services;


use App\Cuota;
use App\User;
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
        $this->cambiarEstadoPagada($cuota, 1);
    }

    public function cancelarPago(Cuota $cuota, User $usuario)
    {
        $this->cambiarEstadoPagada($cuota, 0);
        CajaService::egreso($cuota->pago, 'Cuota '.$cuota->nro_cuota.' '.$cuota->venta->membresia->nombre, 'Cuota mal cobrada', 'Efectivo', $usuario->id);
    }

    public function borrarCuotasDeVenta(Venta $venta)
    {
        $cuotas = Cuota::where('id_venta', $venta->id)->get();
        $cuotas->each(function(Cuota $cuota){ $this->delete($cuota);});
    }

    public function delete(Cuota $cuota)
    {
        $cuota->delete();
    }
    protected $fillable = ['pago', 'pagada', 'fecha_inicio', 'fecha_vto', 'id_venta', 'nro_cuota'];

    public function update(Cuota $cuota, $pago, $pagada, $fechaInicio, $fechaVto, $idVenta, $nroCuota)
    {
        $cuota->pago = $pago;
        $cuota->pagada = $pagada;
        $cuota->fecha_inicio = $fechaInicio;
        $cuota->fecha_vto = $fechaVto;
        $cuota->id_venta = $idVenta;
        $cuota->nro_cuota = $nroCuota;

        $cuota->save();
    }

    public function cambiarEstadoPagada(Cuota $cuota, $estado)
    {
        $this->update($cuota, $cuota->pago, $estado, $cuota->fecha_inicio, $cuota->fecha_vto, $cuota->id_venta, $cuota->nro_cuota);

    }

}