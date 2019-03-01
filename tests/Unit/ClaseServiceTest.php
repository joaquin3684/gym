<?php

namespace Tests\Unit;

use App\Clase;
use App\services\ClaseService;
use App\Servicio;
use App\Socio;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ClaseServiceTest extends TestCase
{
    use DatabaseMigrations;

    private $service;
    public function setUp()
    {
        parent::setUp();
        $this->service = new ClaseService();
        $this->artisan('migrate', ['--database' => 'mysql_testing']);
        $this->artisan('db:seed', ['--class' => 'SocioSeeder', '--database' => 'mysql_testing']);
        $this->artisan('db:seed', ['--class' => 'ClasesSeeder', '--database' => 'mysql_testing']);
        $this->artisan('db:seed', ['--class' => 'ProfesoresSeeder', '--database' => 'mysql_testing']);
    }

    public function testCrearClase()
    {

        $data = [
            'fecha' => \Carbon\Carbon::today()->toDateString(),
            'id_servicio' => 2,
            'estado' => 2,
            'desde' => '11:00:00',
            'hasta' => '23:00:00',
            'entrada_desde' => '11:00:00',
            'entrada_hasta' => '23:00:00',
            'profesores' => [1,2]
        ];

        $fecha = Carbon::today()->toDateString();
        $servicio = Servicio::find(2);
        $desde = '11:00:00';
        $hasta = '23:00:00';
        $entradaDesde = '11:00:00';
        $entradaHasta = '23:00:00';
        $profesores = [1,2];
        $estado = 2;


        $this->service->crear($fecha, $servicio, $desde, $hasta, $entradaDesde, $entradaHasta, $estado, $profesores);
        unset($data['profesores']);
        $this->assertDatabaseHas('clases', $data);
        $this->assertDatabaseHas('clase_profesor', ['id_clase' => 7, 'id_profesor' => 1]);
        $this->assertDatabaseHas('clase_profesor', ['id_clase' => 7, 'id_profesor' => 2]);
    }

    public function testUpdateClase()
    {

        $clase = factory(\App\Clase::class)->create();
        $clase->profesores()->attach([1,2]);

        $data = [
            'fecha' => \Carbon\Carbon::today()->toDateString(),
            'id_servicio' => 2,
            'estado' => 2,
            'desde' => '11:00:00',
            'hasta' => '23:00:00',
            'entrada_desde' => '11:00:00',
            'entrada_hasta' => '23:00:00',
            'profesores' => [1]
        ];


        $fecha = Carbon::today()->toDateString();
        $servicio = Servicio::find(2);
        $desde = '11:00:00';
        $hasta = '23:00:00';
        $entradaDesde = '11:00:00';
        $entradaHasta = '23:00:00';
        $profesores = [1];
        $estado = 2;


        $this->service->update($fecha, $servicio, $desde, $hasta, $entradaDesde, $entradaHasta, $estado, $profesores, $clase);
        unset($data['profesores']);
        $this->assertDatabaseHas('clases', $data);
        $this->assertDatabaseHas('clase_profesor', ['id_clase' => $clase->id, 'id_profesor' => 1]);
        $this->assertDatabaseMissing('clase_profesor', ['id_clase' => $clase->id, 'id_profesor' => 2]);
    }

    public function testClasesEnTranscurso()
    {

        $clases = $this->service->clasesEnTranscurso();
        $this->assertEquals(3, $clases->count());
    }

    public function testClasesDelDia()
    {
        $clases = $this->service->clasesDelDia();
        $this->assertEquals(3, $clases->count());
    }

    public function testClasesFuturas()
    {
        $clases = $this->service->clasesFuturas();
        $this->assertEquals(3, $clases->count());
    }


    public function testRegistrarEntrada()
    {

        $clase = factory(Clase::class)->create(['id_servicio' => 11]);
        $socios = Socio::whereIn('id', [1])->get();

        $this->service->registrarEntradas($clase, $socios);

        $this->assertDatabaseHas('clases_socios', ['id_clase' => $clase->id, 'id_socio' => 1]);
        $this->assertDatabaseHas('venta_servicio', ['id_venta' => 1, 'id_servicio' => 11, 'creditos' => 99]);
    }

    public function testDevolverEntrada()
    {

        $clase = factory(Clase::class)->create(['id_servicio' => 11]);
        $socios = Socio::whereIn('id', [1])->get();

        $this->service->devolverEntradas($clase, $socios);

        $this->assertDatabaseMissing('clases_socios', ['id_clase' => $clase->id, 'id_socio' => 1]);
        $this->assertDatabaseHas('venta_servicio', ['id_venta' => 1, 'id_servicio' => 11, 'creditos' => 101]);
    }


}
