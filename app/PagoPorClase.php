<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 02/11/18
 * Time: 23:18
 */

namespace App;


class PagoPorClase extends TipoPago
{

    private $precio;
    private $clase;
    public function __construct($precio, Clase $clase)
    {
        $this->precio = $precio;
        $this->clase = $clase;
    }

    public function pagar()
    {
        return $this->precio;
    }

}