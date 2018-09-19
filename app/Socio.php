<?php

namespace App;

use App\services\CajaService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Socio extends Model
{
    use SoftDeletes;
    protected $table = 'socios';
    protected $fillable = ['nombre', 'apellido', 'celular', 'domicilio', 'dni', 'fecha_nacimiento', 'id_descuento'];
    protected $dates = ['deleted_at'];

    public function descuento()
    {
        return $this->belongsTo('App\Descuento', 'id_descuento','id');
    }

    public function ventas()
    {
        return $this->hasMany('App\Venta', 'id_socio', 'id');
    }

    public function membresias()
    {
        return $this->belongsToMany('App\Membresia', 'socio_membresia', 'id_membresia', 'id_socio')->withPivot('vto');
    }

    public function servicios()
    {
        return $this->belongsToMany('App\Servicio', 'socio_servicio', 'id_servicio', 'id_socio')->withPivot('creditos');
    }


    public function acceder($automatico)
    {
        if($this->servicios() == null)
        {
            return "no puede entrar";
        }
        if($this->membresias()->contains(function($membresia){
            return $membresia->cuotas() != null;
        }) && $this->membresias()->count() == 1){
            return "no puede entrar";
        }
        if($this->membresias()->contains(function($membresia){
            return $membresia->cuotas() != null;
        }) && $this->membresias()->count() > 1)
        {
            $membresias = $this->membresias()->filter(function($membresia){
                return $membresia->cuotas() == null;
            });
            $servicios = $this->servicios()->filter(function($servicio) use ($membresias){
                return $membresias->contains(function($membresia) use ($servicio){
                    return $membresia->servicios->contains(function($servi) use ($servicio){
                        return $servi->id == $servicio->id;
                    });
                });
            })->unique(function($servicio){ return $servicio->id;});

            return $servicios;
        }
        if($this->servicios()->count() > 1)
        {
            return $this->servicios();
        }
        if($automatico && $this->servicios()->count() == 1)
        {
            $this->servicios()->first()->registrarEntrada($this);
            return "registrada";
        }

    }

    public function registrarEntrada(Servicio $servicio)
    {
        $servicio->registrarEntrada($this);
    }


}
