<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PagoProfesoresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Db::transaction(function(){
            factory(\App\Servicio::class)->create();
            $clase1 = factory(\App\Clase::class)->create(['fecha' => Carbon::today()->subDays(7)->toDateString()]);
            $clase2 = factory(\App\Clase::class)->create(['fecha' => Carbon::today()->subDays(7)->toDateString()]);

            $profesor1 = factory(\App\Profesor::class)->create(['cantidad_dias_cobro' => 3]);
            $profesor1->clases()->attach([$clase1->id => ['tipo_pago' => 'clase', 'precio' => 200], $clase2->id => ['tipo_pago' => 'hora', 'precio' => 100]]);

            $profesor2 = factory(\App\Profesor::class)->create(['cantidad_dias_cobro' => 7]);
            $profesor2->clases()->attach([$clase1->id => ['tipo_pago' => 'hora', 'precio' => 100], $clase2->id => ['tipo_pago' => 'clase', 'precio' => 200]]);


            

            \App\Pago::create(['id_profesor' => $profesor1->id, 'fecha' => Carbon::today()->subDays(10)->toDateString()]);
            \App\Pago::create(['id_profesor' => $profesor2->id, 'fecha' => Carbon::today()->subDays(10)->toDateString()]);


        });
    }
}
