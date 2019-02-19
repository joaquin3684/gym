<?php

namespace Tests\Unit;

use App\Cuota;
use App\Descuento;
use App\Membresia;
use App\services\SocioService;
use App\services\VentaService;
use App\Socio;
use App\User;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CompraMembresiaTest extends TestCase
{
    use DatabaseMigrations;

    private $service;
    public function setUp()
    {
        parent::setUp();
        $this->service = new VentaService();
        $this->artisan('migrate', ['--database' => 'mysql_testing']);
        $this->artisan('db:seed', ['--class' => 'MembresiaSeeder', '--database' => 'mysql_testing']);
        $this->artisan('db:seed', ['--class' => 'SeguridadSeeder', '--database' => 'mysql_testing']);

    }

    public function simularController($data)
    {
        $idSocio = $data['idSocio'];

        $socio = Socio::with('descuento', 'ventas.cuotas')->find($idSocio);
        foreach ($data['membresias'] as $membresia)
        {
            $mem = Membresia::find($membresia['id']);
            $descuento = is_null($membresia['idDescuento']) ? null : Descuento::find($membresia['idDescuento']);
            $usuario = User::find(1);


            $this->service->realizarCompra($socio, $data['tipoPago'], $data['observacion'], $usuario, $mem, $membresia['cantidad'], $descuento);
        }
    }

    public function testComprarUnaMembresiaDeUnPagoSinDescuentoDeSocioYSinDescuentoDeMembresia()
    {
        factory(\App\Socio::class)->create(['id_descuento' => null]);

        $data = ['idSocio' => 1, 'tipoPago' => 'Efectivo', 'observacion' => null, 'membresias' => [['id' => 1, 'cantidad' => 1, 'idDescuento' => null]]];

        $this->simularController($data);


        $this->assertDatabaseHas('movimientos', ['ingreso' => 100, 'concepto' => 'membresia 1', 'observacion' => null, 'tipo_pago' => 'Efectivo']);
        $this->assertDatabaseHas('ventas', ['fecha' => Carbon::today()->toDateString(), 'precio' => 100, 'id_membresia' => 1, 'id_socio' => 1, 'cantidad' => 1, 'id_descuento_membresia' => null, 'id_descuento_socio' => null, 'vto' => Carbon::today()->addDays(30)->toDateString()]);
        $this->assertDatabaseHas('venta_servicio', ['vto' => Carbon::today()->addDays(30)->toDateString(), 'id_servicio' => 1, 'id_venta' => 1, 'creditos' => 10]);
        $this->assertDatabaseHas('venta_servicio', ['vto' => Carbon::today()->addDays(30)->toDateString(), 'id_servicio' => 2, 'id_venta' => 1, 'creditos' => 5]);
        $this->assertDatabaseHas('cuotas', ['id_venta' => 1, 'pagada' => 1, 'pago' => 100, 'fecha_inicio' => Carbon::today()->toDateString(), 'fecha_vto' => Carbon::today()->addDays(30)->toDateString(), 'nro_cuota' => 1]);
    }

    public function testComprarUnaMembresiaDeUnPagoSinDescuentoDeSocioConDescuentoDeMembresia()
    {
        factory(\App\Socio::class)->create(['id_descuento' => null]);
        $data = ['idSocio' => 1, 'tipoPago' => 'Efectivo', 'observacion' => null, 'membresias' => [['id' => 1, 'cantidad' => 1, 'idDescuento' => 3]]];

        $this->simularController($data);

        $this->assertDatabaseHas('movimientos', ['ingreso' => 50, 'concepto' => 'membresia 1', 'observacion' => null, 'tipo_pago' => 'Efectivo']);
        $this->assertDatabaseHas('ventas', ['fecha' => Carbon::today()->toDateString(), 'precio' => 50, 'id_membresia' => 1, 'id_socio' => 1, 'cantidad' => 1, 'id_descuento_membresia' => 3, 'id_descuento_socio' => null, 'vto' => Carbon::today()->addDays(30)->toDateString()]);
        $this->assertDatabaseHas('venta_servicio', ['vto' => Carbon::today()->addDays(30)->toDateString(), 'id_servicio' => 1, 'id_venta' => 1, 'creditos' => 10]);
        $this->assertDatabaseHas('venta_servicio', ['vto' => Carbon::today()->addDays(30)->toDateString(), 'id_servicio' => 2, 'id_venta' => 1, 'creditos' => 5]);
        $this->assertDatabaseHas('cuotas', ['id_venta' => 1, 'pagada' => 1, 'pago' => 50, 'fecha_inicio' => Carbon::today()->toDateString(), 'fecha_vto' => Carbon::today()->addDays(30)->toDateString(), 'nro_cuota' => 1]);

    }

    public function testComprarUnaMembresiaDeUnPagoConUnSocioConDescuentoAsignadoPeroParaOtraMembresia()
    {
        factory(\App\Socio::class)->create(['id_descuento' => 1]);
        $data = ['idSocio' => 1, 'tipoPago' => 'Efectivo', 'observacion' => null, 'membresias' => [['id' => 1, 'cantidad' => 1, 'idDescuento' => null]]];

        $this->simularController($data);

        $this->assertDatabaseHas('movimientos', ['ingreso' => 100, 'concepto' => 'membresia 1', 'observacion' => null, 'tipo_pago' => 'Efectivo']);
        $this->assertDatabaseHas('ventas', ['fecha' => Carbon::today()->toDateString(), 'precio' => 100, 'id_membresia' => 1, 'id_socio' => 1, 'cantidad' => 1, 'id_descuento_membresia' => null, 'id_descuento_socio' => null, 'vto' => Carbon::today()->addDays(30)->toDateString()]);
        $this->assertDatabaseHas('venta_servicio', ['vto' => Carbon::today()->addDays(30)->toDateString(), 'id_servicio' => 1, 'id_venta' => 1, 'creditos' => 10]);
        $this->assertDatabaseHas('venta_servicio', ['vto' => Carbon::today()->addDays(30)->toDateString(), 'id_servicio' => 2, 'id_venta' => 1, 'creditos' => 5]);
        $this->assertDatabaseHas('cuotas', ['id_venta' => 1, 'pagada' => 1, 'pago' => 100, 'fecha_inicio' => Carbon::today()->toDateString(), 'fecha_vto' => Carbon::today()->addDays(30)->toDateString(), 'nro_cuota' => 1]);

    }

    public function testComprarUnaMembresiaDeUnPagoConUnSocioConDescuentoAsignadoParaEsaMembresia()
    {
        factory(\App\Socio::class)->create(['id_descuento' => 2]);
        $data = ['idSocio' => 1, 'tipoPago' => 'Efectivo', 'observacion' => null, 'membresias' => [['id' => 2, 'cantidad' => 1, 'idDescuento' => null]]];

        $this->simularController($data);

        $this->assertDatabaseHas('movimientos', ['ingreso' => 75, 'concepto' => 'Cuota 1 membresia 2', 'observacion' => null, 'tipo_pago' => 'Efectivo']);
        $this->assertDatabaseHas('ventas', ['fecha' => Carbon::today()->toDateString(), 'precio' => 150, 'id_membresia' => 2, 'id_socio' => 1, 'cantidad' => 1, 'id_descuento_membresia' => null, 'id_descuento_socio' => 2, 'vto' => Carbon::today()->addDays(90)->toDateString()]);
        $this->assertDatabaseHas('venta_servicio', ['vto' => Carbon::today()->addDays(30)->toDateString(), 'id_servicio' => 1, 'id_venta' => 1, 'creditos' => 10]);
        $this->assertDatabaseHas('venta_servicio', ['vto' => Carbon::today()->addDays(50)->toDateString(), 'id_servicio' => 2, 'id_venta' => 1, 'creditos' => 5]);
        $this->assertDatabaseHas('cuotas', ['id_venta' => 1, 'pagada' => 1, 'pago' => 75, 'fecha_inicio' => Carbon::today()->toDateString(), 'fecha_vto' => Carbon::today()->addDays(30)->toDateString(), 'nro_cuota' => 1]);
        $this->assertDatabaseHas('cuotas', ['id_venta' => 1, 'pagada' => 0, 'pago' => 75, 'fecha_inicio' => Carbon::today()->addDays(30)->toDateString(), 'fecha_vto' => Carbon::today()->addDays(90)->toDateString(), 'nro_cuota' => 2]);

    }

    public function testComprarUnaMembresiaDeUnPagoConUnSocioConDescuentoAsignadoParaEsaMembresiaYConUnDescuentoDeMembresiaConAplicableEnConjunto()
    {
        factory(\App\Socio::class)->create(['id_descuento' => 1]);
        $data = ['idSocio' => 1, 'tipoPago' => 'Efectivo', 'observacion' => null, 'membresias' => [['id' => 2, 'cantidad' => 1, 'idDescuento' => 3]]];

        $this->simularController($data);

        $this->assertDatabaseHas('movimientos', ['ingreso' => 37.5, 'concepto' => 'Cuota 1 membresia 2', 'observacion' => null, 'tipo_pago' => 'Efectivo']);
        $this->assertDatabaseHas('ventas', ['fecha' => Carbon::today()->toDateString(), 'precio' => 75, 'id_membresia' => 2, 'id_socio' => 1, 'cantidad' => 1, 'id_descuento_membresia' => 3, 'id_descuento_socio' => 1, 'vto' => Carbon::today()->addDays(90)->toDateString()]);
        $this->assertDatabaseHas('venta_servicio', ['vto' => Carbon::today()->addDays(30)->toDateString(), 'id_servicio' => 1, 'id_venta' => 1, 'creditos' => 10]);
        $this->assertDatabaseHas('venta_servicio', ['vto' => Carbon::today()->addDays(50)->toDateString(), 'id_servicio' => 2, 'id_venta' => 1, 'creditos' => 5]);
        $this->assertDatabaseHas('cuotas', ['id_venta' => 1, 'pagada' => 1, 'pago' => 37.5, 'fecha_inicio' => Carbon::today()->toDateString(), 'fecha_vto' => Carbon::today()->addDays(30)->toDateString(), 'nro_cuota' => 1]);
        $this->assertDatabaseHas('cuotas', ['id_venta' => 1, 'pagada' => 0, 'pago' => 37.5, 'fecha_inicio' => Carbon::today()->addDays(30)->toDateString(), 'fecha_vto' => Carbon::today()->addDays(90)->toDateString(), 'nro_cuota' => 2]);

    }

    public function testComprarUnaMembresiaDeUnPagoConUnSocioConDescuentoAsignadoParaEsaMembresiaYConUnDescuentoDeMembresiaSinAplicableEnConjunto()
    {
        factory(\App\Socio::class)->create(['id_descuento' => 2]);
        $data = ['idSocio' => 1, 'tipoPago' => 'Efectivo', 'observacion' => null, 'membresias' => [['id' => 2, 'cantidad' => 1, 'idDescuento' => 4]]];

        $this->simularController($data);

        $this->assertDatabaseHas('movimientos', ['ingreso' => 0, 'concepto' => 'Cuota 1 membresia 2', 'observacion' => null, 'tipo_pago' => 'Efectivo']);
        $this->assertDatabaseHas('ventas', ['fecha' => Carbon::today()->toDateString(), 'precio' => 0, 'id_membresia' => 2, 'id_socio' => 1, 'cantidad' => 1, 'id_descuento_membresia' => 4, 'id_descuento_socio' => 2, 'vto' => Carbon::today()->addDays(90)->toDateString()]);
        $this->assertDatabaseHas('venta_servicio', ['vto' => Carbon::today()->addDays(30)->toDateString(), 'id_servicio' => 1, 'id_venta' => 1, 'creditos' => 10]);
        $this->assertDatabaseHas('venta_servicio', ['vto' => Carbon::today()->addDays(50)->toDateString(), 'id_servicio' => 2, 'id_venta' => 1, 'creditos' => 5]);
        $this->assertDatabaseHas('cuotas', ['id_venta' => 1, 'pagada' => 1, 'pago' => 0, 'fecha_inicio' => Carbon::today()->toDateString(), 'fecha_vto' => Carbon::today()->addDays(30)->toDateString(), 'nro_cuota' => 1]);
        $this->assertDatabaseHas('cuotas', ['id_venta' => 1, 'pagada' => 0, 'pago' => 0, 'fecha_inicio' => Carbon::today()->addDays(30)->toDateString(), 'fecha_vto' => Carbon::today()->addDays(90)->toDateString(), 'nro_cuota' => 2]);

    }

    public function testComprarUnaMembresiaDeUnPagoConUnSocioConDescuentoAsignadoPeroNoParaEsaMembresiaYConUnDescuentoDeMembresia()
    {
        factory(\App\Socio::class)->create(['id_descuento' => 1]);
        $data = ['idSocio' => 1, 'tipoPago' => 'Efectivo', 'observacion' => null, 'membresias' => [['id' => 1, 'cantidad' => 1, 'idDescuento' => 3], ['id' => 1, 'cantidad' => 1, 'idDescuento' => 4]]];

        $this->simularController($data);

        $this->assertDatabaseHas('movimientos', ['ingreso' => 50, 'concepto' => 'membresia 1', 'observacion' => null, 'tipo_pago' => 'Efectivo']);
        $this->assertDatabaseHas('movimientos', ['ingreso' => 50, 'concepto' => 'membresia 1', 'observacion' => null, 'tipo_pago' => 'Efectivo']);
        $this->assertDatabaseHas('ventas', ['fecha' => Carbon::today()->toDateString(), 'precio' => 50, 'id_membresia' => 1, 'id_socio' => 1, 'cantidad' => 1, 'id_descuento_membresia' => 3, 'id_descuento_socio' => null, 'vto' => Carbon::today()->addDays(30)->toDateString()]);
        $this->assertDatabaseHas('ventas', ['fecha' => Carbon::today()->toDateString(), 'precio' => 50, 'id_membresia' => 1, 'id_socio' => 1, 'cantidad' => 1, 'id_descuento_membresia' => 4, 'id_descuento_socio' => null, 'vto' => Carbon::today()->addDays(30)->toDateString()]);
        $this->assertDatabaseHas('venta_servicio', ['vto' => Carbon::today()->addDays(30)->toDateString(), 'id_servicio' => 1, 'id_venta' => 1, 'creditos' => 10]);
        $this->assertDatabaseHas('venta_servicio', ['vto' => Carbon::today()->addDays(30)->toDateString(), 'id_servicio' => 1, 'id_venta' => 1, 'creditos' => 10]);
        $this->assertDatabaseHas('venta_servicio', ['vto' => Carbon::today()->addDays(30)->toDateString(), 'id_servicio' => 2, 'id_venta' => 1, 'creditos' => 5]);
        $this->assertDatabaseHas('venta_servicio', ['vto' => Carbon::today()->addDays(30)->toDateString(), 'id_servicio' => 2, 'id_venta' => 1, 'creditos' => 5]);
        $this->assertDatabaseHas('cuotas', ['id_venta' => 1, 'pagada' => 1, 'pago' => 50, 'fecha_inicio' => Carbon::today()->toDateString(), 'fecha_vto' => Carbon::today()->addDays(30)->toDateString(), 'nro_cuota' => 1]);
        $this->assertDatabaseHas('cuotas', ['id_venta' => 2, 'pagada' => 1, 'pago' => 50, 'fecha_inicio' => Carbon::today()->toDateString(), 'fecha_vto' => Carbon::today()->addDays(30)->toDateString(), 'nro_cuota' => 1]);
    }

    public function testComprarDosMembresiasDeUnPago()
    {
        factory(\App\Socio::class)->create(['id_descuento' => 1]);
        $data = ['idSocio' => 1, 'tipoPago' => 'Efectivo', 'observacion' => null, 'membresias' => [['id' => 1, 'cantidad' => 1, 'idDescuento' => 3], ['id' => 1, 'cantidad' => 1, 'idDescuento' => 4]]];

        $this->simularController($data);

        $this->assertDatabaseHas('movimientos', ['ingreso' => 50, 'concepto' => 'membresia 1', 'observacion' => null, 'tipo_pago' => 'Efectivo']);
        $this->assertDatabaseHas('movimientos', ['ingreso' => 50, 'concepto' => 'membresia 1', 'observacion' => null, 'tipo_pago' => 'Efectivo']);
        $this->assertDatabaseHas('ventas', ['fecha' => Carbon::today()->toDateString(), 'precio' => 50, 'id_membresia' => 1, 'id_socio' => 1, 'cantidad' => 1, 'id_descuento_membresia' => 3, 'id_descuento_socio' => null, 'vto' => Carbon::today()->addDays(30)->toDateString()]);
        $this->assertDatabaseHas('ventas', ['fecha' => Carbon::today()->toDateString(), 'precio' => 50, 'id_membresia' => 1, 'id_socio' => 1, 'cantidad' => 1, 'id_descuento_membresia' => 4, 'id_descuento_socio' => null, 'vto' => Carbon::today()->addDays(30)->toDateString()]);
        $this->assertDatabaseHas('venta_servicio', ['vto' => Carbon::today()->addDays(30)->toDateString(), 'id_servicio' => 1, 'id_venta' => 1, 'creditos' => 10]);
        $this->assertDatabaseHas('venta_servicio', ['vto' => Carbon::today()->addDays(30)->toDateString(), 'id_servicio' => 1, 'id_venta' => 1, 'creditos' => 10]);
        $this->assertDatabaseHas('venta_servicio', ['vto' => Carbon::today()->addDays(30)->toDateString(), 'id_servicio' => 2, 'id_venta' => 1, 'creditos' => 5]);
        $this->assertDatabaseHas('venta_servicio', ['vto' => Carbon::today()->addDays(30)->toDateString(), 'id_servicio' => 2, 'id_venta' => 1, 'creditos' => 5]);
        $this->assertDatabaseHas('cuotas', ['id_venta' => 1, 'pagada' => 1, 'pago' => 50, 'fecha_inicio' => Carbon::today()->toDateString(), 'fecha_vto' => Carbon::today()->addDays(30)->toDateString(), 'nro_cuota' => 1]);
        $this->assertDatabaseHas('cuotas', ['id_venta' => 2, 'pagada' => 1, 'pago' => 50, 'fecha_inicio' => Carbon::today()->toDateString(), 'fecha_vto' => Carbon::today()->addDays(30)->toDateString(), 'nro_cuota' => 1]);

    }

    public function testComprarMembresiaDeDosPagos()
    {
        factory(\App\Socio::class)->create(['id_descuento' => null]);
        $data = ['idSocio' => 1, 'tipoPago' => 'Efectivo', 'observacion' => null, 'membresias' => [['id' => 2, 'cantidad' => 1, 'idDescuento' => 3]]];

        $this->simularController($data);

        $this->assertDatabaseHas('movimientos', ['ingreso' => 75, 'concepto' => 'Cuota 1 membresia 2', 'observacion' => null, 'tipo_pago' => 'Efectivo']);
        $this->assertDatabaseHas('ventas', ['fecha' => Carbon::today()->toDateString(), 'precio' => 150, 'id_membresia' => 2, 'id_socio' => 1, 'cantidad' => 1, 'id_descuento_membresia' => 3, 'id_descuento_socio' => null, 'vto' => Carbon::today()->addDays(90)->toDateString()]);
        $this->assertDatabaseHas('venta_servicio', ['vto' => Carbon::today()->addDays(30)->toDateString(), 'id_servicio' => 1, 'id_venta' => 1, 'creditos' => 10]);
        $this->assertDatabaseHas('venta_servicio', ['vto' => Carbon::today()->addDays(50)->toDateString(), 'id_servicio' => 2, 'id_venta' => 1, 'creditos' => 5]);
        $this->assertDatabaseHas('cuotas', ['id_venta' => 1, 'pagada' => 1, 'pago' => 75, 'fecha_inicio' => Carbon::today()->toDateString(), 'fecha_vto' => Carbon::today()->addDays(30)->toDateString(), 'nro_cuota' => 1]);
        $this->assertDatabaseHas('cuotas', ['id_venta' => 1, 'pagada' => 0, 'pago' => 75, 'fecha_inicio' => Carbon::today()->addDays(30)->toDateString(), 'fecha_vto' => Carbon::today()->addDays(90)->toDateString(), 'nro_cuota' => 2]);
    }

    public function testComprarMembresiaDeMasDe2Pagos()
    {
        factory(\App\Socio::class)->create(['id_descuento' => null]);
        $data = ['idSocio' => 1, 'tipoPago' => 'Efectivo', 'observacion' => null, 'membresias' => [['id' => 3, 'cantidad' => 1, 'idDescuento' => 3]]];

        $this->simularController($data);

        $this->assertDatabaseHas('movimientos', ['ingreso' => 100, 'concepto' => 'Cuota 1 membresia 3', 'observacion' => null, 'tipo_pago' => 'Efectivo']);
        $this->assertDatabaseHas('ventas', ['fecha' => Carbon::today()->toDateString(), 'precio' => 300, 'id_membresia' => 3, 'id_socio' => 1, 'cantidad' => 1, 'id_descuento_membresia' => 3, 'id_descuento_socio' => null, 'vto' => Carbon::today()->addDays(120)->toDateString()]);
        $this->assertDatabaseHas('venta_servicio', ['vto' => Carbon::today()->addDays(30)->toDateString(), 'id_servicio' => 1, 'id_venta' => 1, 'creditos' => 10]);
        $this->assertDatabaseHas('venta_servicio', ['vto' => Carbon::today()->addDays(50)->toDateString(), 'id_servicio' => 2, 'id_venta' => 1, 'creditos' => 5]);
        $this->assertDatabaseHas('cuotas', ['id_venta' => 1, 'pagada' => 1, 'pago' => 100, 'fecha_inicio' => Carbon::today()->toDateString(), 'fecha_vto' => Carbon::today()->addDays(30)->toDateString(), 'nro_cuota' => 1]);
        $this->assertDatabaseHas('cuotas', ['id_venta' => 1, 'pagada' => 0, 'pago' => 100, 'fecha_inicio' => Carbon::today()->addDays(30)->toDateString(), 'fecha_vto' => Carbon::today()->addDays(60)->toDateString(), 'nro_cuota' => 2]);
        $this->assertDatabaseHas('cuotas', ['id_venta' => 1, 'pagada' => 0, 'pago' => 100, 'fecha_inicio' => Carbon::today()->addDays(60)->toDateString(), 'fecha_vto' => Carbon::today()->addDays(120)->toDateString(), 'nro_cuota' => 3]);
    }

    public function testComprarUnaMembresiaEnLaCualTengoCuotasPendientes()
    {
        factory(\App\Socio::class)->create(['id_descuento' => null]);
        $data = ['idSocio' => 1, 'tipoPago' => 'Efectivo', 'observacion' => null, 'membresias' => [['id' => 3, 'cantidad' => 1, 'idDescuento' => 3]]];

        $this->simularController($data);

        $this->simularController($data);

        $this->assertDatabaseHas('movimientos', ['ingreso' => 100, 'concepto' => 'Cuota 1 membresia 3', 'observacion' => null, 'tipo_pago' => 'Efectivo']);
        $this->assertDatabaseHas('movimientos', ['ingreso' => 100, 'concepto' => 'Cuota 1 membresia 3', 'observacion' => null, 'tipo_pago' => 'Efectivo']);
        $this->assertDatabaseHas('cuotas', ['id_venta' => 1, 'pagada' => 1, 'pago' => 100, 'fecha_inicio' => Carbon::today()->toDateString(), 'fecha_vto' => Carbon::today()->addDays(30)->toDateString(), 'nro_cuota' => 1]);
        $this->assertDatabaseHas('cuotas', ['id_venta' => 1, 'pagada' => 1, 'pago' => 100, 'fecha_inicio' => Carbon::today()->addDays(30)->toDateString(), 'fecha_vto' => Carbon::today()->addDays(60)->toDateString(), 'nro_cuota' => 2]);
        $this->assertDatabaseHas('cuotas', ['id_venta' => 1, 'pagada' => 0, 'pago' => 100, 'fecha_inicio' => Carbon::today()->addDays(60)->toDateString(), 'fecha_vto' => Carbon::today()->addDays(120)->toDateString(), 'nro_cuota' => 3]);
        $this->assertDatabaseMissing('ventas', ['id' => 2]);
    }

    public function testComprarUnaMembresiaEnLaCualLasCuotasSeMeVecieron()
    {
        $socio = factory(\App\Socio::class)->create(['id_descuento' => null]);
        $fechaInicioCreacion = Carbon::today()->subDays(120);
        $fechaVtoCreacion = Carbon::today()->subDays(90);
        $venta = factory(\App\Venta::class)->create(['id_socio' => $socio->id, 'id_membresia' => 3, 'vto' => '2015-02-03']);
        factory(Cuota::class)->create(['fecha_inicio' => $fechaInicioCreacion->toDateString(), 'fecha_vto' => $fechaVtoCreacion->toDateString(), 'id_venta' => $venta->id, 'nro_cuota' => 1, 'pagada' => true]);
        factory(Cuota::class)->create(['fecha_inicio' => $fechaInicioCreacion->addDays(30)->toDateString(), 'fecha_vto' => $fechaVtoCreacion->addDays(30)->toDateString(), 'id_venta' => $venta->id, 'nro_cuota' => 2]);
        factory(Cuota::class)->create(['fecha_inicio' => $fechaInicioCreacion->addDays(30)->toDateString(), 'fecha_vto' => $fechaVtoCreacion->addDays(60)->toDateString(), 'id_venta' => $venta->id, 'nro_cuota' => 3]);

        $data = ['idSocio' => 1, 'tipoPago' => 'Efectivo', 'observacion' => null, 'membresias' => [['id' => 3, 'cantidad' => 1, 'idDescuento' => 3]]];

        $this->simularController($data);


        $fechaInicio = Carbon::today()->subDays(120);
        $fechaVto = Carbon::today()->subDays(90);

        $this->assertDatabaseHas('movimientos', ['ingreso' => 100, 'concepto' => 'Cuota 2 membresia 3', 'observacion' => null, 'tipo_pago' => 'Efectivo']);
        $this->assertDatabaseHas('cuotas', ['id_venta' => $venta->id, 'pagada' => 1, 'pago' => 100, 'fecha_inicio' => $fechaInicio->toDateString(), 'fecha_vto' => $fechaVto->toDateString(), 'nro_cuota' => 1]);
        $this->assertDatabaseHas('cuotas', ['id_venta' => $venta->id, 'pagada' => 1, 'pago' => 100, 'fecha_inicio' => $fechaInicio->addDays(30)->toDateString(), 'fecha_vto' => $fechaVto->addDays(30)->toDateString(), 'nro_cuota' => 2]);
        $this->assertDatabaseHas('cuotas', ['id_venta' => $venta->id, 'pagada' => 0, 'pago' => 100, 'fecha_inicio' => $fechaInicio->addDays(30)->toDateString(), 'fecha_vto' => $fechaVto->addDays(60)->toDateString(), 'nro_cuota' => 3]);
        $this->assertDatabaseMissing('ventas', ['id' => 2]);
    }

    public function testComprarUnaMembresiaCuandoTodaviaHayUnaVigente()
    {
        factory(\App\Socio::class)->create(['id_descuento' => null]);
        $data = ['idSocio' => 1, 'tipoPago' => 'Efectivo', 'observacion' => null, 'membresias' => [['id' => 1, 'cantidad' => 1, 'idDescuento' => 3]]];

        $this->simularController($data);

        $this->simularController($data);

        $this->assertDatabaseHas('movimientos', ['ingreso' => 50, 'concepto' => 'membresia 1', 'observacion' => null, 'tipo_pago' => 'Efectivo']);
        $this->assertDatabaseHas('cuotas', ['id_venta' => 1, 'pagada' => 1, 'pago' => 50, 'fecha_inicio' => Carbon::today()->toDateString(), 'fecha_vto' => Carbon::today()->addDays(30)->toDateString(), 'nro_cuota' => 1]);
        $this->assertDatabaseHas('venta_servicio', ['vto' => Carbon::today()->addDays(30)->toDateString(), 'id_servicio' => 1, 'id_venta' => 1, 'creditos' => 10]);
        $this->assertDatabaseHas('venta_servicio', ['vto' => Carbon::today()->addDays(30)->toDateString(), 'id_servicio' => 2, 'id_venta' => 1, 'creditos' => 5]);
        $this->assertDatabaseHas('venta_servicio', ['vto' => Carbon::today()->addDays(60)->toDateString(), 'id_servicio' => 2, 'id_venta' => 2, 'creditos' => 5]);
        $this->assertDatabaseHas('venta_servicio', ['vto' => Carbon::today()->addDays(60)->toDateString(), 'id_servicio' => 1, 'id_venta' => 2, 'creditos' => 10]);
        $this->assertDatabaseHas('cuotas', ['id_venta' => 2, 'pagada' => 1, 'pago' => 50, 'fecha_inicio' => Carbon::today()->toDateString(), 'fecha_vto' => Carbon::today()->addDays(30)->toDateString(), 'nro_cuota' => 1]);
        $this->assertDatabaseHas('ventas', ['id' => 1, 'fecha' => Carbon::today()->toDateString(), 'precio' => 50, 'id_membresia' => 1, 'id_socio' => 1, 'cantidad' => 1, 'id_descuento_membresia' => 3, 'id_descuento_socio' => null, 'vto' => Carbon::today()->addDays(30)->toDateString()]);
        $this->assertDatabaseHas('ventas', ['id' => 2, 'fecha' => Carbon::today()->toDateString(), 'precio' => 50, 'id_membresia' => 1, 'id_socio' => 1, 'cantidad' => 1, 'id_descuento_membresia' => 3, 'id_descuento_socio' => null, 'vto' => Carbon::today()->addDays(60)->toDateString()]);
    }
}
