<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MembresiaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Db::transaction(function() {


            $this->call(DescuentoSeeder::class);
            $this->call(DiasSeeder::class);
            $this->call(ServicioSeeder::class);

            $membresia = factory(\App\Membresia::class)->create(['nro_cuotas' => 1, 'vencimiento_dias' => 30, 'precio' => 100, 'nombre' => 'membresia 1']);
            $membresia->servicios()->attach([
                1 => ['creditos' => 10, 'vto' => 30],
                2 => ['creditos' => 5, 'vto' => 30]
            ]);
            $membresia->descuentos()->attach([3, 4]);

            $membresia = factory(\App\Membresia::class)->create(['nro_cuotas' => 2, 'vencimiento_dias' => 90, 'precio' => 300, 'nombre' => 'membresia 2']);
            $membresia->servicios()->attach([
                1 => ['creditos' => 10, 'vto' => 30],
                2 => ['creditos' => 5, 'vto' => 50]
            ]);
            $membresia->descuentos()->attach([1,2, 3, 4]);

            $membresia = factory(\App\Membresia::class)->create(['nro_cuotas' => 3, 'vencimiento_dias' => 120, 'precio' => 600, 'nombre' => 'membresia 3']);
            $membresia->servicios()->attach([
                1 => ['creditos' => 10, 'vto' => 30],
                2 => ['creditos' => 5, 'vto' => 50]
            ]);
            $membresia->descuentos()->attach([1,2, 3, 4]);

            $membresiaEntradaGym = factory(\App\Membresia::class)->create(['nro_cuotas' => 1, 'vencimiento_dias' => 30, 'precio' => 100, 'nombre' => 'gimnasio']);
            $membresiaEntradaGym->servicios()->attach([
                11 => ['creditos' => null, 'vto' => 30]
            ]);
            $membresiaEntradaGym->descuentos()->attach([3, 4]);

            $membresiaConServicioNuncaDisponible = factory(\App\Membresia::class)->create(['nro_cuotas' => 1, 'vencimiento_dias' => 30, 'precio' => 100, 'nombre' => 'membresia con servicio nunca disponible']);
            $membresiaConServicioNuncaDisponible->servicios()->attach([
                12=> ['creditos' => null, 'vto' => 30]
            ]);
            $membresiaConServicioNuncaDisponible->descuentos()->attach([3, 4]);

            $membresiaConServicioQueNoRegistraEntrada = factory(\App\Membresia::class)->create(['nro_cuotas' => 1, 'vencimiento_dias' => 30, 'precio' => 100, 'nombre' => 'membresia con servicio que no registra entrada']);
            $membresiaConServicioQueNoRegistraEntrada->servicios()->attach([
                13 => ['creditos' => null, 'vto' => 30]
            ]);
            $membresiaConServicioQueNoRegistraEntrada->descuentos()->attach([3, 4]);



        });




    }
}
