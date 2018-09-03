<?php
namespace App\services;

use App\Socio;
use App\Vendible;
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
        return Socio::find($id);
    }

    public function all()
    {
        return Socio::all();
    }

    public function comprar($elem)
    {
        $idVendible = $elem['idVendible'];
        $idSocio = $elem['idSocio'];
        $cantidad = $elem['cantidad'];
        $tipoPago = $elem['tipoPago'];


        $vendible = Vendible::find($idVendible);
        $socio = Socio::find($idSocio);
        $socio->comprar($vendible, $cantidad, $tipoPago);

    }
}