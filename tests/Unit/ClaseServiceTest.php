<?php

namespace Tests\Unit;

use App\services\ClaseService;
use App\Socio;
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
        $this->artisan('db:seed', ['--class' => 'DiasSeeder', '--database' => 'mysql_testing']);
        $this->artisan('db:seed', ['--class' => 'ServicioSeeder', '--database' => 'mysql_testing']);
        $this->artisan('db:seed', ['--class' => 'ClasesSeeder', '--database' => 'mysql_testing']);
    }

    public function testUpdateClase()
    {

        $clase = factory(\App\Clase::class)->create();
        $data = [
            'fecha' => \Carbon\Carbon::today()->addDay()->toDateString(),
            'dia' => 'Martes',
            'id_servicio' => 2,
            'estado' => 2,
            'desde' => '11:00:00',
            'hasta' => '23:00:00',
            'entrada_desde' => '11:00:00',
            'entrada_hasta' => '23:00:00',
        ];

        $this->service->update($data, $clase->id);
        $this->assertDatabaseHas('clases', $data);
    }

    public function testClasesEnTranscurso()
    {
        $clases = $this->service->clasesEnTranscurso();
        $this->assertEquals(2, $clases->count());
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


    public function testRegistrarAlumnos()
    {
        $socio1 = factory(Socio::class)->create();
        $socio2 = factory(Socio::class)->create();
        $data = ['id' => 1, 'alumnos' => [$socio1->id, $socio2->id]];
        $this->service->registrarAlumnos($data);

        $this->assertDatabaseHas('clases_socios', ['id_clase' => 1, 'id_socio' => 1]);
        $this->assertDatabaseHas('clases_socios', ['id_clase' => 1, 'id_socio' => 2]);
    }

    public function testSacarAlumnos()
    {
        $socio1 = factory(Socio::class)->create();
        $socio2 = factory(Socio::class)->create();
        $data = ['id' => 1, 'alumnos' => [$socio1->id, $socio2->id]];
        $this->service->registrarAlumnos($data);

        $this->service->sacarAlumnos($data);
        $this->assertDatabaseMissing('clases_socios', ['id_clase' => 1, 'id_socio' => 1]);
        $this->assertDatabaseMissing('clases_socios', ['id_clase' => 1, 'id_socio' => 2]);
    }


}
