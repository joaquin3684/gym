<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClasesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function(){

            // clases futuras = 3
            factory(\App\Clase::class,3)->create(['fecha' => \Carbon\Carbon::today()->addDay()->toDateString()]);

            //clases en transcurso = 2
            factory(\App\Clase::class)->create(['fecha' => Carbon::today()->toDateString(), 'desde' => Carbon::now()->subMinutes(10)->toTimeString(), 'hasta' => Carbon::now()->addHour()->toTimeString()]);
            factory(\App\Clase::class)->create(['fecha' => Carbon::today()->toDateString(), 'desde' => Carbon::now()->addMinutes(10)->toTimeString(), 'hasta' => Carbon::now()->addHour()->toTimeString()]);
            factory(\App\Clase::class)->create(['fecha' => Carbon::today()->toDateString(), 'desde' => Carbon::now()->subMinutes(10)->toTimeString(), 'hasta' => Carbon::now()->addHour()->toTimeString()]);





        });
    }
}
