<?php

namespace Tests\Unit;

use App\Descuento;
use App\services\DescuentoService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DescuentoServiceTest extends TestCase
{
    use DatabaseMigrations;

    private $service;
    public function setUp()
    {
        parent::setUp();
        $this->service = new DescuentoService();

    }

    public function testCrearDescuento()
    {

        $data = ['nombre' => 'prueba', 'porcentaje' => 50, 'vencimiento_dias' => 30, 'aplicable_enconjunto' => true, 'tipo' => 1];
        $this->service->crear($data);
        $this->assertDatabaseHas('descuentos', $data);
    }

    public function testUpdateDescuento()
    {
        factory(\App\Descuento::class)->create();
        $data = ['nombre' => 'prueba', 'porcentaje' => 50, 'vencimiento_dias' => 30, 'aplicable_enconjunto' => true, 'id' => 1, 'tipo' => 1];

        $this->service->update($data, $data['id']);
        $this->assertDatabaseHas('descuentos', $data);

    }

    public function testFindDescuento()
    {
        factory(\App\Descuento::class)->create();

        $descuentoBusqueda = $this->service->find(1);
        $this->assertEquals(false, is_null($descuentoBusqueda));
    }
    
    public function testDelete()
    {
        factory(\App\Descuento::class)->create();
        $this->service->delete(1);
        $this->assertSoftDeleted('descuentos', ['id' => 1]);
    }

    public function testTraerDescuentos()
    {
        factory(\App\Descuento::class, 3)->create();
        $descuentos = $this->service->descuentos();
        $this->assertEquals(3, $descuentos->count());
    }

}
