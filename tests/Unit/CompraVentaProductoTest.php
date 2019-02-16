<?php

namespace Tests\Unit;

use App\Producto;
use App\services\ProductosService;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CompraVentaProductoTest extends TestCase
{
    use DatabaseMigrations;

    private $service;
    public function setUp()
    {
        parent::setUp();
        $this->service = new ProductosService();
        $this->artisan('migrate', ['--database' => 'mysql_testing']);

    }

    public function testComprarProductos()
    {
        factory(Producto::class, 3)->create(['cantidad' => 20, 'precio_compra' => 10]);
        $data = ['observacion' => null, 'tipoPago' => 'Efectivo', 'idSocio' => null, 'productos' => [['id' => 1, 'cantidad' => 5], ['id' => 2, 'cantidad' => 10]]];

        $this->service->comprar($data, 1);

        $this->assertDatabaseHas('registro_stock', ['id_producto' => 1, 'cantidad' => 5, 'precio' => 10, 'tipo' => 'Compra']);
        $this->assertDatabaseHas('registro_stock', ['id_producto' => 2, 'cantidad' => 10, 'precio' => 10, 'tipo' => 'Compra']);
        $this->assertDatabaseHas('productos', ['id' => 1, 'precio_compra' => 10, 'cantidad' => 25]);
        $this->assertDatabaseHas('productos', ['id' => 2, 'precio_compra' => 10, 'cantidad' => 30]);
        $this->assertDatabaseHas('movimientos', ['ingreso' => 0, 'egreso' => 50, 'observacion' => null, 'tipo_pago' => 'Efectivo', 'fecha' => Carbon::today()->toDateString()]);
        $this->assertDatabaseHas('movimientos', ['ingreso' => 0, 'egreso' => 100, 'observacion' => null, 'tipo_pago' => 'Efectivo', 'fecha' => Carbon::today()->toDateString()]);

    }

    public function testVenderProductos()
    {
        factory(Producto::class, 3)->create(['cantidad' => 20, 'precio_venta' => 10]);
        $data = ['observacion' => null, 'tipoPago' => 'Efectivo', 'idSocio' => 1, 'productos' => [['id' => 1, 'cantidad' => 5], ['id' => 2, 'cantidad' => 10]]];

        $this->service->vender($data, 1);

        $this->assertDatabaseHas('registro_stock', ['id_producto' => 1, 'cantidad' => 5, 'precio' => 10, 'tipo' => 'Venta', 'id_usuario' => 1]);
        $this->assertDatabaseHas('registro_stock', ['id_producto' => 2, 'cantidad' => 10, 'precio' => 10, 'tipo' => 'Venta', 'id_usuario' => 1]);
        $this->assertDatabaseHas('productos', ['id' => 1, 'precio_venta' => 10, 'cantidad' => 15]);
        $this->assertDatabaseHas('productos', ['id' => 2, 'precio_venta' => 10, 'cantidad' => 10]);
        $this->assertDatabaseHas('movimientos', ['ingreso' => 50, 'egreso' => 0, 'observacion' => null, 'tipo_pago' => 'Efectivo', 'fecha' => Carbon::today()->toDateString()]);
        $this->assertDatabaseHas('movimientos', ['ingreso' => 100, 'egreso' => 0, 'observacion' => null, 'tipo_pago' => 'Efectivo', 'fecha' => Carbon::today()->toDateString()]);
    }

    public function testTraerRegistrosDeStock()
    {
        factory(Producto::class, 3)->create(['cantidad' => 20]);
        $data = ['observacion' => null, 'tipoPago' => 'Efectivo', 'idSocio' => null, 'productos' => [['id' => 1, 'cantidad' => 5, 'precio' => 10], ['id' => 2, 'cantidad' => 10, 'precio' => 30]]];

        $this->service->vender($data, 1);

        factory(Producto::class, 3)->create(['cantidad' => 20]);
        $data = ['observacion' => null, 'tipoPago' => 'Efectivo', 'idSocio' => null, 'productos' => [['id' => 1, 'cantidad' => 5, 'precio' => 10], ['id' => 2, 'cantidad' => 10, 'precio' => 30]]];

        $this->service->comprar($data, 1);

        $data = ['fechaInicio' => Carbon::today()->subDay()->toDateString(), 'fechaFin' => Carbon::today()->addDay()->toDateString()];
        $registros = $this->service->registrosDeStock($data);
        $this->assertEquals(4, $registros->count());
    }
}
