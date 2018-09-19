<?php

namespace Tests\Unit;

use App\services\SocioService;
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
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->service = new SocioService();

    }

    public function testAltaSocio()
    {
        $data = ['nombre' => 'pepe', 'apellido' => 'argento', 'celular' => '15-46727473', 'domicilio' => 'tres arroyos 3684', 'dni' => 39624527, 'fecha_nacimiento' => '2017-02-04', 'id_descuento' => null];
        $data2 = ['nombre' => 'pepe', 'apellido' => 'argento', 'celular' => '15-46727473', 'domicilio' => 'tres arroyos 3684', 'dni' => 39624527, 'fecha_nacimiento' => '2017-02-04', 'id_descuento' => 1];
        $this->service->crear($data);
        $this->service->crear($data2);

        $this->assertDatabaseHas('socios', $data);
        $this->assertDatabaseHas('socios', $data2);
    }

    public function testUpdateSocio()
    {
        factory(\App\Socio::class)->create();
        $data = ['nombre' => 'pepe', 'apellido' => 'argento', 'celular' => '15-46727473', 'domicilio' => 'tres arroyos 3684', 'dni' => 39624527, 'fecha_nacimiento' => '2017-02-04', 'id_descuento'];
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

    public function testComprar()
    {
        factory(\App\Socio::class)->create();
        $clase = factory(\App\Clase::class)->create();
        $articulo = factory(\App\Articulo::class)->create();

        $data = ['idVendible' => 1, 'idSocio' => 1, 'cantidad' => 1, 'tipoPago' => 'Efectivo'];
        $data2 = ['idVendible' => 2, 'idSocio' => 1, 'cantidad' => 3, 'tipoPago' => 'Efectivo'];

        $this->service->comprar($data);
        $this->service->comprar($data2);

        $this->assertDatabaseHas('movimientos', ['ingreso' => $clase->precio, 'concepto' => $clase->nombre, 'observacion' => null, 'tipo_pago' => 'Efectivo']);
        $this->assertDatabaseHas('movimientos', ['ingreso' => $articulo->precio * 3, 'concepto' => $articulo->nombre, 'observacion' => null, 'tipo_pago' => 'Efectivo']);
        $this->assertDatabaseHas('ventas', ['fecha' => Carbon::today()->toDateString(), 'precio' => $clase->precio, 'id_vendible' => $clase->id, 'id_socio' => 1, 'cantidad' => 1]);
        $this->assertDatabaseHas('ventas', ['fecha' => Carbon::today()->toDateString(), 'precio' => $articulo->precio, 'id_vendible' => $articulo->id, 'id_socio' => 1, 'cantidad' => 3]);
    }
}
