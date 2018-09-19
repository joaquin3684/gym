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
            $ar[$dia['id']] = ['desde' => $dia['desde'], 'hasta' => $dia['hasta']];
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
            $ar[$dia['id']] = ['desde' => $dia['desde'], 'hasta' => $dia['hasta']];
        }
        $servicio->dias()->sync($ar);
    }

    public function delete($id)
    {
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
}