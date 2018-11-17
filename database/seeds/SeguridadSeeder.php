<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SeguridadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Db::transaction(function(){



        $ruta = factory(App\Ruta::class)->create(['ruta' => 'vendibles/clases']);
        $ruta2 = factory(App\Ruta::class)->create(['ruta' => 'vendibles/articulos']);

        $perfil = factory(App\Perfil::class)->create(['nombre' => 'admin']);

        $pantalla = factory(App\Pantalla::class)->create(['nombre' => 'socio']);
        $pantalla1 = factory(App\Pantalla::class)->create(['nombre' => 'servicio']);
        $pantalla2 = factory(App\Pantalla::class)->create(['nombre' => 'caja']);
        $pantalla3 = factory(App\Pantalla::class)->create(['nombre' => 'descuento']);
        $pantalla4 = factory(App\Pantalla::class)->create(['nombre' => 'ventas']);
        $pantalla5 = factory(App\Pantalla::class)->create(['nombre' => 'membresia']);
        $pantalla6 = factory(App\Pantalla::class)->create(['nombre' => 'profesor']);


        $user = factory(App\User::class)->create(['id_perfil' => 1, 'name' => 'prueba', 'password' => Hash::make('prueba')]);

       // $user->perfil()->attach($perfil->id);
        $perfil->pantallas()->attach($pantalla->id);
        $perfil->pantallas()->attach($pantalla1->id);
        $perfil->pantallas()->attach($pantalla2->id);
        $perfil->pantallas()->attach($pantalla3->id);
        $perfil->pantallas()->attach($pantalla4->id);
        $perfil->pantallas()->attach($pantalla5->id);
        $perfil->pantallas()->attach($pantalla6->id);

        $pantalla->rutas()->attach($ruta->id);
        $pantalla->rutas()->attach($ruta2->id);

        });

    }
}
