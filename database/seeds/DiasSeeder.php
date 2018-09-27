<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DiasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Db::transaction(function(){
            \App\Dia::create(['nombre' => 'Lunes']);
            \App\Dia::create(['nombre' => 'Martes']);
            \App\Dia::create(['nombre' => 'Miercoles']);
            \App\Dia::create(['nombre' => 'Jueves']);
            \App\Dia::create(['nombre' => 'Viernes']);
            \App\Dia::create(['nombre' => 'Sabado']);
            \App\Dia::create(['nombre' => 'Domingo']);
        });
    }
}
