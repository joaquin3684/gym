<?php
namespace App\services;

use App\Descuento;
use App\Membresia;
use App\Socio;
use App\Vendible;
use Carbon\Carbon;

/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 31/08/18
 * Time: 13:43
 */


class SocioService
{
    public function crear($elem)
    {
        $socio = new Socio($elem);
        $socio->save();
    }

    public function update($elem, $id)
    {
        $socio = Socio::find($id);
        $socio->fill($elem);
        $socio->save();
    }

    public function find($id)
    {
        return Socio::with('descuento')->find($id);
    }

    public function all()
    {
        return Socio::all();
    }

    public function comprar($elem)
    {
        $idSocio = $elem['idSocio'];
        $tipoPago = $elem['tipoPago'];
        $socio = Socio::with('descuento')->find($idSocio);
        foreach ($elem['membresias'] as $membresia)
        {
            $idMembresia = $membresia['id'];
            $idDescuento = $membresia['idDescuento'];
            $membresia = Membresia::find($idMembresia);
            $descuento = is_null($idDescuento) ? null : Descuento::find($idDescuento);
            $cantidad = $membresia['cantidad'];
            $membresia->vender($socio, $cantidad, $tipoPago, $descuento);
        }
    }

    public function acceder($elem)
    {
        $hoy = Carbon::today()->toDateString();
        $dia = Carbon::today()->dayOfWeekIso;
        $hora = Carbon::today()->toTimeString();


        $socio = Socio::with(['membresias' => function($query) use ($hoy, $dia, $hora){
            $query->where('vto', '<=', $hoy)
                ->with(['cuotas' => function($q) use ($hoy){
                    $q->where('pagada', false)
                    ->where('fecha_inicio', '<=', $hoy)
                        ->where('fecha_vto', '>', $hoy);
                }])
            ->with('servicios');
        }, 'servicios' => function($query) use ($hoy, $dia, $hora){
            $query->where('vto', '<=', $hoy)
                ->where(function($q){
                    $q->where('creditos', '>', 0)
                        ->orWhere('creditos', null);
                })
                ->whereHas('dias', function($q) use ($dia, $hora) {
                    $q->where('numero', $dia)
                        ->where('desde', '<', $hora)
                        ->where('hasta', '>', $hora);
                });
        }])->find($elem['idSocio']);


        return $socio->acceder($elem['automatico']);
    }

    public function registrarEntradaAServicio($elem)
    {
        $socio = Socio::with(['servicios' => function($q) use ($elem){
            $q->where('id', $elem['idServicio']);
        }])->find($elem['idSocio']);
        $servicio = $socio->servicios()->first();
        $socio->registrarEntrada($servicio);
    }
}