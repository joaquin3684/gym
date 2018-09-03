<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Suscripcion extends Venta
{

    protected $table = 'ventas';

    public function __construct(array $attributes = [])
    {
        $attributes['tipo'] = 'suscripcion';
        parent::__construct($attributes);
    }


    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(function ($query) {
            $query->where('tipo', 'suscripcion');
        });
    }

    public function create(array $attributes = [])
    {
        $attributes['tipo'] = 'suscripcion';
        parent::create($attributes);
    }
}
