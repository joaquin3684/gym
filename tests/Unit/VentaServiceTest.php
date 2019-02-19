<?php

namespace Tests\Unit;

use App\Descuento;
use App\Membresia;
use App\services\VentaService;
use App\Socio;
use App\User;
use App\Venta;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class VentaServiceTest extends TestCase
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

    public function testBorrarVenta()
    {
        factory(\App\Socio::class)->create(['id_descuento' => null]);

        $data = ['idSocio' => 1, 'tipoPago' => 'Efectivo', 'observacion' => null, 'membresias' => [['id' => 1, 'cantidad' => 1, 'idDescuento' => null]]];

        $this->simularController($data);

        $this->service->delete(Venta::find(1));

        $this->assertDatabaseMissing('ventas', ['fecha' => Carbon::today()->toDateString(), 'precio' => 100, 'id_membresia' => 1, 'id_socio' => 1, 'cantidad' => 1, 'id_descuento_membresia' => null, 'id_descuento_socio' => null, 'vto' => Carbon::today()->addDays(30)->toDateString()]);
        $this->assertDatabaseMissing('venta_servicio', ['vto' => Carbon::today()->addDays(30)->toDateString(), 'id_servicio' => 1, 'id_venta' => 1, 'creditos' => 10]);
        $this->assertDatabaseMissing('venta_servicio', ['vto' => Carbon::today()->addDays(30)->toDateString(), 'id_servicio' => 2, 'id_venta' => 1, 'creditos' => 5]);
        $this->assertDatabaseMissing('cuotas', ['id_venta' => 1, 'pagada' => 1, 'pago' => 100, 'fecha_inicio' => Carbon::today()->toDateString(), 'fecha_vto' => Carbon::today()->addDays(30)->toDateString(), 'nro_cuota' => 1]);
    }



}
