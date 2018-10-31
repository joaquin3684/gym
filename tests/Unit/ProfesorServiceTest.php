<?php

namespace Tests\Unit;

use App\Profesor;
use App\services\ProfesorService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProfesorServiceTest extends TestCase
{
    use DatabaseMigrations;

    private $service;
    public function setUp()
    {
        parent::setUp();
        $this->service = new ProfesorService();
        $this->artisan('migrate', ['--database' => 'mysql_testing']);

    }

    public function testCrearProfesor()
    {
        $data = factory(Profesor::class)->make()->toArray();
        $this->service->crear($data);
        $this->assertDatabaseHas('profesores', $data);
    }

    public function testUpdateProfesor()
    {

        $data = factory(Profesor::class)->make()->toArray();
        factory(\App\Profesor::class, 3)->create();

        $this->service->update($data, 1);
        unset($data['dias']);
        $this->assertDatabaseHas('profesores', $data);
    }

    public function testFindProfesor()
    {
        factory(\App\Profesor::class, 3)->create();


        $profesor = $this->service->find(1);
        $this->assertEquals(false, is_null($profesor));
    }

    public function testDeleteProfesor()
    {
        factory(\App\Profesor::class, 3)->create();

        $this->service->delete(1);
        $this->assertSoftDeleted('profesores', ['id' => 1]);
    }

    public function testTraerProfesores()
    {
        factory(\App\Profesor::class, 3)->create();
        $profesores = $this->service->all();
        $this->assertEquals(3, $profesores->count());
    }

}
