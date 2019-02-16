<?php

namespace App;

use App\Enums\RegistroEntrada;
use App\services\CajaService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Socio extends Model
{
    use SoftDeletes;
    protected $table = 'socios';
    protected $fillable = ['nombre', 'apellido', 'celular', 'domicilio', 'dni', 'fecha_nacimiento', 'id_descuento', 'genero', 'email', 'nro_socio'];
    protected $dates = ['deleted_at'];

    public function descuento()
    {
        return $this->belongsTo('App\Descuento', 'id_descuento','id');
    }

    public function ventas()
    {
        return $this->hasMany('App\Venta', 'id_socio', 'id');
    }

    public function membresiasVigentes()
    {
        return $this->belongsToMany('App\Membresia', 'socio_membresia', 'id_socio', 'id_membresia')->where('vto', '>=', Carbon::today()->toDateString())->withPivot('vto');

    }


    public function membresias()
    {
        return $this->belongsToMany('App\Membresia', 'socio_membresia', 'id_socio', 'id_membresia')->withPivot('vto');
    }

    public function serviciosVigentes()
    {
        return $this->belongsToMany('App\Servicio', 'socio_servicio', 'id_socio', 'id_servicio')->where('vto', '>=', Carbon::today()->toDateString())->withPivot('creditos', 'vto');

    }

    public function clases()
    {
        return $this->belongsToMany('App\Clase', 'clases_socios', 'id_socio', 'id_clase');
    }

    public function servicios()
    {
        return $this->belongsToMany('App\Servicio', 'socio_servicio', 'id_socio', 'id_servicio')->withPivot('creditos', 'vto', 'id');
    }

    public function cuotasPendientes()
    {
        return $this->hasManyThrough('App\Cuota', 'App\Socio', 'id_socio', 'id_venta', 'id', 'id')->where('pagada', 0);
    }


    public function accesos()
    {
        return $this->hasMany('App\Accesos','id_socio', 'id');
    }
    
    public function acceder()
    {
        //esto quiere decir que no tiene ningun servicio vigente
        if($this->servicios->isEmpty())
        {
            return RegistroEntrada::ENTRADA_RECHAZADA;
        }
        //esto quiere decir que tiene una membresia con la cuota vencida
        else if($this->ventas->every(function($membresia){
            return $membresia->cuotas->isNotEmpty();
        })){
            return RegistroEntrada::ENTRADA_RECHAZADA;
        }
        else
        {
            $i = 0;
            $sms = $this->ventas->filter(function($membresia){
                return $membresia->cuotas->isEmpty();
            });

            $membresias = $this->membresias->filter(function($membresia) use($sms){
               return $sms->contains(function($sm) use ($membresia){
                    return $sm->id_membresia = $membresia->id;
               });
            });

            $servicios = $this->servicios->filter(function($servicio) use ($membresias){
                return $membresias->contains(function($membresia) use ($servicio){
                    return $membresia->servicios->contains(function($servi) use ($servicio){
                        return $servi->id == $servicio->id;
                    });
                });
            })->unique(function($servicio){ return $servicio->id;});

            if($servicios->sum(function($serv){ return $serv->clases->count();}) > 1)
            {
                 return ['id' => $this->id, 'nombre' => $this->nombre, 'apellido' => $this->apellido, 'servicios' => $servicios->values()->all()];
            } else {
                $this->servicios->first()->clases->first()->registrarEntrada($this);
                return RegistroEntrada::ENTRADA_REGISTRADA;
            }

        }


    }


}
