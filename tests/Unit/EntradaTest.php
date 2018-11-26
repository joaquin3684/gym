<?php

namespace Tests\Unit;

use App\services\ClaseService;
use App\services\ServicioService;
use App\services\SocioService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EntradaTest extends TestCase
{
    use DatabaseMigrations;

    private $service;
    private $servService;
    private $clService;
    public function setUp()
    {
        parent::setUp();
        $this->service = new SocioService();
        $this->servService = new ServicioService();
        $this->clService = new ClaseService();
        $this->artisan('migrate', ['--database' => 'mysql_testing']);
        $this->artisan('db:seed', ['--class' => 'EntradaSeeder', '--database' => 'mysql_testing']);

    }

    public function testAccederAUnaHoraDondeSoloHayaUnServicioDisponible()
    {
        $data = ['idSocio' => 1, 'automatico' => true];
        $valor = $this->service->acceder($data);
        $this->assertEquals(1, $valor);
        $this->assertDatabaseHas('clases_socios', ['id_socio' => 1]);
        $this->assertDatabaseHas('socio_servicio', ['id_socio' => 1, 'id_servicio' => 11, 'creditos' => 99]);
    }

    public function testAccederAUnaHoraDondeHayaMasDeUnServicioDisponible()
    {
        $data = ['idSocio' => 2, 'automatico' => true];
        $valor = $this->service->acceder($data);
        $this->assertEquals(count($valor['servicios']), 2);
    }

    public function testAccederAUnaHoraDondeNoHayaServiciosDisponibles()
    {
        $data = ['idSocio' => 3, 'automatico' => true];
        $valor = $this->service->acceder($data);
        $this->assertEquals($valor, 2);
    }

    public function testAccederAUnaHoraDondeHayaUnServicioSinCreditos()
    {
        $data = ['idSocio' => 4, 'automatico' => true];
        $valor = $this->service->acceder($data);
        $this->assertEquals($valor, 2);
    }

    public function testAccederAUnaHoraDondeElServicioEsteVencido()
    {
        $data = ['idSocio' => 5, 'automatico' => true];
        $valor = $this->service->acceder($data);
        $this->assertEquals($valor, 2);
    }

    public function testAccederAUnaHoraDondeTengaUnServicioVencidoPeroOtroNo()
    {
        $data = ['idSocio' => 8, 'automatico' => true];
        $valor = $this->service->acceder($data);
        $this->assertEquals($valor, 1);
        $this->assertDatabaseHas('clases_socios', ['id_socio' => 8]);
        $this->assertDatabaseHas('socio_servicio', ['id_socio' => 8, 'id_servicio' => 2, 'creditos' => 4]);
    }

    public function testAccederAUnaHoraDondeTengaUnaMembresiaQueEsteVencida()
    {
        $data = ['idSocio' => 6, 'automatico' => true];
        $valor = $this->service->acceder($data);
        $this->assertEquals($valor, 2);
    }

    public function testAccederAUnaHoraDondeTengaUnaMembresiaQueEsteVencidaLaCuota()
    {
        $data = ['idSocio' => 7, 'automatico' => true];
        $valor = $this->service->acceder($data);
        $this->assertEquals(2, $valor);
    }

    public function testRegistrarEntradaManual()
    {
        $data = ['socios' => [1], 'clase' => 7];
        $this->clService->registrarEntradas($data);
        $this->assertDatabaseHas('clases_socios', ['id_socio' => 1, 'id_clase' => 7]);

    }

    public function testDevolverEntrada()
    {
        $data = ['socios' => [1], 'clase' => 7];
        $this->clService->registrarEntradas($data);
        $data = ['socios' => [1], 'clases' => [7]];

        $this->clService->devolverEntradas($data);
        $this->assertDatabaseMissing('clases_socios', ['id_socio' => 1, 'id_clase' => 7]);
        $this->assertDatabaseHas('socio_servicio', ['id_socio' => 1, 'id_servicio' => 11, 'creditos' => 100]);


    }

    public function testServicioQueNoRegistraEntrada()
    {
        $data = ['idSocio' => 9, 'automatico' => true];
        $valor = $this->service->acceder($data);
        $this->assertEquals($valor, 2);
    }
}
