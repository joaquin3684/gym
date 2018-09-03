<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Articulo extends Vendible
{
    protected $table = 'vendibles';

    public function __construct(array $attributes = [])
    {
        $attributes['tipo'] = 'articulo';
        parent::__construct($attributes);
    }


    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(function ($query) {
            $query->where('tipo', 'articulo');
        });
    }

    public function create(array $attributes = [])
    {
        $attributes['tipo'] = 'articulo';
        parent::create($attributes);
    }
}
