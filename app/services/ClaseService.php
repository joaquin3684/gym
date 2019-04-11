<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 22/10/18
 * Time: 18:29
 */

namespace App\services;


use App\Clase;
use App\Servicio;
use App\Socio;
use Carbon\Carbon;

class ClaseService
{
    public function update($fecha, Servicio $servicio, $desde, $hasta, $entradaDesde, $entradaHasta, $estado, $profesores, Clase $clase)
    {

        $clase->fecha = $fecha;
        $clase->id_servicio = $servicio->id;
        $clase->desde = $desde;
        $clase->hasta = $hasta;
        $clase->entrada_desde = $entradaDesde;
        $clase->entrada_hasta = $entradaHasta;
        $clase->estado = $estado;
        $clase->save();
        $clase->profesores()->sync($profesores);
        return $clase;
    }

    public function crear($fecha, Servicio $servicio, $desde, $hasta, $entradaDesde, $entradaHasta, $estado, $profesores)
    {
        $clase = new Clase();
        $clase->fecha = $fecha;
        $clase->id_servicio = $servicio->id;
        $clase->desde = $desde;
        $clase->hasta = $hasta;
        $clase->entrada_desde = $entradaDesde;
        $clase->entrada_hasta = $entradaHasta;
        $clase->estado = $estado;
        $clase->save();
        $clase->profesores()->attach($profesores);
        return $clase;
    }


    public function all($fechaDesde, $fechaHasta)
    {
        return Clase::with('servicio', 'profesores', 'alumnos')
            ->where('fecha', '>=', $fechaDesde)
            ->where('fecha', '<=', $fechaHasta)
            ->get();
    }

    public function clasesDelDia()
    {
        return Clase::with('profesores', 'servicio', 'alumnos')->where('fecha', Carbon::today()->toDateString())->get();
    }

    public function clasesEnTranscurso()
    {
        $ahora = Carbon::now()->toTimeString();
        return Clase::with('profesores', 'servicio', 'alumnos')->where('entrada_desde', '<=', $ahora)->where('hasta', '>=', $ahora)->where('fecha', Carbon::today()->toDateString())->get();
    }

    public function clasesFuturas()
    {
        return Clase::with('profesores', 'servicio', 'alumnos')->where('fecha', '>', Carbon::today()->toDateString())->get();
    }

    public function registrarEntradas(Clase $clase, $socios)
    {
        $clase->alumnos()->attach($socios->map(function($socio){return $socio->id;})->toArray());
        $ventaSrv = new VentaService();

        $socios->each(function($socio) use ($clase, $ventaSrv){
            $venta = $socio->ventas->filter(function($venta) use ($clase) { return $venta->servicios->contains(function($servicio) use($clase){return $servicio->id == $clase->id_servicio;}); })
                ->sortBy(function($venta){return $venta->id;})
                ->first();

            $ventaSrv->descontarCredito($venta, $clase);

        });
    }

    public function devolverEntradas(Clase $clase, $socios)
    {
        $clase->alumnos()->detach($socios->map(function($socio){return $socio->id;})->toArray());

        $ventaSrv = new VentaService();

        $socios->each(function($socio) use ($clase, $ventaSrv){
            $venta = $socio->ventas->filter(function($venta) use ($clase) { return $venta->servicios->contains(function($servicio) use($clase){return $servicio->id == $clase->id_servicio;}); })
                ->sortBy(function($venta){return $venta->id;})
                ->first();

            $ventaSrv->retornarCredito($venta, $clase);

        });
    }

}