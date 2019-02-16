<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EntradaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function(){

            $this->call(MembresiaSeeder::class);

            factory(\App\Clase::class)->create(['dia' => 'Lunes', 'id_servicio' => 11, 'id_dia' => 1]);
            factory(\App\Clase::class)->create(['dia' => 'Martes', 'id_servicio' => 11, 'id_dia' => 2]);
            factory(\App\Clase::class)->create(['dia' => 'Miercoles', 'id_servicio' => 11, 'id_dia' => 3]);
            factory(\App\Clase::class)->create(['dia' => 'Jueves', 'id_servicio' => 11, 'id_dia' => 4]);
            factory(\App\Clase::class)->create(['dia' => 'Viernes', 'id_servicio' => 11, 'id_dia' => 5]);
            factory(\App\Clase::class)->create(['dia' => 'Sabado', 'id_servicio' => 11, 'id_dia' => 6]);
            factory(\App\Clase::class)->create(['dia' => 'Domingo', 'id_servicio' => 11, 'id_dia' => 7]);

            factory(\App\Clase::class)->create(['dia' => 'Lunes', 'id_servicio' => 2, 'id_dia' => 1]);
            factory(\App\Clase::class)->create(['dia' => 'Martes', 'id_servicio' => 2, 'id_dia' => 2]);
            factory(\App\Clase::class)->create(['dia' => 'Miercoles', 'id_servicio' => 2, 'id_dia' => 3]);
            factory(\App\Clase::class)->create(['dia' => 'Jueves', 'id_servicio' => 2, 'id_dia' => 4]);
            factory(\App\Clase::class)->create(['dia' => 'Viernes', 'id_servicio' => 2, 'id_dia' => 5]);
            factory(\App\Clase::class)->create(['dia' => 'Sabado', 'id_servicio' => 2, 'id_dia' => 6]);
            factory(\App\Clase::class)->create(['dia' => 'Domingo', 'id_servicio' => 2, 'id_dia' => 7]);

            factory(\App\Clase::class)->create(['dia' => 'Lunes', 'id_servicio' => 1, 'id_dia' => 1]);
            factory(\App\Clase::class)->create(['dia' => 'Martes', 'id_servicio' => 1, 'id_dia' => 2]);
            factory(\App\Clase::class)->create(['dia' => 'Miercoles', 'id_servicio' => 1, 'id_dia' => 3]);
            factory(\App\Clase::class)->create(['dia' => 'Jueves', 'id_servicio' => 1, 'id_dia' => 4]);
            factory(\App\Clase::class)->create(['dia' => 'Viernes', 'id_servicio' => 1, 'id_dia' => 5]);
            factory(\App\Clase::class)->create(['dia' => 'Sabado', 'id_servicio' => 1, 'id_dia' => 6]);
            factory(\App\Clase::class)->create(['dia' => 'Domingo', 'id_servicio' => 1, 'id_dia' => 7]);


            $socio1 = factory(\App\Socio::class)->create();
            $socio1->membresias()->attach([
                4 => ['vto' => Carbon::today()->addDays(30)->toDateString()]
            ]);
            $socio1->servicios()->attach([
                11 => ['creditos' => 100, 'vto' => Carbon::today()->addDays(30)->toDateString()]
            ]);

            $socio2 = factory(\App\Socio::class)->create();
            $socio2->membresias()->attach([
                1 => ['vto' => Carbon::today()->addDays(30)->toDateString()],
                2 => ['vto' => Carbon::today()->addDays(30)->toDateString()]
            ]);
            $socio2->servicios()->attach([
                1 => ['creditos' => 10, 'vto' => Carbon::today()->addDays(30)->toDateString()],
                2 => ['creditos' => 5, 'vto' => Carbon::today()->addDays(30)->toDateString()],
            ]);

            $socio3 = factory(\App\Socio::class)->create();
            $socio3->membresias()->attach([
                5 => ['vto' => Carbon::today()->addDays(30)->toDateString()]
            ]);
            $socio3->servicios()->attach([
                12 => ['creditos' => null, 'vto' => Carbon::today()->addDays(30)->toDateString()]
            ]);

            $socio4 = factory(\App\Socio::class)->create();
            $socio4->membresias()->attach([
                4 => ['vto' => Carbon::today()->addDays(30)->toDateString()]
            ]);
            $socio4->servicios()->attach([
                11 => ['creditos' => 0, 'vto' => Carbon::today()->addDays(30)->toDateString()]
            ]);

            $socio5 = factory(\App\Socio::class)->create();
            $socio5->membresias()->attach([
                4 => ['vto' => Carbon::today()->addDays(30)->toDateString()]
            ]);
            $socio5->servicios()->attach([
                11 => ['creditos' => 100, 'vto' => Carbon::today()->subDay()->toDateString()]
            ]);

            $socio6 = factory(\App\Socio::class)->create();
            $socio6->membresias()->attach([
                4 => ['vto' => Carbon::today()->addDays(30)->toDateString()]
            ]);
            $socio6->servicios()->attach([
                11 => ['creditos' => 100, 'vto' => Carbon::today()->subDay()->toDateString()]
            ]);

            $socio7 = factory(\App\Socio::class)->create();
            $socio7->membresias()->attach([
                4 => ['vto' => Carbon::today()->addDays(30)->toDateString()]
            ]);
            $socio7->servicios()->attach([
                11 => ['creditos' => 100, 'vto' => Carbon::today()->addDays(30)->toDateString()]
            ]);

            $socio8 = factory(\App\Socio::class)->create();
            $socio8->membresias()->attach([
                1 => ['vto' => Carbon::today()->addDays(30)->toDateString()]
            ]);
            $socio8->servicios()->attach([
                1 => ['creditos' => 10, 'vto' => Carbon::today()->subDay()->toDateString()],
                2 => ['creditos' => 5, 'vto' => Carbon::today()->addDays(30)->toDateString()],
            ]);

            $socio9 = factory(\App\Socio::class)->create();
            $socio9->membresias()->attach([
                6 => ['vto' => Carbon::today()->addDays(30)->toDateString()]
            ]);
            $socio9->servicios()->attach([
                13 => ['creditos' => 10, 'vto' => Carbon::today()->addDays(30)->toDateString()],
            ]);


            $cuota = factory(\App\Cuota::class)->create(['pago' => 100, 'pagada' => true, 'fecha_inicio' => Carbon::today()->toDateString(), 'fecha_vto' => Carbon::today()->toDateString(), 'id_venta' => 1]);
            $cuota2 = factory(\App\Cuota::class)->create(['pago' => 100, 'pagada' => true, 'fecha_inicio' => Carbon::today()->toDateString(), 'fecha_vto' => Carbon::today()->toDateString(), 'id_venta' => 2]);
            $cuota2 = factory(\App\Cuota::class)->create(['pago' => 100, 'pagada' => true, 'fecha_inicio' => Carbon::today()->toDateString(), 'fecha_vto' => Carbon::today()->toDateString(), 'id_venta' => 3]);
            $cuota3 = factory(\App\Cuota::class)->create(['pago' => 100, 'pagada' => true, 'fecha_inicio' => Carbon::today()->toDateString(), 'fecha_vto' => Carbon::today()->toDateString(), 'id_venta' => 4]);
            $cuota4 = factory(\App\Cuota::class)->create(['pago' => 100, 'pagada' => true, 'fecha_inicio' => Carbon::today()->toDateString(), 'fecha_vto' => Carbon::today()->toDateString(), 'id_venta' => 5]);
            $cuota5 = factory(\App\Cuota::class)->create(['pago' => 100, 'pagada' => true, 'fecha_inicio' => Carbon::today()->toDateString(), 'fecha_vto' => Carbon::today()->toDateString(), 'id_venta' => 6]);
            $cuota6 = factory(\App\Cuota::class)->create(['pago' => 100, 'pagada' => true, 'fecha_inicio' => Carbon::today()->toDateString(), 'fecha_vto' => Carbon::today()->toDateString(), 'id_venta' => 7]);
            $cuota7 = factory(\App\Cuota::class)->create(['pago' => 100, 'pagada' => false, 'fecha_inicio' => Carbon::today()->toDateString(), 'fecha_vto' => Carbon::today()->addDays(30)->toDateString(), 'id_venta' => 8]);
            $cuota8 = factory(\App\Cuota::class)->create(['pago' => 100, 'pagada' => true, 'fecha_inicio' => Carbon::today()->toDateString(), 'fecha_vto' => Carbon::today()->addDays(30)->toDateString(), 'id_venta' => 9]);
            $cuota9 = factory(\App\Cuota::class)->create(['pago' => 100, 'pagada' => true, 'fecha_inicio' => Carbon::today()->toDateString(), 'fecha_vto' => Carbon::today()->addDays(30)->toDateString(), 'id_venta' => 10]);





        });
    }
}
