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
        return $this->hasManyThrough('App\Cuota', 'App\SocioMembresia', 'id_membresia', 'id_socio_membresia', 'id', 'id');
    }

    public function descuentos()
    {
        return $this->belongsToMany('App\Descuento', 'membresia_descuento', 'id_membresia', 'id_descuento');
    }

    public function servicios()
    {
        return $this->belongsToMany('App\Servicio', 'membresia_servicio','id_membresia', 'id_servicio')->withPivot('creditos', 'vto');
    }
    
    public function socios()
    {
        return $this->belongsToMany('App\Socio', 'socio_membresia', 'id_membresia', 'id_socio')->withPivot('vto');
    }

    public function vender(Socio $socio, $cantidad, $tipoPago, $descuento, $idUsuario, $observacion = null)
    {

        $sm = $socio->socioMembresia->first(function ($sm){
            return $sm->id_membresia = $this->id && $sm->cuotas->contains(function($cuota){
                 return $cuota->pagada == false;
                });
        });


        $precio = null;
        if($sm != null)
        {
            $cuota = $sm->cuotas->sortBy(function ($cuota){
                return $cuota->id;
            })->first(function($cuota){
                return $cuota->pagada == false;
            });
            $precio = $cuota->pago;
            $cuota->pagada = 1;
            $cuota->save();
        } else {
            $descuentos = $this->buscarDescuentos($socio, $descuento);
            $descuentoDeSocio = $descuentos->first(function($descuento){return $descuento->tipo == 2;});
            $precio = $this->aplicarDescuento($descuentos);

            $vto = $this->generarVencimiento($socio);
            $socioMembresia = SocioMembresia::create(['id_socio' => $socio->id, 'id_membresia' => $this->id, 'vto' => $vto]);
            $this->crearCuotas($precio, $socioMembresia, Carbon::createFromFormat('Y-m-d', $vto)->subDays($this->vencimiento_dias)->toDateString());

            $this->adjuntarServicios($socio);
            Venta::create(['fecha' => Carbon::today()->toDateString(), 'precio' => $precio, 'id_membresia' => $this->id, 'id_socio' => $socio->id, 'cantidad' => $cantidad, 'id_descuento_membresia' => is_null($descuento) ? null : $descuento->id, 'id_descuento_socio' => is_null($descuentoDeSocio)? null : $descuentoDeSocio->id]);
            $precio = $precio/ $this->nro_cuotas;
        }

        CajaService::ingreso($precio, $this->nombre, $observacion, $tipoPago, $idUsuario);
    }

    public function generarVencimiento(Socio $socio)
    {
        $membresia = $socio->socioMembresia->sortByDesc(function($membresia){
            return $membresia->vto;
        })->first();
        
        $hoy = Carbon::today();
        if($membresia == null)
        {
            return $hoy->addDays($this->vencimiento_dias)->toDateString();
        }
        else
        {
            if($membresia->vto > $hoy->toDateString())
                return Carbon::createFromFormat('Y-m-d', $membresia->vto)->addDays($this->vencimiento_dias)->toDateString();
            else
                return $hoy->addDays($this->vencimiento_dias)->toDateString();
        }


    }

    public function aplicarDescuento($descuentos)
    {
        $monto = $this->precio;
        $montoADescontar = $this->precio;
        $algunDescuentoEnConjunto = $descuentos->contains(function($descuento){ return $descuento->esAplicableEnConjunto(); });
        if($algunDescuentoEnConjunto)
        {
            $descuentos->each(function(Descuento $descuento) use (&$monto){
                $descuentoAplicado = $descuento->aplicar($monto);
                $monto = $monto - $descuentoAplicado;
            });
        }
        else
        {
            $totalDescuentos = $descuentos->sum(function($descuento) use ($monto){
                return $descuento->aplicar($monto);
            });
            $monto = $monto - $totalDescuentos;
        }

        return $monto;
    }

    public function adjuntarServicios(Socio $socio)
    {
        $ids = array();
        $vtoMembresia = $this->generarVencimiento($socio);
        foreach($this->servicios as $servicio)
        {
            $ids[$servicio->id] = ['creditos' => $servicio->pivot->creditos, 'vto' => Carbon::createFromFormat('Y-m-d',$vtoMembresia)->subDays($this->vencimiento_dias)->addDays($servicio->pivot->vto)->toDateString()];
        }

        $socio->servicios()->attach($ids);
    }

    public function buscarDescuentos(Socio $socio, $descuento)
    {

        $descuentos = collect();
        if(!is_null($descuento))
            $descuentos->push($descuento);
        if(!is_null($socio->descuento))
        {
            $descuentos->push($socio->descuento);
            $compartenDescuento = $socio->descuento->membresias->contains(function($membresia){ return $membresia->id == $this->id;});

            if($compartenDescuento)
                return $descuentos;
            else
                return is_null($descuento) ? collect() : collect()->push($descuento);

        }

        return $descuentos;




    }

    public function crearCuotas($precio, SocioMembresia $socioMembresia, $fechaDesde)
    {
            $ar = array();
            $f = Carbon::createFromFormat('Y-m-d', $fechaDesde)->addDays(30);
            $f2 = Carbon::createFromFormat('Y-m-d', $fechaDesde);
            $ultimoVencimiento = Carbon::today()->addDays($this->vencimiento_dias);
            if($this->nro_cuotas == 1)
            {
                $a = ['id_socio_membresia' => $socioMembresia, 'pago' => $precio/$this->nro_cuotas, 'fecha_vto' => $f->toDateString(), 'pagada' => true, 'fecha_inicio' => $f2->toDateString(), 'nro_cuota' => 1];
                array_push($ar, $a);

            } else {

                for ($i = 1; $i <= $this->nro_cuotas; $i++) {
                    $aux2 = $f2;
                    if ($i == $this->nro_cuotas) {
                        $aux = $ultimoVencimiento;
                        $a = ['id_socio_membresia' => $socioMembresia, 'pago' => $precio / $this->nro_cuotas, 'fecha_vto' => $aux->toDateString(), 'pagada' => false, 'fecha_inicio' => $aux2->toDateString(), 'nro_cuota' => $i];
                        array_push($ar, $a);


                    } else {
                        $aux = $f;

                        if ($i == 1)
                            $a = ['id_socio_membresia' => $socioMembresia,'pago' => $precio / $this->nro_cuotas, 'fecha_vto' => $aux->toDateString(), 'pagada' => true, 'fecha_inicio' => $aux2->toDateString(), 'nro_cuota' => $i];
                        else
                            $a = ['id_socio_membresia' => $socioMembresia, 'pago' => $precio / $this->nro_cuotas, 'fecha_vto' => $aux->toDateString(), 'pagada' => false, 'fecha_inicio' => $aux2->toDateString(), 'nro_cuota' => $i];

                        array_push($ar, $a);

                    }

                    $f->addDays(30);
                    $f2->addDays(30);

                }
            }
            $socioMembresia->cuotas()->createMany($ar);

    }
}
