<?php

use App\Venta;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SocioSeeder extends Seeder
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

            $socio1 = factory(\App\Socio::class)->create();
            $venta1 = Venta::create(['id_membresia' => 4, 'vto' => Carbon::today()->addDays(30)->toDateString(), 'id_socio' => $socio1->id, 'fecha' => Carbon::today()->toDateString(), 'precio' => 100]);
            $venta1->servicios()->attach([
                11 => ['creditos' => 100, 'vto' => Carbon::today()->addDays(30)->toDateString()]

            ]);

            $socio2 = factory(\App\Socio::class)->create();
            $venta2 = Venta::create(['id_membresia' => 1, 'vto' => Carbon::today()->addDays(30)->toDateString(), 'id_socio' => $socio2->id, 'fecha' => Carbon::today()->toDateString(), 'precio' => 100]);
            $venta3 = Venta::create(['id_membresia' => 2, 'vto' => Carbon::today()->addDays(30)->toDateString(), 'id_socio' => $socio2->id, 'fecha' => Carbon::today()->toDateString(), 'precio' => 100]);

            $venta2->servicios()->attach([
                1 => ['creditos' => 10, 'vto' => Carbon::today()->addDays(30)->toDateString()],
                2 => ['creditos' => 5, 'vto' => Carbon::today()->addDays(30)->toDateString()],
            ]);

            $venta3->servicios()->attach([
                1 => ['creditos' => 10, 'vto' => Carbon::today()->addDays(30)->toDateString()],
                2 => ['creditos' => 5, 'vto' => Carbon::today()->addDays(30)->toDateString()],
            ]);



            $cuota = factory(\App\Cuota::class)->create(['pago' => 100, 'pagada' => true, 'fecha_inicio' => Carbon::today()->toDateString(), 'fecha_vto' => Carbon::today()->toDateString(), 'id_venta' => 1]);
            $cuota2 = factory(\App\Cuota::class)->create(['pago' => 100, 'pagada' => true, 'fecha_inicio' => Carbon::today()->toDateString(), 'fecha_vto' => Carbon::today()->toDateString(), 'id_venta' => 2]);
            $cuota3 = factory(\App\Cuota::class)->create(['pago' => 100, 'pagada' => true, 'fecha_inicio' => Carbon::today()->toDateString(), 'fecha_vto' => Carbon::today()->toDateString(), 'id_venta' => 3]);



        });
    }
}
