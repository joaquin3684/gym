<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pantalla extends Model
{
    protected $table = 'pantallas';

    public $timestamps = false;

    protected $fillable = [
        'nombre'
    ];

    public function perfiles()
    {
        return $this->belongsToMany('App\Perfil', 'pantalla_perfil', 'id_perfil', 'id_pantalla');
    }

    public function rutas()
    {
        return $this->belongsToMany('App\Ruta', 'pantalla_ruta', 'id_pantalla', 'id_ruta');
    }
}
