<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Clase extends Model
{
    //estado: 1 = activa, 2 = suspendida, 3 = cambio de profesor

    use SoftDeletes;
    protected $table = 'clases';
    protected $fillable = ['fecha', 'dia', 'id_servicio', 'estado', 'desde', 'hasta', 'entrada_desde', 'entrada_hasta'];

    public function alumnos()
    {
        return $this->belongsToMany('App\Socio', 'clases_socios', 'id_clase', 'id_socio');
    }

    public function profesores()
    {
        return $this->belongsToMany('App\Profesor', 'clase_profesor', 'id_clase', 'id_profesor');
    }

}
