<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Servicio extends Model
{
    use SoftDeletes;
    protected $table = 'servicios';
    protected $fillable = ['nombre', 'creditos_minimos'];

    public function dias()
    {
        return $this->belongsToMany('App\Dia', 'servicio_dia', 'id_servicio', 'id_dia')->withPivot('desde', 'hasta', 'entrada_desde', 'entrada_hasta');
    }



    public function registrarEntrada(Socio $socio)
    {
        if($this->creditos != null)
        {
            $this->creditos--;
            $this->save();
        }

        Accesos::create(['id_socio' => $socio->id, 'id_servicio' => $this->id]);


    }

    public function suscribir($socio)
    {

    }
}
