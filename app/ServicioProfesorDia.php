<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServicioProfesorDia extends Model
{
    protected $table = 'servicio_profesor_dia';
    protected $fillable = ['id_servicio', 'id_profesor', 'id_dia', 'desde', 'hasta', 'entrada_desde', 'entrada_hasta'];

    public function servicio()
    {
        return $this->belongsTo('App\Servicio', 'id_servicio', 'id');
    }

    public function profesor()
    {

    }

    public function dia()
    {

    }
}
