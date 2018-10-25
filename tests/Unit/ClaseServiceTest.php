<?php

namespace Tests\Unit;

use App\services\ClaseService;
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
    }

    public function testUpdateClase()
    {

        factory(\App\Clase::class)->create();
        $data = [
            'fecha' => \Carbon\Carbon::today()->addDay()->toDateString(),
            'hora' => '12:00:00',
            'dia' => 'Martes',
            'id_servicio' => 2,
            'estado' => 2

        ];

        $this->service->update($data, 1);
        $this->assertDatabaseHas('clases', $data);
    }

    public function testUpdateDescuento()
    {
        factory(\App\Descuento::class)->create();
        $data = ['nombre' => 'prueba', 'porcentaje' => 50, 'vencimiento_dias' => 30, 'aplicable_enconjunto' => true, 'id' => 1, 'tipo' => 1];

        $this->service->update($data, $data['id']);
        $this->assertDatabaseHas('descuentos', $data);

    }


}
