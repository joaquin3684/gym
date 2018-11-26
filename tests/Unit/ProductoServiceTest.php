<?php

namespace Tests\Unit;

use App\Producto;
use App\services\ProductosService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProductoServiceTest extends TestCase
{
    use DatabaseMigrations;

    private $service;
    public function setUp()
    {
        parent::setUp();
        $this->service = new ProductosService();
        $this->artisan('migrate', ['--database' => 'mysql_testing']);

    }

    public function testCrearProducto()
    {
        $data = factory(Producto::class)->make()->toArray();
        $this->service->crear($data);
        $this->assertDatabaseHas('productos', $data);
    }

    public function testUpdateProducto()
    {

        $data = factory(Producto::class)->make(['precio_venta' => 150, 'precio_compra' => 100, 'punto_reposicion' => 45, 'cantidad' => 72])->toArray();
        factory(\App\Producto::class, 3)->create();

        $this->service->update($data, 1);
        $this->assertDatabaseHas('productos', $data);
    }

    public function testFindProducto()
    {
        factory(\App\Producto::class, 3)->create();


        $producto = $this->service->find(1);
        $this->assertEquals(false, is_null($producto));
    }

    public function testDeleteProductos()
    {
        factory(\App\Producto::class, 3)->create();

        $this->service->delete(1);
        $this->assertSoftDeleted('productos', ['id' => 1]);
    }

    public function testTraerProductos()
    {
        factory(\App\Producto::class, 3)->create();
        $productos = $this->service->all();
        $this->assertEquals(3, $productos->count());
    }
}
