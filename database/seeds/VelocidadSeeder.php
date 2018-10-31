<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VelocidadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Db::transaction(function() {

            factory(\App\Servicio::class, 1000)->create()->each(function($servicio, $key){
                $servicio->dias()->attach([
                    1 => ['desde' => '17:00:00', 'hasta' => '20:00:00'],
                    2 => ['desde' => '17:00:00', 'hasta' => '20:00:00'],
                    5 => ['desde' => '17:00:00', 'hasta' => '20:00:00'],
                ]);
            });

            factory(\App\Socio::class, 1000)->create();
        });

    }
}
