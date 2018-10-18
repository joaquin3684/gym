<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServicioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Db::transaction(function() {

            factory(\App\Servicio::class, 10)->create()->each(function($servicio, $key){
                $servicio->dias()->attach([
                    1 => ['desde' => '7:00:00', 'hasta' => '22:00:00', 'entrada_desde' => '7:00:00', 'entrada_hasta' => '22:00:00'],
                    2 => ['desde' => '7:00:00', 'hasta' => '22:00:00', 'entrada_desde' => '7:00:00', 'entrada_hasta' => '22:00:00'],
                    3 => ['desde' => '7:00:00', 'hasta' => '22:00:00', 'entrada_desde' => '7:00:00', 'entrada_hasta' => '22:00:00'],
                    4 => ['desde' => '7:00:00', 'hasta' => '22:00:00', 'entrada_desde' => '7:00:00', 'entrada_hasta' => '22:00:00'],
                    5 => ['desde' => '7:00:00', 'hasta' => '22:00:00', 'entrada_desde' => '7:00:00', 'entrada_hasta' => '22:00:00'],
                    6 => ['desde' => '7:00:00', 'hasta' => '22:00:00', 'entrada_desde' => '7:00:00', 'entrada_hasta' => '22:00:00'],
                    7 => ['desde' => '17:00:00', 'hasta' => '20:00:00', 'entrada_desde' => '7:00:00', 'entrada_hasta' => '22:00:00'],
                ]);
            });

            $servicio = factory(\App\Servicio::class)->create(['nombre' => 'Entrada gym'])->dias()->attach([
                1 => ['desde' => '7:00:00', 'hasta' => '22:00:00', 'entrada_desde' => '7:00:00', 'entrada_hasta' => '22:00:00'],
                2 => ['desde' => '7:00:00', 'hasta' => '22:00:00', 'entrada_desde' => '7:00:00', 'entrada_hasta' => '22:00:00'],
                3 => ['desde' => '7:00:00', 'hasta' => '22:00:00', 'entrada_desde' => '7:00:00', 'entrada_hasta' => '22:00:00'],
                4 => ['desde' => '7:00:00', 'hasta' => '22:00:00', 'entrada_desde' => '7:00:00', 'entrada_hasta' => '22:00:00'],
                5 => ['desde' => '7:00:00', 'hasta' => '22:00:00', 'entrada_desde' => '7:00:00', 'entrada_hasta' => '22:00:00'],
                6 => ['desde' => '7:00:00', 'hasta' => '22:00:00', 'entrada_desde' => '7:00:00', 'entrada_hasta' => '22:00:00'],
                7 => ['desde' => '7:00:00', 'hasta' => '22:00:00', 'entrada_desde' => '7:00:00', 'entrada_hasta' => '22:00:00'],

            ]);
        });

    }
}
