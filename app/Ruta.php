<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ruta extends Model
{
    protected $table = 'rutas';

    public $timestamps = false;

    protected $fillable = [
        'ruta'
    ];

    public function pantallas()
    {
        return $this->belongsToMany('App\Pantalla', 'pantalla_ruta', 'id_pantalla', 'id_ruta');
    }
}
