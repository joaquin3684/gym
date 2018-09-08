<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{

    protected $table = 'perfiles';

    public $timestamps = false;

    protected $fillable = [
        'nombre'
    ];

    public function pantallas()
    {
        return $this->belongsToMany('App\Pantalla', 'pantalla_perfil', 'id_perfil', 'id_pantalla');
    }



}
