<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 24/11/18
 * Time: 19:53
 */

namespace App\services;


use App\Producto;
use App\RegistroStock;

class ProductosService
{
    public function crear($elem)
    {
        $prod = Producto::create($elem);
        return $prod->id;
    }

    public function update($elem, $id)
    {
        $prod = Producto::find($id);
        $prod->fill($elem);
        $prod->save();
    }

    public function all()
    {
        return Producto::all();
    }

    public function find($id)
    {
        return Producto::find($id);
    }

    public function delete($id)
    {
        Producto::destroy($id);
    }

    public function comprar($elem, $idUsuario)
    {
        $observacion = $elem['observacion'];
        $tipoPago = $elem['tipoPago'];
        foreach($elem['productos'] as $producto)
        {
            $prod = Producto::find($producto['id']);
            $prod->comprar($producto['cantidad'], $observacion, $tipoPago, $idUsuario);
        }
    }

    public function vender($elem, $idUsuario)
    {
        $observacion = $elem['observacion'];
        $tipoPago = $elem['tipoPago'];

        foreach($elem['productos'] as $producto)
        {
            $prod = Producto::find($producto['id']);
            $prod->vender($producto['cantidad'], $observacion, $tipoPago, $idUsuario);
        }
    }

    public function registrosDeStock($elem)
    {
        $fechaInicio = $elem['fechaInicio'];
        $fechaFin = $elem['fechaFin'];

        return RegistroStock::whereBetween('fecha', [$fechaInicio, $fechaFin])->with('producto', 'usuario', 'socio')->get();
    }
}