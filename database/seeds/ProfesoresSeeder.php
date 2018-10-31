<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfesoresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Db::transaction(function(){
            $profesor = factory(\App\Profesor::class)->create();
            $profesor2 = factory(\App\Profesor::class)->create();
        });
    }
}
