<?php

namespace Tests\Unit;

use App\Cuota;
use App\Descuento;
use App\Membresia;
use App\services\CuotaService;
use App\services\VentaService;
use App\Socio;
use App\User;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CuotaServiceTest extends TestCase
{
    use DatabaseMigrations;

    private $service;
    private $ventasSrv;
    public function setUp()
    {
        parent::setUp();
        $this->service = new CuotaService();
        $this->ventasSrv = new VentaService();
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


            $this->ventasSrv->realizarCompra($socio, $data['tipoPago'], $data['observacion'], $usuario, $mem, $membresia['cantidad'], $descuento);
        }
    }


    public function testCancelarUnPago()
    {

        factory(\App\Socio::class)->create(['id_descuento' => null]);
        $data = ['idSocio' => 1, 'tipoPago' => 'Efectivo', 'observacion' => null, 'membresias' => [['id' => 3, 'cantidad' => 1, 'idDescuento' => 3]]];

        $this->simularController($data);

        $user = User::find(1);


        $this->service->cancelarPago(Cuota::find(1), $user);

        $this->assertDatabaseHas('cuotas', ['id_venta' => 1, 'pagada' => 0, 'pago' => 100, 'fecha_inicio' => Carbon::today()->toDateString(), 'fecha_vto' => Carbon::today()->addDays(30)->toDateString(), 'nro_cuota' => 1]);
    }

}
