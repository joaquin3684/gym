<?php

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
            $socio1->membresias()->attach([
                4 => ['vto' => Carbon::today()->addDays(30)->toDateString()]
            ]);
            $socio1->servicios()->attach([
                11 => ['creditos' => null, 'vto' => Carbon::today()->addDays(30)->toDateString()]
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

            $cuota = factory(\App\Cuota::class)->create(['pago' => 100, 'pagada' => true, 'fecha_inicio' => Carbon::today()->toDateString(), 'fecha_vto' => Carbon::today()->toDateString(), 'id_socio' => $socio1->id, 'id_membresia' => 1]);
            $cuota2 = factory(\App\Cuota::class)->create(['pago' => 100, 'pagada' => true, 'fecha_inicio' => Carbon::today()->toDateString(), 'fecha_vto' => Carbon::today()->toDateString(), 'id_socio' => $socio2->id, 'id_membresia' => 1]);



        });
    }
}
