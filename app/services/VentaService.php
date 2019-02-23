<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 31/08/18
 * Time: 22:01
 */
namespace App\services;

use App\Descuento;
use App\Membresia;
use App\Socio;
use App\User;
use App\Venta;
use Carbon\Carbon;

class VentaService
{

    public function __construct()
    {
        $this->cuotasSrv = new CuotaService();
    }

    public function ventas($fechaInicio, $fechaFin)
    {
        return Venta::with('socio', 'membresia', 'descuentoMembresia', 'descuentoSocio')->whereBetween('fecha', [$fechaInicio, $fechaFin])->get();
    }

    public function historialCompra($idSocio)
    {
        return Venta::with('socio', 'membresia', 'descuentoMembresia', 'descuentoSocio')->where('id_socio', $idSocio)->get();
    }

    public function delete(Venta $venta){
        $venta->servicios()->detach();
        $this->cuotasSrv->borrarCuotasDeVenta($venta);
        $venta->delete();
    }

    public function crear(Socio $socio, $tipoPago, $observacion, User $usuario, Membresia $membresia, $cantidad, Descuento $descuento = null)
    {

            $descuentos = $this->buscarDescuentos($socio, $membresia, $descuento);
            $precio = $this->precioLuegoDeDescuentos($descuentos, $membresia);
            $descuentoSocio = $descuentos->first(function($descuento){ return $descuento->tipo == 2;});
            $vto = $this->generarVencimiento($socio, $membresia);
            $venta = Venta::create([
                                    'fecha' => Carbon::today()->toDateString(),
                                    'precio' => $precio,
                                    'id_membresia' => $membresia->id,
                                    'id_socio' => $socio->id,
                                    'cantidad' => $cantidad,
                                    'id_descuento_membresia' => is_null($descuento) ? null : $descuento->id,
                                    'id_descuento_socio' => is_null($descuentoSocio)? null : $socio->descuento->id,
                                    'vto' => $vto
            ]);

            $this->cuotasSrv->crearHastaFecha($venta, $vto, $membresia->nro_cuotas);

            $this->adjuntarServicios($socio, $membresia, $venta);
            $concepto = $membresia->nro_cuotas == 1 ? $membresia->nombre : 'Cuota 1 '. $membresia->nombre;
            CajaService::ingreso($precio/$membresia->nro_cuotas, $concepto, $observacion, $tipoPago, $usuario->id);
            return $venta;
    }

    public function generarVencimiento(Socio $socio, Membresia $membresia)
    {
        $venta = $socio->ventas->filter(function($venta) use ($membresia){
            return $venta->id_membresia == $membresia->id;
        })->sortByDesc(function($venta){
            return $venta->vto;
        })->first();
        $hoy = Carbon::today();
        if($venta == null)
        {
            return $hoy->addDays($membresia->vencimiento_dias)->toDateString();
        }
        else
        {
            if($venta->vto > $hoy->toDateString())
                return Carbon::createFromFormat('Y-m-d', $venta->vto)->addDays($membresia->vencimiento_dias)->toDateString();
            else
                return $hoy->addDays($membresia->vencimiento_dias)->toDateString();
        }


    }

    public function precioLuegoDeDescuentos($descuentos, $membresia)
    {


        $monto = $membresia->precio;
        $algunDescuentoEnConjunto = $descuentos->contains(function($descuento){ return $descuento->esAplicableEnConjunto(); });
        if($algunDescuentoEnConjunto)
        {
            $descuentos->each(function(Descuento $descuento) use (&$monto){
                $descuentoAplicado = $descuento->aplicar($monto);
                $monto = $monto - $descuentoAplicado;
            });
        }
        else
        {
            $totalDescuentos = $descuentos->sum(function($descuento) use ($monto){
                return $descuento->aplicar($monto);
            });
            $monto = $monto - $totalDescuentos;
        }

        return $monto;
    }

    public function adjuntarServicios(Socio $socio, Membresia $membresia, Venta $venta)
    {
        $ids = array();
        $vtoMembresia = $this->generarVencimiento($socio, $membresia);
        foreach($membresia->servicios as $servicio)
        {
            $ids[$servicio->id] = ['creditos' => $servicio->pivot->creditos, 'vto' => Carbon::createFromFormat('Y-m-d',$vtoMembresia)->subDays($membresia->vencimiento_dias)->addDays($servicio->pivot->vto)->toDateString()];
        }

        $venta->servicios()->attach($ids);
    }

    public function buscarDescuentos(Socio $socio,  Membresia $membresia, Descuento $descuento = null)
    {

        $descuentos = collect();
        if(!is_null($descuento))
            $descuentos->push($descuento);
        if(!is_null($socio->descuento))
        {
            $descuentos->push($socio->descuento);
            // aca me fijo si la membresia que quiero comprar pertenece al descuento de socio
            $compartenDescuento = $socio->descuento->membresias->contains(function($mem) use ($membresia){ return $mem->id == $membresia->id;});

            if($compartenDescuento)
                return $descuentos;
            else
                return is_null($descuento) ? collect() : collect()->push($descuento);

        }

        return $descuentos;

    }

    public function realizarCompra(Socio $socio, $tipoPago, $observacion, User $usuario, Membresia $membresia, $cantidad, Descuento $descuento = null)
    {
        $ventaConCuotasSinPagar = $socio->ventas->first(function ($venta) use($membresia){
            return $venta->id_membresia == $membresia->id && $venta->cuotas->contains(function($cuota){
                    return $cuota->pagada == false;
                });
        });

        if($ventaConCuotasSinPagar != null)
            return $this->pagarCuota($ventaConCuotasSinPagar, $tipoPago, $usuario, $observacion, $membresia);
         else
            return $this->crear($socio, $tipoPago, $observacion, $usuario, $membresia, $cantidad, $descuento);

    }

    public function pagarCuota(Venta $venta, $tipoPago, $usuario, $observacion, Membresia $membresia)
    {
        $cuota = $venta->cuotas->sortBy(function ($cuota){
            return $cuota->id;
        })->first(function($cuota){
            return $cuota->pagada == false;
        });

        $this->cuotasSrv->pagarCuota($cuota);

        CajaService::ingreso($cuota->pago, 'Cuota '.$cuota->nro_cuota.' '.$membresia->nombre, $observacion, $tipoPago, $usuario->id);
        return $venta;
    }
}