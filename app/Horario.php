<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    protected $table = 'horarios';
    protected $fillable = ['id_servicio', 'dia', 'desde', 'hasta', 'entrada_desde', 'entrada_hasta'];

    public function servicio()
    {
        return $this->belongsTo('App\Servicio', 'id_servicio', 'id');
    }

    public function profesores()
    {
        return $this->belongsToMany('App\Profesor', 'horario_profesor', 'id_horario', 'id_profesor');
    }

}
