<?php

namespace Tests\Unit;

use App\services\ServicioService;
use Illuminate\Support\Facades\Artisan;
use SeguridadSeeder;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ServicioServiceTest extends TestCase
{

    use DatabaseMigrations;

    private $service;
    public function setUp()
    {
        parent::setUp();
        $this->service = new ServicioService();
        $this->artisan('migrate', ['--database' => 'mysql_testing']);
        $this->artisan('db:seed', ['--class' => 'DiasSeeder', '--database' => 'mysql_testing']);

    }

    public function testCrearDescuento()
    {

        $data = ['nombre' => 'prueba', 'creditos_minimos' => 1, 'dias' => [['id' => 1, 'desde' => '17:00:00', 'hasta' => '18:00:00', 'entrada_desde' => '16:45:00', 'entrada_hasta' => '17:15:00'], ['id' => 2, 'desde' => '17:00:00', 'hasta' => '18:00:00', 'entrada_desde' => '16:45:00', 'entrada_hasta' => '17:15:00']]];
        $this->service->crear($data);
        unset($data['dias']);
        $this->assertDatabaseHas('servicios', $data);
        $this->assertDatabaseHas('servicio_dia', ['id_servicio' => 1, 'id_dia' => 1, 'desde' => '17:00:00', 'hasta' => '18:00:00', 'entrada_desde' => '16:45:00', 'entrada_hasta' => '17:15:00']);
        $this->assertDatabaseHas('servicio_dia', ['id_servicio' => 1, 'id_dia' => 2, 'desde' => '17:00:00', 'hasta' => '18:00:00', 'entrada_desde' => '16:45:00', 'entrada_hasta' => '17:15:00']);
    }

    public function testUpdateDescuento()
    {

        $data = ['id' => 1, 'nombre' => 'prueba2', 'creditos_minimos' => 2, 'dias' => [['id' => 1, 'desde' => '17:00:00', 'hasta' => '18:00:00', 'entrada_desde' => '16:45:00', 'entrada_hasta' => '17:15:00'], ['id' => 2, 'desde' => '17:00:00', 'hasta' => '18:00:00', 'entrada_desde' => '16:45:00', 'entrada_hasta' => '17:15:00']]];
        factory(\App\Servicio::class, 3)->create()->each(function($ser){
            $ser->dias()->attach([
                1 => ['desde' => '17:00:00', 'hasta' => '18:00:00', 'entrada_desde' => '16:45:00', 'entrada_hasta' => '17:15:00']
            ]);
        });

        $this->service->update($data, $data['id']);
        unset($data['dias']);
        $this->assertDatabaseHas('servicios', $data);
        $this->assertDatabaseHas('servicio_dia', ['id_servicio' => 1, 'id_dia' => 1, 'desde' => '17:00:00', 'hasta' => '18:00:00', 'entrada_desde' => '16:45:00', 'entrada_hasta' => '17:15:00']);
        $this->assertDatabaseHas('servicio_dia', ['id_servicio' => 1, 'id_dia' => 2, 'desde' => '17:00:00', 'hasta' => '18:00:00', 'entrada_desde' => '16:45:00', 'entrada_hasta' => '17:15:00']);
    }

    public function testFindDescuento()
    {
        factory(\App\Servicio::class, 3)->create()->each(function($ser){
            $ser->dias()->attach([
                1 => ['desde' => '17:00:00', 'hasta' => '18:00:00', 'entrada_desde' => '16:45:00', 'entrada_hasta' => '17:15:00'],
                2 => ['desde' => '17:00:00', 'hasta' => '18:00:00', 'entrada_desde' => '16:45:00', 'entrada_hasta' => '17:15:00']
            ]);
        });


        $servicio = $this->service->find(1);
        $this->assertEquals(false, is_null($servicio));
    }

    public function testDelete()
    {
        factory(\App\Servicio::class, 3)->create()->each(function($ser){
            $ser->dias()->attach([
                1 => ['desde' => '17:00:00', 'hasta' => '18:00:00', 'entrada_desde' => '16:45:00', 'entrada_hasta' => '17:15:00'],
                2 => ['desde' => '17:00:00', 'hasta' => '18:00:00', 'entrada_desde' => '16:45:00', 'entrada_hasta' => '17:15:00']
            ]);
        });


        $this->service->delete(1);
        $this->assertSoftDeleted('servicios', ['id' => 1]);
    }

    public function testTraerDescuentos()
    {
        factory(\App\Servicio::class, 3)->create()->each(function($ser){
            $ser->dias()->attach([
                1 => ['desde' => '17:00:00', 'hasta' => '18:00:00', 'entrada_desde' => '16:45:00', 'entrada_hasta' => '17:15:00'],
                2 => ['desde' => '17:00:00', 'hasta' => '18:00:00', 'entrada_desde' => '16:45:00', 'entrada_hasta' => '17:15:00']
            ]);
        });
        $servicios = $this->service->servicios();
        $this->assertEquals(3, $servicios->count());
    }
}
