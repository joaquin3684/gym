<?php

namespace Tests\Unit;

use App\services\MembresiaService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MembresiaServiceTest extends TestCase
{
    use DatabaseMigrations;

    private $service;
    public function setUp()
    {
        parent::setUp();
        $this->service = new MembresiaService();
        $this->artisan('migrate', ['--database' => 'mysql_testing']);
        $this->artisan('db:seed', ['--class' => 'DiasSeeder', '--database' => 'mysql_testing']);
        factory(\App\Descuento::class, 3)->create();
        factory(\App\Servicio::class, 3)->create()->each(function($ser) {
            return $ser->dias()->attach([
                1 => ['desde' => '17:00:00', 'hasta' => '20:00:00'],
                2 => ['desde' => '17:00:00', 'hasta' => '20:00:00'],

            ]);
        });
    }

    public function testAltaMembresia()
    {

        $data = ['precio' => 50, 'nombre' => 'combio piola', 'vencimiento_dias' => 30, 'nro_cuotas' => 2, 'servicios' => [['id' => 1, 'cantidadCreditos' => 10, 'vto' => 10], ['id' => 2, 'cantidadCreditos' => 20, 'vto' => 20]], 'descuentos' => [1, 2]];
        $this->service->crear($data);

        unset($data['servicios']);
        unset($data['descuentos']);

        $this->assertDatabaseHas('membresias', $data);
        $this->assertDatabaseHas('membresia_servicio', ['id_membresia' => 1, 'id_servicio' => 1, 'creditos' => 10]);
        $this->assertDatabaseHas('membresia_servicio', ['id_membresia' => 1, 'id_servicio' => 2, 'creditos' => 20]);
        $this->assertDatabaseHas('membresia_descuento', ['id_membresia' => 1, 'id_descuento' => 1]);
        $this->assertDatabaseHas('membresia_descuento', ['id_membresia' => 1, 'id_descuento' => 2]);
    }

    public function testUpdateMembresia()
    {
        $membresia = factory(\App\Membresia::class)->create();
        $membresia->descuentos()->attach([1,2]);
        $membresia->servicios()->attach([
            1 => ['creditos' => 10],
            2 => ['creditos' => 10]
        ]);

        $data = ['id' => 1, 'precio' => 50, 'nombre' => 'combio piola', 'vencimiento_dias' => 30, 'nro_cuotas' => 2, 'servicios' => [['id' => 1, 'cantidadCreditos' => 10], ['id' => 2, 'cantidadCreditos' => 20], ['id' => 3, 'cantidadCreditos' => 20]], 'descuentos' => [1, 2, 3]];
        $this->service->update($data, 1);
        unset($data['servicios']);
        unset($data['descuentos']);

        $this->assertDatabaseHas('membresias', $data);
        $this->assertDatabaseHas('membresia_servicio', ['id_membresia' => 1, 'id_servicio' => 1, 'creditos' => 10]);
        $this->assertDatabaseHas('membresia_servicio', ['id_membresia' => 1, 'id_servicio' => 2, 'creditos' => 20]);
        $this->assertDatabaseHas('membresia_servicio', ['id_membresia' => 1, 'id_servicio' => 3, 'creditos' => 20]);
        $this->assertDatabaseHas('membresia_descuento', ['id_membresia' => 1, 'id_descuento' => 1]);
        $this->assertDatabaseHas('membresia_descuento', ['id_membresia' => 1, 'id_descuento' => 2]);
        $this->assertDatabaseHas('membresia_descuento', ['id_membresia' => 1, 'id_descuento' => 3]);
    }

    public function testFindMembresia()
    {
        $membresia = factory(\App\Membresia::class)->create();
        $membresia->descuentos()->attach([1,2]);
        $membresia->servicios()->attach([
            1 => ['creditos' => 10],
            2 => ['creditos' => 10]
        ]);

        $membresia = $this->service->find(1);

        $this->assertEquals(false, is_null($membresia));
    }

    public function testAll()
    {
        factory(\App\Membresia::class, 3)->create()->each(function($membresia){
            $membresia->descuentos()->attach([1,2]);
            $membresia->servicios()->attach([
                1 => ['creditos' => 10],
                2 => ['creditos' => 10]
            ]);
        });

        $membresias = $this->service->membresias();
        $this->assertEquals($membresias->count(), 3);
    }

    public function testTraerMembresiasConTodo()
    {
        factory(\App\Membresia::class, 3)->create()->each(function($membresia){
            $membresia->descuentos()->attach([1,2]);
            $membresia->servicios()->attach([
                1 => ['creditos' => 10],
                2 => ['creditos' => 10]
            ]);
        });

        $membresias = $this->service->membresiasConTodo();
        $this->assertEquals($membresias->count(), 3);
    }


    public function testDelete()
    {
        factory(\App\Membresia::class, 3)->create()->each(function($membresia){
            $membresia->descuentos()->attach([1,2]);
            $membresia->servicios()->attach([
                1 => ['creditos' => 10],
                2 => ['creditos' => 10]
            ]);
        });

        $this->service->delete(1);
        $this->assertSoftDeleted('membresias', ['id' => 1]);
    }
}
