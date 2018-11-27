<?php

namespace Tests\Unit;

use App\Clase;
use App\Cuota;
use App\services\SocioService;
use App\ServicioProfesorDia;
use App\Socio;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SociosServiceTest extends TestCase
{
    use DatabaseMigrations;

    private $service;
    public function setUp()
    {
        parent::setUp();
        $this->service = new SocioService();
        $this->artisan('migrate', ['--database' => 'mysql_testing']);
        $this->artisan('db:seed', ['--class' => 'MembresiaSeeder', '--database' => 'mysql_testing']);

    }

    public function testAltaSocio()
    {
        $data = ['nombre' => 'pepe', 'apellido' => 'argento', 'celular' => '15-46727473', 'domicilio' => 'tres arroyos 3684', 'dni' => 39624527, 'fecha_nacimiento' => '2017-02-04', 'id_descuento' => null, 'nro_socio' => '1'];
        $data2 = ['nombre' => 'pepe', 'apellido' => 'argento', 'celular' => '15-46727473', 'domicilio' => 'tres arroyos 3684', 'dni' => 39624527, 'fecha_nacimiento' => '2017-02-04', 'id_descuento' => 1, 'nro_socio' => '2'];
        $this->service->crear($data);
        $this->service->crear($data2);

        $this->assertDatabaseHas('socios', $data);
        $this->assertDatabaseHas('socios', $data2);
    }

    public function testUpdateSocio()
    {
        factory(\App\Socio::class)->create();
        $data = ['nombre' => 'pepe', 'apellido' => 'argento', 'celular' => '15-46727473', 'domicilio' => 'tres arroyos 3684', 'dni' => 39624527, 'fecha_nacimiento' => '2017-02-04', 'id_descuento' => null, 'nro_socio' => 2];
        $this->service->update($data, 1);
        $this->assertDatabaseHas('socios', $data);

    }

    public function testFindSocio()
    {
        factory(\App\Socio::class)->create();
        $socio = $this->service->find(1);

        $this->assertEquals(false, is_null($socio));
    }

    public function testAll()
    {
        factory(\App\Socio::class)->create();
        factory(\App\Socio::class)->create();

        $socios = $this->service->all();
        $this->assertEquals($socios->count(), 2);
    }


    public function testPrueba()
    {


        $spd = ServicioProfesorDia::with('servicio', 'profesor', 'dia')->get();
        $serv = $spd->map(function($s){
            $ser = $s->servicio;
            return $ser;
        })->unique(function($serv){
            return $serv->id;
        });

        $serv = $serv->map(function($ser) use ($spd){
            $filtroPorElServicioActual = $spd->filter(function($s) use ($ser){return $s->id_servicio == $ser->id;});
            $ser->dias = $filtroPorElServicioActual->map(function($s) use ($spd){


                $s->profesores = $filtroPorDiaYHoraYservicio = $spd->filter(function($s2) use ($s){
                    return $s2->id_servicio == $s->id_servicio && $s2->id_dia == $s->id_dia && $s2->desde == $s->desde && $s2->hasta == $s->hasta && $s2->entrada_desde == $s->entrada_desde && $s2->entrada_hasta == $s->entrada_hasta;
                })->map(function($s){ return $s->profesor;});

                return $s;
            })->unique(function($s){ $s->id_servicio.$s->id_dia.$s->desde.$s->hasta.$s->entrada_desde.$s->entrada_hasta;});

            return $ser;
        });


        $serv->each(function($servicio){
            $servicio->dias->each(function($dia) use ($servicio){
                Clase::create(['fecha' => Carbon::today()->toDateString(), 'dia' => $dia->id_dia, 'id_servicio' => $servicio->id, 'estado' => 1, 'desde' => $dia->desde, 'hasta' => $dia->hasta, 'entrada_desde' => $dia->entrada_desde, 'entrada_hasta' => $dia->entrada_hasta, 'id_dia' => $dia->id_dia]);

            });

        });

        return 1;
    }


}
