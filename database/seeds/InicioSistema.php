<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InicioSistema extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Db::transaction(function() {
            $this->call(SeguridadSeeder::class);
            $this->call(ProductoSeeder::class);
            $this->call(SocioSeeder::class);
        });
    }
}
