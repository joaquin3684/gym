<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 17/09/18
 * Time: 11:10
 */

namespace App\services;


use App\Servicio;
use App\Socio;

class ServicioService
{
    public function crear($elem)
    {
        $servicio = new Servicio($elem);
        $servicio->save();
        $dias = $elem['dias'];
        $ar = array();
        foreach($dias as $dia)
        {
            $ar[$dia['id']] = ['desde' => $dia['desde'], 'hasta' => $dia['hasta'], 'entrada_desde' => $dia['entrada_desde'], 'entrada_hasta' => $dia['entrada_hasta']];
        }
        $servicio->dias()->attach($ar);
    }

    public function update($elem, $id)
    {
        $servicio = Servicio::find($id);
        $servicio->fill($elem);
        $servicio->save();
        $dias = $elem['dias'];
        $ar = array();
        foreach($dias as $dia)
        {
            $ar[$dia['id']] = ['desde' => $dia['desde'], 'hasta' => $dia['hasta'], 'entrada_desde' => $dia['entrada_desde'], 'entrada_hasta' => $dia['entrada_hasta']];
        }
        $servicio->dias()->sync($ar);
    }

    public function delete($id)
    {
        $servicio = Servicio::find($id);
        $servicio->dias()->detach();
        Servicio::destroy($id);

    }

    public function find($id)
    {
        return Servicio::with('dias')->find($id);
    }

    public function servicios()
    {
        return Servicio::all();
    }

    public function devolverEntradas($elem)
    {
        foreach($elem['servicios'] as $servicio)
        {
            $serv = Servicio::find($servicio);

            foreach($elem['socios'] as $socio)
            {
                $soc = Socio::with(['servicios' => function($q) use ($servicio){
                    $q->where('id', $servicio);
                }])->find($socio);
                $serv->devolverEntrada($soc);
            }
        }
    }

    public function registrarEntradas($elem)
    {
        foreach($elem['servicios'] as $servicio)
        {
            $serv = Servicio::find($servicio);

            foreach($elem['socios'] as $socio)
            {
                $soc = Socio::with(['servicios' => function($q) use ($servicio){
                    $q->where('id', $servicio);
                }])->find($socio);
                $serv->registrarEntrada($soc);
            }
        }

    }
}