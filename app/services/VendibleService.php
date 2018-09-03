<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 31/08/18
 * Time: 21:24
 */
namespace App\services;

class VendibleService
{
    public function clases()
    {
        return \App\Clase::all();
    }

    public function articulos()
    {
        return \App\Articulo::all();
    }
}