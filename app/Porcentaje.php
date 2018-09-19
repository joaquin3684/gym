<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Porcentaje extends Descuento
{
    public function __construct(array $attributes = [])
    {
        $attributes['tipo'] = 'porcentaje';
        parent::__construct($attributes);
    }


    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(function ($query) {
            $query->where('tipo', 'porcentaje');
        });
    }

    public function create(array $attributes = [])
    {
        $attributes['tipo'] = 'porcentaje';
        parent::create($attributes);
    }

    public function aplicar($monto)
    {
        return $monto - $monto * $this->porcentaje / 100;
    }
}
