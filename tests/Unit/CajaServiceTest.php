<?php

namespace Tests\Unit;

use App\services\CajaService;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CajaServiceTest extends TestCase
{
    use DatabaseMigrations;


    private $service;
    public function setUp()
    {
        parent::setUp();
        $this->service = new CajaService();
        $this->artisan('migrate', ['--database' => 'mysql_testing']);
        $this->artisan('db:seed', ['--class' => 'SeguridadSeeder', '--database' => 'mysql_testing']);

    }
    public function testIngreso()
    {
        CajaService::ingreso(100, 'prueba', 'prueba', 'prueba', 1);

        $this->assertDatabaseHas('movimientos', ['ingreso' => 100, 'egreso' => 0, 'concepto' => 'prueba', 'observacion' => 'prueba', 'tipo_pago' => 'prueba', 'fecha' => Carbon::today()->toDateString()]);

    }

    public function testEgreso()
    {
        CajaService::egreso(100, 'prueba', 'prueba', 'prueba', 1);

        $this->assertDatabaseHas('movimientos', ['ingreso' => 0, 'egreso' => 100, 'concepto' => 'prueba', 'observacion' => 'prueba', 'tipo_pago' => 'prueba', 'fecha' => Carbon::today()->toDateString()]);

    }

    public function testMovimientos()
    {
        factory(\App\Movimiento::class)->states('egreso')->create();
        factory(\App\Movimiento::class)->states('egreso')->create();
        factory(\App\Movimiento::class)->states('egreso')->create();
        factory(\App\Movimiento::class)->states('egreso')->create();
        factory(\App\Movimiento::class)->states('egreso')->create();
        factory(\App\Movimiento::class)->states('egreso')->create();
        factory(\App\Movimiento::class)->states('egreso')->create();
        factory(\App\Movimiento::class)->states('egreso')->create();
        factory(\App\Movimiento::class)->states('egreso')->create();
        factory(\App\Movimiento::class)->create();
        factory(\App\Movimiento::class)->create();
        factory(\App\Movimiento::class)->create();
        factory(\App\Movimiento::class)->create();
        factory(\App\Movimiento::class)->create();
        factory(\App\Movimiento::class)->create();
        factory(\App\Movimiento::class)->create();
        factory(\App\Movimiento::class)->create();
        factory(\App\Movimiento::class)->create();

        $movimientos = CajaService::movimientos('2017-02-03', Carbon::today()->toDateString());

        $this->assertEquals(18, $movimientos->count());
    }

}
