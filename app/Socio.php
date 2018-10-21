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
    protected $fillable = ['nombre', 'apellido', 'celular', 'domicilio', 'dni', 'fecha_nacimiento', 'id_descuento', 'genero', 'email'];
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
        return $this->belongsToMany('App\Membresia', 'socio_membresia', 'id_socio', 'id_membresia')->withPivot('vto');
    }

    public function servicios()
    {
        return $this->belongsToMany('App\Servicio', 'socio_servicio', 'id_socio', 'id_servicio')->withPivot('creditos', 'vto');
    }


    public function cuotasPendientes()
    {
        return $this->hasMany('App\Cuota', 'id_socio', 'id')->where('pagada', 0);
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
        else if($this->membresias->every(function($membresia){
            return $membresia->cuotas->isNotEmpty();
        })){
            return RegistroEntrada::ENTRADA_RECHAZADA;
        }
        else
        {
            $i = 0;
            $membresias = $this->membresias->filter(function($membresia){
                return $membresia->cuotas->isEmpty();
            });
            $servicios = $this->servicios->filter(function($servicio) use ($membresias){
                return $membresias->contains(function($membresia) use ($servicio){
                    return $membresia->servicios->contains(function($servi) use ($servicio){
                        return $servi->id == $servicio->id;
                    });
                });
            })->unique(function($servicio){ return $servicio->id;});
            if($servicios->count() > 1)
            {
                 return ['id' => $this->id, 'nombre' => $this->nombre, 'apellido' => $this->apellido, 'servicios' => $servicios->values()->all()];
            } else {
                $this->servicios->first()->registrarEntrada($this);
                return RegistroEntrada::ENTRADA_REGISTRADA;
            }

        }


    }


}
