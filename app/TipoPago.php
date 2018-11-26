<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoPago
{

    public static function crearTipo($tipo, $precio, $clase)
    {
        switch ($tipo){
            case 'hora':
                return new PagoPorHora($precio, $clase);
                break;
            case 'clase':
                return new PagoPorClase($precio, $clase);
                break;
            default:
                throw new \RuntimeException("no existe el tipo de pago");
                break;
        }
    }
}
