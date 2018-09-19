<?php

namespace App;

use App\services\CajaService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Membresia extends Model
{
    use SoftDeletes;

    protected $table = 'membresias';
    protected $fillable = ['precio', 'nombre', 'vencimiento_dias', 'nro_cuotas'];

    public function cuotas()
    {
        return $this->hasMany('App\Cuota', 'id_membresia', 'id');
    }


    public function descuentos()
    {
        return $this->belongsToMany('App\Descuento', 'membresia_descuento', 'id_membresia', 'id_descuento');
    }

    public function servicios()
    {
        return $this->belongsToMany('App\Servicio', 'membresia_servicio','id_membresia', 'id_servicio')->withPivot('creditos');
    }
    
    public function socios()
    {
        return $this->belongsToMany('App\Socio', 'socio_membresia', 'id_socio', 'id_membresia')->withPivot('vto');
    }

    public function vender($socio, $cantidad, $tipoPago, $descuento)
    {
        $descuentos = $this->buscarDescuentos($descuento);

        $precio = $this->aplicarDescuento($descuentos);
        $this->crearCuotas($precio);

        $this->socios()->attach($socio->id, ['vto' => Carbon::today()->addDays($this->vencimiento_dias)->toDateString()]);
        $this->adjuntarServicios($socio);
        $venta = new Venta(['fecha' => Carbon::today()->toDateString(), 'precio' => $precio, 'id_membresia' => $this->id, 'id_socio' => $socio->id, 'cantidad' => $cantidad, 'id_descuento' => $descuento->id]);
        $venta->save();
        CajaService::ingreso($precio, $this->nombre, null, $tipoPago);
    }
    public function aplicarDescuento($descuentos)
    {
        $monto = $this->precio;
        $descuentos->each(function($descuento) use (&$monto){
            $monto = $descuento->aplicar($monto);
        });
        return $monto;
    }

    public function adjuntarServicios(Socio $socio)
    {
        $ids = $this->servicios()->map(function($servicio){
            return [$servicio->id => ['creditos' => $servicio->creditos]];
        });

        $socio->servicios()->attach($ids);
    }

    public function buscarDescuentos(Socio $socio, $descuento)
    {

        $descuentos = collect();
        $descuentos->push($descuento);
        $descuentos->push($socio->descuento());

        $compartenDescuento = $socio->descuento()->membresias()->contains(function($membresia){ return $membresia->id == $this->id;});

        if($compartenDescuento)
        {
            $descuentoNoAplicableEnConjunto = $descuentos->first(function($descuento){
                return !$descuento->esAplicableEnConjunto();
            });
            if($descuentoNoAplicableEnConjunto == null)
                return $descuentos;
            else
                return collect($socio->descuento());
        } else {
            return collect($descuento);
        }


    }

    public function crearCuotas($precio)
    {

            $ar = array();
            $f = Carbon::today()->addDays(30);
            $f2 = Carbon::today();
            $ultimoVencimiento = Carbon::today()->addDays($this->vencimiento_dias);
            for($i = 0; $i <= $this->cuotas; $i++)
            {
                $aux2 = $f2;
                if($i == $this->cuotas)
                {
                    $aux = $ultimoVencimiento;
                    $a = ['id_membresia' => $this->id, 'pago' => $precio/$this->cuotas, 'vto' => $aux->toDateString(), 'pagada' => false, 'fecha_inicio' => $aux2->toDateString()];
                    array_push($ar, $a);


                }else {
                    $aux = $f;

                    if($i == 0)
                        $a = ['id_membresia' => $this->id, 'pago' => $precio / $this->cuotas, 'fecha_vto' => $aux->toDateString(), 'pagada' => true, 'fecha_inicio' => $aux2->toDateString()];
                    else
                        $a = ['id_membresia' => $this->id, 'pago' => $precio / $this->cuotas, 'fecha_vto' => $aux->toDateString(), 'pagada' => false, 'fecha_inicio' => $aux2->toDateString()];

                    array_push($ar, $a);

                }

                $f->addDays(30);
                $f2->addDays(30);
                
            }
            $this->cuotas()->createMany($ar);

    }
}
