<?php
namespace App\services;

use App\Accesos;
use App\Descuento;
use App\Membresia;
use App\Socio;
use App\User;
use App\Vendible;
use Carbon\Carbon;

/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 31/08/18
 * Time: 13:43
 */


class SocioService
{
    public function crear($elem)
    {
        $socio = new Socio($elem);
        $socio->save();

        return $socio->id;
    }

    public function update($elem, $id)
    {
        $socio = Socio::find($id);
        $socio->fill($elem);
        $socio->save();
        return $socio->id;
    }

    public function find($id)
    {
        return Socio::with('descuento')->find($id);
    }

    public function all()
    {
        return Socio::with(['membresias.cuotas', 'descuento', 'servicios' => function($q){
            $q->where('vto', '>=', Carbon::today()->toDateString());
        }])->get();
    }

    public function comprar($elem, $userId)
    {
        $idSocio = $elem['idSocio'];
        $tipoPago = $elem['tipoPago'];
        $observacion = $elem['observacion'];
        $socio = Socio::with('descuento', 'ventas.cuotas')->find($idSocio);
        foreach ($elem['membresias'] as $membresia)
        {
            $idMembresia = $membresia['id'];
            $idDescuento = $membresia['idDescuento'];
            $cantidad = $membresia['cantidad'];
            $membresia = Membresia::find($idMembresia);
            $descuento = is_null($idDescuento) ? null : Descuento::find($idDescuento);
            $membresia->vender($socio, $cantidad, $tipoPago, $descuento, $userId, $observacion);
        }



    }

    public function acceder($elem)
    {
        $hoy = Carbon::today()->toDateString();
        $dia = Carbon::today()->dayOfWeekIso;
        $hora = Carbon::now('America/Argentina/Buenos_Aires')->toTimeString();
        $horaPosterior = Carbon::now('America/Argentina/Buenos_Aires')->addHour(2)->toTimeString();
        $idSocio = $elem['idSocio'];
        $automatico = $elem['automatico'];

        /**
         * aca traigo el socio con las membresias que tengan cuotas vencidas con sus respectivas cuotas
         * y con los servicios que tenga vigentes dentro del horario actual
         */

        if($automatico)
            $socio = Socio::with(['ventas' => function($q) use ($hoy){
                $q->where('vto', '>=', $hoy)
                    ->with(['cuotas' => function($q) use ($hoy){
                    $q->where('pagada', false)
                        ->where('fecha_inicio', '<=', $hoy)
                        ->where('fecha_vto', '>', $hoy);
                }]);

            }, 'membresias' => function($query) use ($hoy, $dia, $hora, $idSocio){
                $query->where('vto', '>=', $hoy)

            ->with('servicios');
            }, 'servicios' => function($query) use ($hoy, $dia, $hora){
                $query->where('vto', '>=', $hoy)
                ->where(function($q){
                    $q->where('creditos', '>', 0)
                        ->orWhere('creditos', null);
                })
                ->where('registra_entrada', true)
                ->with(['clases' => function($q) use($dia, $hora, $hoy) {
                        $q->where('fecha', $hoy)
                            ->where('id_dia', $dia)

                            ->where('entrada_desde', '<=', $hora)
                        ->where('entrada_hasta', '>=', $hora);

                }])
                ->whereHas('clases', function($q) use ($dia, $hora, $hoy){
                    $q->where('fecha', $hoy)
                        ->where('id_dia', $dia)

                        ->where('entrada_desde', '<=', $hora)
                        ->where('entrada_hasta', '>=', $hora);
                });

        }])->find($idSocio);
        else
            $socio = $socio = Socio::with(['ventas' => function($q) use ($hoy){
                $q->where('vto', '>=', $hoy)
                    ->with(['cuotas' => function($q) use ($hoy){
                        $q->where('pagada', false)
                            ->where('fecha_inicio', '<=', $hoy)
                            ->where('fecha_vto', '>', $hoy);
                    }]);

            },   'membresias' => function($query) use ($hoy, $dia, $hora, $idSocio){
                $query->where('vto', '>=', $hoy)

                    ->with('servicios');
            }, 'servicios' => function($query) use ($hoy, $dia, $hora, $horaPosterior){
                $query->where('vto', '>=', $hoy)
                    ->where(function($q){
                        $q->where('creditos', '>', 0)
                            ->orWhere('creditos', null);
                    })
                    ->where('registra_entrada', true)
                    ->with(['clases' => function($q) use($dia, $hora, $horaPosterior, $hoy) {
                        $q->where('fecha', $hoy)
                            ->where('id_dia', $dia)
                            ->where(function($q) use ($hora){
                                $q->where('entrada_desde', '<=', $hora)
                                    ->where('entrada_hasta', '>=', $hora);
                            })
                            ->orWhere(function($q) use ($hora, $horaPosterior){
                                $q->whereBetween('entrada_desde', [$hora, $horaPosterior]);
                            });
                    }])
                ->whereHas('clases', function($q) use ($dia, $hora, $horaPosterior, $hoy){
                    $q->where('fecha', $hoy)
                        ->where('id_dia', $dia)
                        ->where(function($q) use ($hora){
                            $q->where('entrada_desde', '<=', $hora)
                                ->where('entrada_hasta', '>=', $hora);
                        })
                        ->orWhere(function($q) use ($hora, $horaPosterior){
                            $q->whereBetween('entrada_desde', [$hora, $horaPosterior]);
                        });
                });


            }])->find($idSocio);

        return $socio->acceder();
    }


    public function sociosConCompra()
    {
        return Socio::with('venta');

    }

    public function borrarMembresia($idSocio, $idMembresia)
    {
        $socio = Socio::find($idSocio);
        $membresiaSrv = new MembresiaService();
        $membresia = $membresiaSrv->find($idMembresia);
        $socio->borrarMembresia($membresia);
    }

}