<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 02/11/18
 * Time: 23:18
 */

namespace App;


use Carbon\Carbon;

class PagoPorHora extends TipoPago
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
        $carbon = new Carbon($this->clase->desde);

        return round($carbon->diffInMinutes($this->clase->hasta)/60, 2) * $this->precio;
    }
}