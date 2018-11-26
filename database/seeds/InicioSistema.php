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
            $this->call(DiasSeeder::class);
            $this->call(DescuentoSeeder::class);
            $this->call(ProductoSeeder::class);
            $this->call(ProfesoresSeeder::class);
            $this->call(ServicioSeeder::class);
            $this->call(MembresiaSeeder::class);
            $this->call(SocioSeeder::class);
        });
    }
}
