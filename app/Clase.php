<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Clase extends Model
{
    //estado: 1 = activa, 2 = suspendida, 3 = cambio de profesor

    use SoftDeletes;
    protected $table = 'clases';
    protected $fillable = ['fecha', 'dia', 'id_servicio', 'estado', 'desde', 'hasta', 'entrada_desde', 'entrada_hasta', 'id_dia'];

    public function alumnos()
    {
        return $this->belongsToMany('App\Socio', 'clases_socios', 'id_clase', 'id_socio');
    }

    public function profesores()
    {
        return $this->belongsToMany('App\Profesor', 'clase_profesor', 'id_clase', 'id_profesor');
    }

    public function servicio()
    {
        return $this->belongsTo('App\Servicio', 'id_servicio', 'id');
    }

    public function tipoDePago()
    {
        return TipoPago::crearTipo($this->pivot->tipo_pago, $this->pivot->precio, $this);
    }

    public function pagar()
    {
        $this->pivot->pagada = true;
        $this->save();
        $tipo = $this->tipoDePago();
        return $tipo->pagar();
    }

    public function registrarEntrada(Socio $socio)
    {
        $servicio = $socio->servicios->first(function($serv){ return $serv->id = $this->id_servicio;});
        if ($servicio->pivot->creditos != null) {
            $creditos = --$servicio->pivot->creditos;
            $servicio->pivot->save();
   //         $socio->servicios()->updateExistingPivot($this->id_servicio, ['creditos' => $creditos]);
        }
        $this->alumnos()->attach($socio->id);

    }

    public function devolverEntrada(Socio $socio)
    {
        $servicio = $socio->servicios->first(function($serv){ return $serv->id = $this->id_servicio;});

        if($servicio->pivot->creditos != null)
        {
            $creditos = $servicio->pivot->creditos++;
            $servicio->pivot->save();
     //       $socio->servicios()->updateExistingPivot($this->id, ['creditos' => $creditos]);
        }

        $this->alumnos()->detach($socio->id);
    }
}
