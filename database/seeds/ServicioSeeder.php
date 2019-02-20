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
                factory(\App\Horario::class)->create(['dia' => 1, 'id_servicio' => $servicio->id, 'desde' => '7:00:00', 'hasta' => '15:00:00', 'entrada_desde' => '7:00:00', 'entrada_hasta' => '15:00:00'])->profesores()->attach(1);
                factory(\App\Horario::class)->create(['dia' => 2, 'id_servicio' => $servicio->id, 'desde' => '7:00:00', 'hasta' => '15:00:00', 'entrada_desde' => '7:00:00', 'entrada_hasta' => '15:00:00'])->profesores()->attach(1);
                factory(\App\Horario::class)->create(['dia' => 3, 'id_servicio' => $servicio->id, 'desde' => '7:00:00', 'hasta' => '15:00:00', 'entrada_desde' => '7:00:00', 'entrada_hasta' => '15:00:00'])->profesores()->attach(1);
                factory(\App\Horario::class)->create(['dia' => 4, 'id_servicio' => $servicio->id, 'desde' => '7:00:00', 'hasta' => '15:00:00', 'entrada_desde' => '7:00:00', 'entrada_hasta' => '15:00:00'])->profesores()->attach(1);
                factory(\App\Horario::class)->create(['dia' => 5, 'id_servicio' => $servicio->id, 'desde' => '7:00:00', 'hasta' => '15:00:00', 'entrada_desde' => '7:00:00', 'entrada_hasta' => '15:00:00'])->profesores()->attach(1);
                factory(\App\Horario::class)->create(['dia' => 6, 'id_servicio' => $servicio->id, 'desde' => '7:00:00', 'hasta' => '15:00:00', 'entrada_desde' => '7:00:00', 'entrada_hasta' => '15:00:00'])->profesores()->attach(1);
                factory(\App\Horario::class)->create(['dia' => 7, 'id_servicio' => $servicio->id, 'desde' => '7:00:00', 'hasta' => '15:00:00', 'entrada_desde' => '7:00:00', 'entrada_hasta' => '15:00:00'])->profesores()->attach(1);

                factory(\App\Horario::class)->create(['dia' => 1, 'id_servicio' => $servicio->id, 'desde' => '15:00:00', 'hasta' => '24:00:00', 'entrada_desde' => '15:00:00', 'entrada_hasta' => '24:00:00'])->profesores()->attach(2);
                factory(\App\Horario::class)->create(['dia' => 2, 'id_servicio' => $servicio->id, 'desde' => '15:00:00', 'hasta' => '24:00:00', 'entrada_desde' => '15:00:00', 'entrada_hasta' => '24:00:00'])->profesores()->attach(2);
                factory(\App\Horario::class)->create(['dia' => 3, 'id_servicio' => $servicio->id, 'desde' => '15:00:00', 'hasta' => '24:00:00', 'entrada_desde' => '15:00:00', 'entrada_hasta' => '24:00:00'])->profesores()->attach(2);
                factory(\App\Horario::class)->create(['dia' => 4, 'id_servicio' => $servicio->id, 'desde' => '15:00:00', 'hasta' => '24:00:00', 'entrada_desde' => '15:00:00', 'entrada_hasta' => '24:00:00'])->profesores()->attach(2);
                factory(\App\Horario::class)->create(['dia' => 5, 'id_servicio' => $servicio->id, 'desde' => '15:00:00', 'hasta' => '24:00:00', 'entrada_desde' => '15:00:00', 'entrada_hasta' => '24:00:00'])->profesores()->attach(2);
                factory(\App\Horario::class)->create(['dia' => 6, 'id_servicio' => $servicio->id, 'desde' => '15:00:00', 'hasta' => '24:00:00', 'entrada_desde' => '15:00:00', 'entrada_hasta' => '24:00:00'])->profesores()->attach(2);
                factory(\App\Horario::class)->create(['dia' => 7, 'id_servicio' => $servicio->id, 'desde' => '15:00:00', 'hasta' => '24:00:00', 'entrada_desde' => '15:00:00', 'entrada_hasta' => '24:00:00'])->profesores()->attach(2);

                factory(\App\Horario::class)->create(['dia' => 1, 'id_servicio' => $servicio->id, 'desde' => '15:00:00', 'hasta' => '24:00:00', 'entrada_desde' => '15:00:00', 'entrada_hasta' => '24:00:00'])->profesores()->attach(1);
                factory(\App\Horario::class)->create(['dia' => 2, 'id_servicio' => $servicio->id, 'desde' => '15:00:00', 'hasta' => '24:00:00', 'entrada_desde' => '15:00:00', 'entrada_hasta' => '24:00:00'])->profesores()->attach(1);
                factory(\App\Horario::class)->create(['dia' => 3, 'id_servicio' => $servicio->id, 'desde' => '15:00:00', 'hasta' => '24:00:00', 'entrada_desde' => '15:00:00', 'entrada_hasta' => '24:00:00'])->profesores()->attach(1);
                factory(\App\Horario::class)->create(['dia' => 4, 'id_servicio' => $servicio->id, 'desde' => '15:00:00', 'hasta' => '24:00:00', 'entrada_desde' => '15:00:00', 'entrada_hasta' => '24:00:00'])->profesores()->attach(1);
                factory(\App\Horario::class)->create(['dia' => 5, 'id_servicio' => $servicio->id, 'desde' => '15:00:00', 'hasta' => '24:00:00', 'entrada_desde' => '15:00:00', 'entrada_hasta' => '24:00:00'])->profesores()->attach(1);
                factory(\App\Horario::class)->create(['dia' => 6, 'id_servicio' => $servicio->id, 'desde' => '15:00:00', 'hasta' => '24:00:00', 'entrada_desde' => '15:00:00', 'entrada_hasta' => '24:00:00'])->profesores()->attach(1);
                factory(\App\Horario::class)->create(['dia' => 7, 'id_servicio' => $servicio->id, 'desde' => '15:00:00', 'hasta' => '24:00:00', 'entrada_desde' => '15:00:00', 'entrada_hasta' => '24:00:00'])->profesores()->attach(1);

            });

            $servicio = factory(\App\Servicio::class)->create(['nombre' => 'Entrada gym']);

            factory(\App\Horario::class)->create(['dia' => 1, 'id_servicio' => $servicio->id, 'desde' => '7:00:00', 'hasta' => '15:00:00', 'entrada_desde' => '7:00:00', 'entrada_hasta' => '15:00:00'])->profesores()->attach(1);
            factory(\App\Horario::class)->create(['dia' => 2, 'id_servicio' => $servicio->id, 'desde' => '7:00:00', 'hasta' => '15:00:00', 'entrada_desde' => '7:00:00', 'entrada_hasta' => '15:00:00'])->profesores()->attach(1);
            factory(\App\Horario::class)->create(['dia' => 3, 'id_servicio' => $servicio->id, 'desde' => '7:00:00', 'hasta' => '15:00:00', 'entrada_desde' => '7:00:00', 'entrada_hasta' => '15:00:00'])->profesores()->attach(1);
            factory(\App\Horario::class)->create(['dia' => 4, 'id_servicio' => $servicio->id, 'desde' => '7:00:00', 'hasta' => '15:00:00', 'entrada_desde' => '7:00:00', 'entrada_hasta' => '15:00:00'])->profesores()->attach(1);
            factory(\App\Horario::class)->create(['dia' => 5, 'id_servicio' => $servicio->id, 'desde' => '7:00:00', 'hasta' => '15:00:00', 'entrada_desde' => '7:00:00', 'entrada_hasta' => '15:00:00'])->profesores()->attach(1);
            factory(\App\Horario::class)->create(['dia' => 6, 'id_servicio' => $servicio->id, 'desde' => '7:00:00', 'hasta' => '15:00:00', 'entrada_desde' => '7:00:00', 'entrada_hasta' => '15:00:00'])->profesores()->attach(1);
            factory(\App\Horario::class)->create(['dia' => 7, 'id_servicio' => $servicio->id, 'desde' => '7:00:00', 'hasta' => '15:00:00', 'entrada_desde' => '7:00:00', 'entrada_hasta' => '15:00:00'])->profesores()->attach(1);

            factory(\App\Horario::class)->create(['dia' => 1, 'id_servicio' => $servicio->id, 'desde' => '15:00:00', 'hasta' => '24:00:00', 'entrada_desde' => '15:00:00', 'entrada_hasta' => '24:00:00'])->profesores()->attach(2);
            factory(\App\Horario::class)->create(['dia' => 2, 'id_servicio' => $servicio->id, 'desde' => '15:00:00', 'hasta' => '24:00:00', 'entrada_desde' => '15:00:00', 'entrada_hasta' => '24:00:00'])->profesores()->attach(2);
            factory(\App\Horario::class)->create(['dia' => 3, 'id_servicio' => $servicio->id, 'desde' => '15:00:00', 'hasta' => '24:00:00', 'entrada_desde' => '15:00:00', 'entrada_hasta' => '24:00:00'])->profesores()->attach(2);
            factory(\App\Horario::class)->create(['dia' => 4, 'id_servicio' => $servicio->id, 'desde' => '15:00:00', 'hasta' => '24:00:00', 'entrada_desde' => '15:00:00', 'entrada_hasta' => '24:00:00'])->profesores()->attach(2);
            factory(\App\Horario::class)->create(['dia' => 5, 'id_servicio' => $servicio->id, 'desde' => '15:00:00', 'hasta' => '24:00:00', 'entrada_desde' => '15:00:00', 'entrada_hasta' => '24:00:00'])->profesores()->attach(2);
            factory(\App\Horario::class)->create(['dia' => 6, 'id_servicio' => $servicio->id, 'desde' => '15:00:00', 'hasta' => '24:00:00', 'entrada_desde' => '15:00:00', 'entrada_hasta' => '24:00:00'])->profesores()->attach(2);
            factory(\App\Horario::class)->create(['dia' => 7, 'id_servicio' => $servicio->id, 'desde' => '15:00:00', 'hasta' => '24:00:00', 'entrada_desde' => '15:00:00', 'entrada_hasta' => '24:00:00'])->profesores()->attach(2);


            $servicioNuncaDisponible = factory(\App\Servicio::class)->create(['nombre' => 'Servicio nunca disponible']);


            $servicioNoRegistraEntrada = factory(\App\Servicio::class)->create(['nombre' => 'Servicio que no registra entrada', 'registra_entrada' => 0]);
        });

    }
}
