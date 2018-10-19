<?php
namespace App\services;

use App\Accesos;
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
        $observacion = $elem['observacion'];
        $socio = Socio::with('descuento')->find($idSocio);
        foreach ($elem['membresias'] as $membresia)
        {
            $idMembresia = $membresia['id'];
            $idDescuento = $membresia['idDescuento'];
            $cantidad = $membresia['cantidad'];
            $membresia = Membresia::find($idMembresia);
            $descuento = is_null($idDescuento) ? null : Descuento::find($idDescuento);
            $membresia->vender($socio, $cantidad, $tipoPago, $descuento, $observacion);
        }
    }

    public function acceder($elem)
    {
        $hoy = Carbon::today()->toDateString();
        $dia = Carbon::today()->dayOfWeekIso;
        $hora = Carbon::now('America/Argentina/Buenos_Aires')->toTimeString();


        /**
         * aca traigo el socio con las membresias que tengan cuotas vencidas con sus respectivas cuotas
         * y con los servicios que tenga vigentes dentro del horario actual
         */

        $idSocio = $elem['idSocio'];
        $socio = Socio::with(['membresias' => function($query) use ($hoy, $dia, $hora, $idSocio){
            $query->where('vto', '>=', $hoy)
                ->with(['cuotas' => function($q) use ($hoy, $idSocio){
                    $q->where('pagada', false)
                        ->where('id_socio', $idSocio)
                    ->where('fecha_inicio', '<=', $hoy)
                        ->where('fecha_vto', '>', $hoy);
                }])
            ->with('servicios');
        }, 'servicios' => function($query) use ($hoy, $dia, $hora){
            $query->where('vto', '>=', $hoy)
                ->where(function($q){
                    $q->where('creditos', '>', 0)
                        ->orWhere('creditos', null);
                })
                ->whereHas('dias', function($q) use ($dia, $hora) {
                    $q->where('id', $dia)
                        ->where('entrada_desde', '<=', $hora)
                        ->where('entrada_hasta', '>=', $hora);
                });
        }])->find($idSocio);


        return $socio->acceder($elem['automatico']);
    }


    public function accesos($idSocio)
    {
        return Accesos::with('servicio')->where('id_socio', $idSocio)->orderBy('created_at', 'desc')->limit(10)->get();
    }


}