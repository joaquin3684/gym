<?php

namespace App\Console;

use App\Clase;
use App\Servicio;
use App\Horario;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;
use stdClass;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function(){
            $spd = Horario::with('servicio', 'profesores')->get();
            $serv = $spd->map(function($s){
                $ser = $s->servicio;
                return $ser;
            })->unique(function($serv){
                return $serv->id;
            });

            $serv = $serv->map(function($ser) use ($spd){
                $filtroPorElServicioActual = $spd->filter(function($s) use ($ser){return $s->id_servicio == $ser->id;});
                $ser->dias = $filtroPorElServicioActual->map(function($s) use ($spd){


                    $s->profesores = $filtroPorDiaYHoraYservicio = $spd->filter(function($s2) use ($s){
                        return $s2->id_servicio == $s->id_servicio && $s2->id_dia == $s->id_dia && $s2->desde == $s->desde && $s2->hasta == $s->hasta && $s2->entrada_desde == $s->entrada_desde && $s2->entrada_hasta == $s->entrada_hasta;
                    })->map(function($s){ return $s->profesor;});

                    return $s;
                })->unique(function($s){ $s->id_servicio.$s->id_dia.$s->desde.$s->hasta.$s->entrada_desde.$s->entrada_hasta;});

                return $ser;
            });


            $serv->each(function($servicio){
                $servicio->dias->each(function($dia) use ($servicio){
                    Clase::create(['fecha' => Carbon::today()->toDateString(), 'dia' => $dia->id_dia, 'id_servicio' => $servicio->id, 'estado' => 1, 'desde' => $dia->desde, 'hasta' => $dia->hasta, 'entrada_desde' => $dia->entrada_desde, 'entrada_hasta' => $dia->entrada_hasta, 'id_dia' => $dia->id_dia]);

                });

            });
        });


    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
