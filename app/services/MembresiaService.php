<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 31/08/18
 * Time: 21:24
 */
namespace App\services;

use App\Membresia;

class MembresiaService
{
    public function membresias()
    {
        return Membresia::all();
    }

    public function crear($elem)
    {
        $membresia = Membresia::create($elem);
        $servicios = array();
        foreach($elem['servicios'] as $servicio)
        {
            $servicios[$servicio['id']] = ['creditos' => $servicio['cantidadCreditos'], 'vto' => $servicio['vto']];
        }
        $membresia->servicios()->attach($servicios);
        $membresia->descuentos()->attach($elem['descuentos']);
    }

    public function update($elem, $id)
    {
        $membresia = Membresia::find($id);
        $membresia->fill($elem);
        $membresia->save();
        $servicios = array();
        foreach($elem['servicios'] as $servicio)
        {
            $servicios[$servicio['id']] = ['creditos' => $servicio['cantidadCreditos']];
        }

        $membresia->servicios()->sync($servicios);
        $membresia->descuentos()->sync($elem['descuentos']);
    }

    public function find($id)
    {
        return Membresia::with('servicios', 'descuentos')->find($id);
    }

    public function delete($id)
    {
        Membresia::destroy($id);
    }

    public function membresiasConTodo()
    {
        return Membresia::with('descuentos', 'servicios')->get();
    }

}