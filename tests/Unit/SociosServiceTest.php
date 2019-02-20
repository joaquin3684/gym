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




}
