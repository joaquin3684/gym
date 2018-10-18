<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dia extends Model
{
    protected $table = 'dias';
    protected $fillable = ['nombre'];

    public function servicios()
    {
        return $this->belongsToMany('App\Servicio', 'servicio_dia', 'id_dia', 'id_servicio')->withPivot('desde', 'hasta', 'entrada_desde', 'entrada_hasta');
    }

}
