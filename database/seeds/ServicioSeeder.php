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
                    1 => ['desde' => '7:00:00', 'hasta' => '15:00:00', 'entrada_desde' => '7:00:00', 'entrada_hasta' => '15:00:00', 'id_profesor' => 1],
                    2 => ['desde' => '7:00:00', 'hasta' => '15:00:00', 'entrada_desde' => '7:00:00', 'entrada_hasta' => '15:00:00', 'id_profesor' => 1],
                    3 => ['desde' => '7:00:00', 'hasta' => '15:00:00', 'entrada_desde' => '7:00:00', 'entrada_hasta' => '15:00:00', 'id_profesor' => 1],
                    4 => ['desde' => '7:00:00', 'hasta' => '15:00:00', 'entrada_desde' => '7:00:00', 'entrada_hasta' => '15:00:00', 'id_profesor' => 1],
                    5 => ['desde' => '7:00:00', 'hasta' => '15:00:00', 'entrada_desde' => '7:00:00', 'entrada_hasta' => '15:00:00', 'id_profesor' => 1],
                    6 => ['desde' => '7:00:00', 'hasta' => '15:00:00', 'entrada_desde' => '7:00:00', 'entrada_hasta' => '15:00:00', 'id_profesor' => 1],
                    7 => ['desde' => '7:00:00', 'hasta' => '15:00:00', 'entrada_desde' => '7:00:00', 'entrada_hasta' => '15:00:00', 'id_profesor' => 1],
                ]);

                $servicio->dias()->attach([
                    1 => ['desde' => '15:00:00', 'hasta' => '24:00:00', 'entrada_desde' => '15:00:00', 'entrada_hasta' => '24:00:00', 'id_profesor' => 2],
                    2 => ['desde' => '15:00:00', 'hasta' => '24:00:00', 'entrada_desde' => '15:00:00', 'entrada_hasta' => '24:00:00', 'id_profesor' => 2],
                    3 => ['desde' => '15:00:00', 'hasta' => '24:00:00', 'entrada_desde' => '15:00:00', 'entrada_hasta' => '24:00:00', 'id_profesor' => 2],
                    4 => ['desde' => '15:00:00', 'hasta' => '24:00:00', 'entrada_desde' => '15:00:00', 'entrada_hasta' => '24:00:00', 'id_profesor' => 2],
                    5 => ['desde' => '15:00:00', 'hasta' => '24:00:00', 'entrada_desde' => '15:00:00', 'entrada_hasta' => '24:00:00', 'id_profesor' => 2],
                    6 => ['desde' => '15:00:00', 'hasta' => '24:00:00', 'entrada_desde' => '15:00:00', 'entrada_hasta' => '24:00:00', 'id_profesor' => 2],
                    7 => ['desde' => '15:00:00', 'hasta' => '24:00:00', 'entrada_desde' => '15:00:00', 'entrada_hasta' => '24:00:00', 'id_profesor' => 2],
                ]);

                $servicio->dias()->attach([
                    1 => ['desde' => '15:00:00', 'hasta' => '24:00:00', 'entrada_desde' => '15:00:00', 'entrada_hasta' => '24:00:00', 'id_profesor' => 1],
                    2 => ['desde' => '15:00:00', 'hasta' => '24:00:00', 'entrada_desde' => '15:00:00', 'entrada_hasta' => '24:00:00', 'id_profesor' => 1],
                    3 => ['desde' => '15:00:00', 'hasta' => '24:00:00', 'entrada_desde' => '15:00:00', 'entrada_hasta' => '24:00:00', 'id_profesor' => 1],
                    4 => ['desde' => '15:00:00', 'hasta' => '24:00:00', 'entrada_desde' => '15:00:00', 'entrada_hasta' => '24:00:00', 'id_profesor' => 1],
                    5 => ['desde' => '15:00:00', 'hasta' => '24:00:00', 'entrada_desde' => '15:00:00', 'entrada_hasta' => '24:00:00', 'id_profesor' => 1],
                    6 => ['desde' => '15:00:00', 'hasta' => '24:00:00', 'entrada_desde' => '15:00:00', 'entrada_hasta' => '24:00:00', 'id_profesor' => 1],
                    7 => ['desde' => '15:00:00', 'hasta' => '24:00:00', 'entrada_desde' => '15:00:00', 'entrada_hasta' => '24:00:00', 'id_profesor' => 1],
                ]);
            });

            $servicio = factory(\App\Servicio::class)->create(['nombre' => 'Entrada gym']);
                $servicio->dias()->attach([
                    1 => ['desde' => '7:00:00', 'hasta' => '15:00:00', 'entrada_desde' => '7:00:00', 'entrada_hasta' => '15:00:00', 'id_profesor' => 1],
                    2 => ['desde' => '7:00:00', 'hasta' => '15:00:00', 'entrada_desde' => '7:00:00', 'entrada_hasta' => '15:00:00', 'id_profesor' => 1],
                    3 => ['desde' => '7:00:00', 'hasta' => '15:00:00', 'entrada_desde' => '7:00:00', 'entrada_hasta' => '15:00:00', 'id_profesor' => 1],
                    4 => ['desde' => '7:00:00', 'hasta' => '15:00:00', 'entrada_desde' => '7:00:00', 'entrada_hasta' => '15:00:00', 'id_profesor' => 1],
                    5 => ['desde' => '7:00:00', 'hasta' => '15:00:00', 'entrada_desde' => '7:00:00', 'entrada_hasta' => '15:00:00', 'id_profesor' => 1],
                    6 => ['desde' => '7:00:00', 'hasta' => '15:00:00', 'entrada_desde' => '7:00:00', 'entrada_hasta' => '15:00:00', 'id_profesor' => 1],
                    7 => ['desde' => '7:00:00', 'hasta' => '15:00:00', 'entrada_desde' => '7:00:00', 'entrada_hasta' => '15:00:00', 'id_profesor' => 1],

            ]);
            $servicio->dias()->attach([
                1 => ['desde' => '15:00:00', 'hasta' => '24:00:00', 'entrada_desde' => '15:00:00', 'entrada_hasta' => '24:00:00', 'id_profesor' => 2],
                2 => ['desde' => '15:00:00', 'hasta' => '24:00:00', 'entrada_desde' => '15:00:00', 'entrada_hasta' => '24:00:00', 'id_profesor' => 2],
                3 => ['desde' => '15:00:00', 'hasta' => '24:00:00', 'entrada_desde' => '15:00:00', 'entrada_hasta' => '24:00:00', 'id_profesor' => 2],
                4 => ['desde' => '15:00:00', 'hasta' => '24:00:00', 'entrada_desde' => '15:00:00', 'entrada_hasta' => '24:00:00', 'id_profesor' => 2],
                5 => ['desde' => '15:00:00', 'hasta' => '24:00:00', 'entrada_desde' => '15:00:00', 'entrada_hasta' => '24:00:00', 'id_profesor' => 2],
                6 => ['desde' => '15:00:00', 'hasta' => '24:00:00', 'entrada_desde' => '15:00:00', 'entrada_hasta' => '24:00:00', 'id_profesor' => 2],
                7 => ['desde' => '15:00:00', 'hasta' => '24:00:00', 'entrada_desde' => '15:00:00', 'entrada_hasta' => '24:00:00', 'id_profesor' => 2]
            ]);

            $servicioNuncaDisponible = factory(\App\Servicio::class)->create(['nombre' => 'Servicio nunca disponible']);


            $servicioNoRegistraEntrada = factory(\App\Servicio::class)->create(['nombre' => 'Servicio que no registra entrada', 'registra_entrada' => 0]);
        });

    }
}
