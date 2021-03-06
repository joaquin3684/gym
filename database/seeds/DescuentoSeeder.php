<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DescuentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Db::transaction(function(){
            factory(\App\Descuento::class)->create(['nombre' => 'descuento socio 1', 'aplicable_enconjunto' => true, 'tipo' => 2]);
            factory(\App\Descuento::class)->create(['nombre' => 'descuento socio 2','tipo' => 2]);
            factory(\App\Descuento::class)->create(['nombre' => 'descuento membresia 1', 'aplicable_enconjunto' => true, 'tipo' => 1]);
            factory(\App\Descuento::class)->create(['nombre' => 'descuento membresia 2', 'tipo' => 1]);
        });

    }
}
