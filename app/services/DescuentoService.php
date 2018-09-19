<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 18/09/18
 * Time: 10:16
 */

namespace App\services;


use App\Descuento;

class DescuentoService
{
    public function crear($elem)
    {
        Descuento::create($elem);
    }

    public function update($elem, $id)
    {
        $descuento = Descuento::find($id);
        $descuento->fill($elem);
        $descuento->save();
    }

    public function delete($id)
    {
        Descuento::destroy($id);
    }

    public function find($id)
    {
        return Descuento::find($id);
    }

    public function descuentos()
    {
        return Descuento::all();
    }
}