<?php

namespace Tests\Unit;

use App\Cuota;
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
        parent::setUp();
        $this->service = new SocioService();
        $this->artisan('migrate', ['--database' => 'mysql_testing']);
        $this->artisan('db:seed', ['--class' => 'MembresiaSeeder', '--database' => 'mysql_testing']);

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
        $data = ['nombre' => 'pepe', 'apellido' => 'argento', 'celular' => '15-46727473', 'domicilio' => 'tres arroyos 3684', 'dni' => 39624527, 'fecha_nacimiento' => '2017-02-04', 'id_descuento' => null];
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

    public function testComprarUnaMembresiaDeUnPagoSinDescuentoDeSocioYSinDescuentoDeMembresia()
    {
        factory(\App\Socio::class)->create(['id_descuento' => null]);

        $data = ['idSocio' => 1, 'tipoPago' => 'Efectivo', 'observacion' => null, 'membresias' => [['id' => 1, 'cantidad' => 1, 'idDescuento' => null]]];

        $this->service->comprar($data);

        $this->assertDatabaseHas('movimientos', ['ingreso' => 100, 'concepto' => 'membresia 1', 'observacion' => null, 'tipo_pago' => 'Efectivo']);
        $this->assertDatabaseHas('ventas', ['fecha' => Carbon::today()->toDateString(), 'precio' => 100, 'id_membresia' => 1, 'id_socio' => 1, 'cantidad' => 1, 'id_descuento' => null]);
        $this->assertDatabaseHas('socio_membresia', ['id_socio' => 1, 'id_membresia' => 1, 'vto' => Carbon::today()->addDays(30)->toDateString()]);
        $this->assertDatabaseHas('socio_servicio', ['vto' => Carbon::today()->addDays(30)->toDateString(), 'id_servicio' => 1, 'id_socio' => 1, 'creditos' => 10]);
        $this->assertDatabaseHas('socio_servicio', ['vto' => Carbon::today()->addDays(30)->toDateString(), 'id_servicio' => 2, 'id_socio' => 1, 'creditos' => 5]);
        $this->assertDatabaseHas('cuotas', ['id_membresia' => 1, 'id_socio' => 1, 'pagada' => 1, 'pago' => 100, 'fecha_inicio' => Carbon::today()->toDateString(), 'fecha_vto' => Carbon::today()->addDays(30)->toDateString(), 'nro_cuota' => 1]);
    }

    public function testComprarUnaMembresiaDeUnPagoSinDescuentoDeSocioConDescuentoDeMembresia()
    {
        factory(\App\Socio::class)->create(['id_descuento' => null]);
        $data = ['idSocio' => 1, 'tipoPago' => 'Efectivo', 'observacion' => null, 'membresias' => [['id' => 1, 'cantidad' => 1, 'idDescuento' => 3]]];

        $this->service->comprar($data);

        $this->assertDatabaseHas('movimientos', ['ingreso' => 50, 'concepto' => 'membresia 1', 'observacion' => null, 'tipo_pago' => 'Efectivo']);
        $this->assertDatabaseHas('ventas', ['fecha' => Carbon::today()->toDateString(), 'precio' => 50, 'id_membresia' => 1, 'id_socio' => 1, 'cantidad' => 1, 'id_descuento' => 3]);
        $this->assertDatabaseHas('socio_membresia', ['id_socio' => 1, 'id_membresia' => 1, 'vto' => Carbon::today()->addDays(30)->toDateString()]);
        $this->assertDatabaseHas('socio_servicio', ['vto' => Carbon::today()->addDays(30)->toDateString(), 'id_servicio' => 1, 'id_socio' => 1, 'creditos' => 10]);
        $this->assertDatabaseHas('socio_servicio', ['vto' => Carbon::today()->addDays(30)->toDateString(), 'id_servicio' => 2, 'id_socio' => 1, 'creditos' => 5]);
        $this->assertDatabaseHas('cuotas', ['id_membresia' => 1, 'id_socio' => 1, 'pagada' => 1, 'pago' => 50, 'fecha_inicio' => Carbon::today()->toDateString(), 'fecha_vto' => Carbon::today()->addDays(30)->toDateString(), 'nro_cuota' => 1]);

    }

    public function testComprarUnaMembresiaDeUnPagoConUnSocioConDescuentoAsignadoPeroParaOtraMembresia()
    {
        factory(\App\Socio::class)->create(['id_descuento' => 1]);
        $data = ['idSocio' => 1, 'tipoPago' => 'Efectivo', 'observacion' => null, 'membresias' => [['id' => 1, 'cantidad' => 1, 'idDescuento' => null]]];

        $this->service->comprar($data);

        $this->assertDatabaseHas('movimientos', ['ingreso' => 100, 'concepto' => 'membresia 1', 'observacion' => null, 'tipo_pago' => 'Efectivo']);
        $this->assertDatabaseHas('ventas', ['fecha' => Carbon::today()->toDateString(), 'precio' => 100, 'id_membresia' => 1, 'id_socio' => 1, 'cantidad' => 1, 'id_descuento' => null]);
        $this->assertDatabaseHas('socio_membresia', ['id_socio' => 1, 'id_membresia' => 1, 'vto' => Carbon::today()->addDays(30)->toDateString()]);
        $this->assertDatabaseHas('socio_servicio', ['vto' => Carbon::today()->addDays(30)->toDateString(), 'id_servicio' => 1, 'id_socio' => 1, 'creditos' => 10]);
        $this->assertDatabaseHas('socio_servicio', ['vto' => Carbon::today()->addDays(30)->toDateString(), 'id_servicio' => 2, 'id_socio' => 1, 'creditos' => 5]);
        $this->assertDatabaseHas('cuotas', ['id_membresia' => 1, 'id_socio' => 1, 'pagada' => 1, 'pago' => 100, 'fecha_inicio' => Carbon::today()->toDateString(), 'fecha_vto' => Carbon::today()->addDays(30)->toDateString(), 'nro_cuota' => 1]);

    }

    public function testComprarUnaMembresiaDeUnPagoConUnSocioConDescuentoAsignadoParaEsaMembresia()
    {
        factory(\App\Socio::class)->create(['id_descuento' => 3]);
        $data = ['idSocio' => 1, 'tipoPago' => 'Efectivo', 'observacion' => null, 'membresias' => [['id' => 1, 'cantidad' => 1, 'idDescuento' => null]]];

        $this->service->comprar($data);

        $this->assertDatabaseHas('movimientos', ['ingreso' => 50, 'concepto' => 'membresia 1', 'observacion' => null, 'tipo_pago' => 'Efectivo']);
        $this->assertDatabaseHas('ventas', ['fecha' => Carbon::today()->toDateString(), 'precio' => 50, 'id_membresia' => 1, 'id_socio' => 1, 'cantidad' => 1, 'id_descuento' => null]);
        $this->assertDatabaseHas('socio_membresia', ['id_socio' => 1, 'id_membresia' => 1, 'vto' => Carbon::today()->addDays(30)->toDateString()]);
        $this->assertDatabaseHas('socio_servicio', ['vto' => Carbon::today()->addDays(30)->toDateString(), 'id_servicio' => 1, 'id_socio' => 1, 'creditos' => 10]);
        $this->assertDatabaseHas('socio_servicio', ['vto' => Carbon::today()->addDays(30)->toDateString(), 'id_servicio' => 2, 'id_socio' => 1, 'creditos' => 5]);
        $this->assertDatabaseHas('cuotas', ['id_membresia' => 1, 'id_socio' => 1, 'pagada' => 1, 'pago' => 50, 'fecha_inicio' => Carbon::today()->toDateString(), 'fecha_vto' => Carbon::today()->addDays(30)->toDateString(), 'nro_cuota' => 1]);

    }

    public function testComprarUnaMembresiaDeUnPagoConUnSocioConDescuentoAsignadoPeroNoParaEsaMembresiaYConUnDescuentoDeMembresiaConAplicableEnConjunto()
    {
        factory(\App\Socio::class)->create(['id_descuento' => 1]);
        $data = ['idSocio' => 1, 'tipoPago' => 'Efectivo', 'observacion' => null, 'membresias' => [['id' => 2, 'cantidad' => 1, 'idDescuento' => 3]]];

        $this->service->comprar($data);

        $this->assertDatabaseHas('movimientos', ['ingreso' => 37.5, 'concepto' => 'membresia 2', 'observacion' => null, 'tipo_pago' => 'Efectivo']);
        $this->assertDatabaseHas('ventas', ['fecha' => Carbon::today()->toDateString(), 'precio' => 75, 'id_membresia' => 2, 'id_socio' => 1, 'cantidad' => 1, 'id_descuento' => 3]);
        $this->assertDatabaseHas('socio_membresia', ['id_socio' => 1, 'id_membresia' => 2, 'vto' => Carbon::today()->addDays(90)->toDateString()]);
        $this->assertDatabaseHas('socio_servicio', ['vto' => Carbon::today()->addDays(30)->toDateString(), 'id_servicio' => 1, 'id_socio' => 1, 'creditos' => 10]);
        $this->assertDatabaseHas('socio_servicio', ['vto' => Carbon::today()->addDays(50)->toDateString(), 'id_servicio' => 2, 'id_socio' => 1, 'creditos' => 5]);
        $this->assertDatabaseHas('cuotas', ['id_membresia' => 2, 'id_socio' => 1, 'pagada' => 1, 'pago' => 37.5, 'fecha_inicio' => Carbon::today()->toDateString(), 'fecha_vto' => Carbon::today()->addDays(30)->toDateString(), 'nro_cuota' => 1]);
        $this->assertDatabaseHas('cuotas', ['id_membresia' => 2, 'id_socio' => 1, 'pagada' => 0, 'pago' => 37.5, 'fecha_inicio' => Carbon::today()->addDays(30)->toDateString(), 'fecha_vto' => Carbon::today()->addDays(90)->toDateString(), 'nro_cuota' => 2]);

    }

    public function testComprarUnaMembresiaDeUnPagoConUnSocioConDescuentoAsignadoPeroNoParaEsaMembresiaYConUnDescuentoDeMembresiaSinAplicableEnConjunto()
    {
        factory(\App\Socio::class)->create(['id_descuento' => 2]);
        $data = ['idSocio' => 1, 'tipoPago' => 'Efectivo', 'observacion' => null, 'membresias' => [['id' => 2, 'cantidad' => 1, 'idDescuento' => 4]]];

        $this->service->comprar($data);

        $this->assertDatabaseHas('movimientos', ['ingreso' => 0, 'concepto' => 'membresia 2', 'observacion' => null, 'tipo_pago' => 'Efectivo']);
        $this->assertDatabaseHas('ventas', ['fecha' => Carbon::today()->toDateString(), 'precio' => 0, 'id_membresia' => 2, 'id_socio' => 1, 'cantidad' => 1, 'id_descuento' => 4]);
        $this->assertDatabaseHas('socio_membresia', ['id_socio' => 1, 'id_membresia' => 2, 'vto' => Carbon::today()->addDays(90)->toDateString()]);
        $this->assertDatabaseHas('socio_servicio', ['vto' => Carbon::today()->addDays(30)->toDateString(), 'id_servicio' => 1, 'id_socio' => 1, 'creditos' => 10]);
        $this->assertDatabaseHas('socio_servicio', ['vto' => Carbon::today()->addDays(50)->toDateString(), 'id_servicio' => 2, 'id_socio' => 1, 'creditos' => 5]);
        $this->assertDatabaseHas('cuotas', ['id_membresia' => 2, 'id_socio' => 1, 'pagada' => 1, 'pago' => 0, 'fecha_inicio' => Carbon::today()->toDateString(), 'fecha_vto' => Carbon::today()->addDays(30)->toDateString(), 'nro_cuota' => 1]);
        $this->assertDatabaseHas('cuotas', ['id_membresia' => 2, 'id_socio' => 1, 'pagada' => 0, 'pago' => 0, 'fecha_inicio' => Carbon::today()->addDays(30)->toDateString(), 'fecha_vto' => Carbon::today()->addDays(90)->toDateString(), 'nro_cuota' => 2]);

    }

    public function testComprarUnaMembresiaDeUnPagoConUnSocioConDescuentoAsignadoParaEsaMembresiaYConUnDescuentoDeMembresia()
    {
        factory(\App\Socio::class)->create(['id_descuento' => 1]);
        $data = ['idSocio' => 1, 'tipoPago' => 'Efectivo', 'observacion' => null, 'membresias' => [['id' => 1, 'cantidad' => 1, 'idDescuento' => 3], ['id' => 1, 'cantidad' => 1, 'idDescuento' => 4]]];

        $this->service->comprar($data);

        $this->assertDatabaseHas('movimientos', ['ingreso' => 50, 'concepto' => 'membresia 1', 'observacion' => null, 'tipo_pago' => 'Efectivo']);
        $this->assertDatabaseHas('movimientos', ['ingreso' => 50, 'concepto' => 'membresia 1', 'observacion' => null, 'tipo_pago' => 'Efectivo']);
        $this->assertDatabaseHas('ventas', ['fecha' => Carbon::today()->toDateString(), 'precio' => 50, 'id_membresia' => 1, 'id_socio' => 1, 'cantidad' => 1, 'id_descuento' => 3]);
        $this->assertDatabaseHas('ventas', ['fecha' => Carbon::today()->toDateString(), 'precio' => 50, 'id_membresia' => 1, 'id_socio' => 1, 'cantidad' => 1, 'id_descuento' => 4]);
        $this->assertDatabaseHas('socio_membresia', ['id_socio' => 1, 'id_membresia' => 1, 'vto' => Carbon::today()->addDays(30)->toDateString()]);
        $this->assertDatabaseHas('socio_membresia', ['id_socio' => 1, 'id_membresia' => 1, 'vto' => Carbon::today()->addDays(30)->toDateString()]);
        $this->assertDatabaseHas('socio_servicio', ['vto' => Carbon::today()->addDays(30)->toDateString(), 'id_servicio' => 1, 'id_socio' => 1, 'creditos' => 10]);
        $this->assertDatabaseHas('socio_servicio', ['vto' => Carbon::today()->addDays(30)->toDateString(), 'id_servicio' => 1, 'id_socio' => 1, 'creditos' => 10]);
        $this->assertDatabaseHas('socio_servicio', ['vto' => Carbon::today()->addDays(30)->toDateString(), 'id_servicio' => 2, 'id_socio' => 1, 'creditos' => 5]);
        $this->assertDatabaseHas('socio_servicio', ['vto' => Carbon::today()->addDays(30)->toDateString(), 'id_servicio' => 2, 'id_socio' => 1, 'creditos' => 5]);
        $this->assertDatabaseHas('cuotas', ['id_membresia' => 1, 'id_socio' => 1, 'pagada' => 1, 'pago' => 50, 'fecha_inicio' => Carbon::today()->toDateString(), 'fecha_vto' => Carbon::today()->addDays(30)->toDateString(), 'nro_cuota' => 1]);
        $this->assertDatabaseHas('cuotas', ['id_membresia' => 1, 'id_socio' => 1, 'pagada' => 1, 'pago' => 50, 'fecha_inicio' => Carbon::today()->toDateString(), 'fecha_vto' => Carbon::today()->addDays(30)->toDateString(), 'nro_cuota' => 1]);
    }

    public function testComprarDosMembresiasDeUnPago()
    {
        factory(\App\Socio::class)->create(['id_descuento' => 1]);
        $data = ['idSocio' => 1, 'tipoPago' => 'Efectivo', 'observacion' => null, 'membresias' => [['id' => 1, 'cantidad' => 1, 'idDescuento' => 3], ['id' => 1, 'cantidad' => 1, 'idDescuento' => 4]]];

        $this->service->comprar($data);

        $this->assertDatabaseHas('movimientos', ['ingreso' => 50, 'concepto' => 'membresia 1', 'observacion' => null, 'tipo_pago' => 'Efectivo']);
        $this->assertDatabaseHas('movimientos', ['ingreso' => 50, 'concepto' => 'membresia 1', 'observacion' => null, 'tipo_pago' => 'Efectivo']);
        $this->assertDatabaseHas('ventas', ['fecha' => Carbon::today()->toDateString(), 'precio' => 50, 'id_membresia' => 1, 'id_socio' => 1, 'cantidad' => 1, 'id_descuento' => 3]);
        $this->assertDatabaseHas('ventas', ['fecha' => Carbon::today()->toDateString(), 'precio' => 50, 'id_membresia' => 1, 'id_socio' => 1, 'cantidad' => 1, 'id_descuento' => 4]);
        $this->assertDatabaseHas('socio_membresia', ['id_socio' => 1, 'id_membresia' => 1, 'vto' => Carbon::today()->addDays(30)->toDateString()]);
        $this->assertDatabaseHas('socio_membresia', ['id_socio' => 1, 'id_membresia' => 1, 'vto' => Carbon::today()->addDays(30)->toDateString()]);
        $this->assertDatabaseHas('socio_servicio', ['vto' => Carbon::today()->addDays(30)->toDateString(), 'id_servicio' => 1, 'id_socio' => 1, 'creditos' => 10]);
        $this->assertDatabaseHas('socio_servicio', ['vto' => Carbon::today()->addDays(30)->toDateString(), 'id_servicio' => 1, 'id_socio' => 1, 'creditos' => 10]);
        $this->assertDatabaseHas('socio_servicio', ['vto' => Carbon::today()->addDays(30)->toDateString(), 'id_servicio' => 2, 'id_socio' => 1, 'creditos' => 5]);
        $this->assertDatabaseHas('socio_servicio', ['vto' => Carbon::today()->addDays(30)->toDateString(), 'id_servicio' => 2, 'id_socio' => 1, 'creditos' => 5]);
        $this->assertDatabaseHas('cuotas', ['id_membresia' => 1, 'id_socio' => 1, 'pagada' => 1, 'pago' => 50, 'fecha_inicio' => Carbon::today()->toDateString(), 'fecha_vto' => Carbon::today()->addDays(30)->toDateString(), 'nro_cuota' => 1]);
        $this->assertDatabaseHas('cuotas', ['id_membresia' => 1, 'id_socio' => 1, 'pagada' => 1, 'pago' => 50, 'fecha_inicio' => Carbon::today()->toDateString(), 'fecha_vto' => Carbon::today()->addDays(30)->toDateString(), 'nro_cuota' => 1]);

    }

    public function testComprarMembresiaDeDosPagos()
    {
        factory(\App\Socio::class)->create(['id_descuento' => null]);
        $data = ['idSocio' => 1, 'tipoPago' => 'Efectivo', 'observacion' => null, 'membresias' => [['id' => 2, 'cantidad' => 1, 'idDescuento' => 3]]];

        $this->service->comprar($data);

        $this->assertDatabaseHas('movimientos', ['ingreso' => 75, 'concepto' => 'membresia 2', 'observacion' => null, 'tipo_pago' => 'Efectivo']);
        $this->assertDatabaseHas('ventas', ['fecha' => Carbon::today()->toDateString(), 'precio' => 150, 'id_membresia' => 2, 'id_socio' => 1, 'cantidad' => 1, 'id_descuento' => 3]);
        $this->assertDatabaseHas('socio_membresia', ['id_socio' => 1, 'id_membresia' => 2, 'vto' => Carbon::today()->addDays(90)->toDateString()]);
        $this->assertDatabaseHas('socio_servicio', ['vto' => Carbon::today()->addDays(30)->toDateString(), 'id_servicio' => 1, 'id_socio' => 1, 'creditos' => 10]);
        $this->assertDatabaseHas('socio_servicio', ['vto' => Carbon::today()->addDays(50)->toDateString(), 'id_servicio' => 2, 'id_socio' => 1, 'creditos' => 5]);
        $this->assertDatabaseHas('cuotas', ['id_membresia' => 2, 'id_socio' => 1, 'pagada' => 1, 'pago' => 75, 'fecha_inicio' => Carbon::today()->toDateString(), 'fecha_vto' => Carbon::today()->addDays(30)->toDateString(), 'nro_cuota' => 1]);
        $this->assertDatabaseHas('cuotas', ['id_membresia' => 2, 'id_socio' => 1, 'pagada' => 0, 'pago' => 75, 'fecha_inicio' => Carbon::today()->addDays(30)->toDateString(), 'fecha_vto' => Carbon::today()->addDays(90)->toDateString(), 'nro_cuota' => 2]);
    }

    public function testComprarMembresiaDeMasDe2Pagos()
    {
        factory(\App\Socio::class)->create(['id_descuento' => null]);
        $data = ['idSocio' => 1, 'tipoPago' => 'Efectivo', 'observacion' => null, 'membresias' => [['id' => 3, 'cantidad' => 1, 'idDescuento' => 3]]];

        $this->service->comprar($data);

        $this->assertDatabaseHas('movimientos', ['ingreso' => 100, 'concepto' => 'membresia 3', 'observacion' => null, 'tipo_pago' => 'Efectivo']);
        $this->assertDatabaseHas('ventas', ['fecha' => Carbon::today()->toDateString(), 'precio' => 300, 'id_membresia' => 3, 'id_socio' => 1, 'cantidad' => 1, 'id_descuento' => 3]);
        $this->assertDatabaseHas('socio_membresia', ['id_socio' => 1, 'id_membresia' => 3, 'vto' => Carbon::today()->addDays(120)->toDateString()]);
        $this->assertDatabaseHas('socio_servicio', ['vto' => Carbon::today()->addDays(30)->toDateString(), 'id_servicio' => 1, 'id_socio' => 1, 'creditos' => 10]);
        $this->assertDatabaseHas('socio_servicio', ['vto' => Carbon::today()->addDays(50)->toDateString(), 'id_servicio' => 2, 'id_socio' => 1, 'creditos' => 5]);
        $this->assertDatabaseHas('cuotas', ['id_membresia' => 3, 'id_socio' => 1, 'pagada' => 1, 'pago' => 100, 'fecha_inicio' => Carbon::today()->toDateString(), 'fecha_vto' => Carbon::today()->addDays(30)->toDateString(), 'nro_cuota' => 1]);
        $this->assertDatabaseHas('cuotas', ['id_membresia' => 3, 'id_socio' => 1, 'pagada' => 0, 'pago' => 100, 'fecha_inicio' => Carbon::today()->addDays(30)->toDateString(), 'fecha_vto' => Carbon::today()->addDays(60)->toDateString(), 'nro_cuota' => 2]);
        $this->assertDatabaseHas('cuotas', ['id_membresia' => 3, 'id_socio' => 1, 'pagada' => 0, 'pago' => 100, 'fecha_inicio' => Carbon::today()->addDays(60)->toDateString(), 'fecha_vto' => Carbon::today()->addDays(120)->toDateString(), 'nro_cuota' => 3]);
    }

    public function testComprarUnaMembresiaEnLaCualTengoCuotasPendientes()
    {
        factory(\App\Socio::class)->create(['id_descuento' => null]);
        $data = ['idSocio' => 1, 'tipoPago' => 'Efectivo', 'observacion' => null, 'membresias' => [['id' => 3, 'cantidad' => 1, 'idDescuento' => 3]]];

        $this->service->comprar($data);

        $this->service->comprar($data);

        $this->assertDatabaseHas('movimientos', ['ingreso' => 100, 'concepto' => 'membresia 3', 'observacion' => null, 'tipo_pago' => 'Efectivo']);
        $this->assertDatabaseHas('movimientos', ['ingreso' => 100, 'concepto' => 'membresia 3', 'observacion' => null, 'tipo_pago' => 'Efectivo']);
        $this->assertDatabaseHas('cuotas', ['id_membresia' => 3, 'id_socio' => 1, 'pagada' => 1, 'pago' => 100, 'fecha_inicio' => Carbon::today()->toDateString(), 'fecha_vto' => Carbon::today()->addDays(30)->toDateString(), 'nro_cuota' => 1]);
        $this->assertDatabaseHas('cuotas', ['id_membresia' => 3, 'id_socio' => 1, 'pagada' => 1, 'pago' => 100, 'fecha_inicio' => Carbon::today()->addDays(30)->toDateString(), 'fecha_vto' => Carbon::today()->addDays(60)->toDateString(), 'nro_cuota' => 2]);
        $this->assertDatabaseHas('cuotas', ['id_membresia' => 3, 'id_socio' => 1, 'pagada' => 0, 'pago' => 100, 'fecha_inicio' => Carbon::today()->addDays(60)->toDateString(), 'fecha_vto' => Carbon::today()->addDays(120)->toDateString(), 'nro_cuota' => 3]);

    }
}
