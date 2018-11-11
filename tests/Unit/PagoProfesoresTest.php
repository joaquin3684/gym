<?php

namespace Tests\Unit;

use App\services\ProfesorService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PagoProfesoresTest extends TestCase
{
    use DatabaseMigrations;

    private $service;
    public function setUp()
    {
        parent::setUp();
        $this->service = new ProfesorService();
        $this->artisan('migrate', ['--database' => 'mysql_testing']);
        $this->artisan('db:seed', ['--class' => 'PagoProfesoresSeeder', '--database' => 'mysql_testing']);

    }

    public function testProfesoresQueHayQuePagarlesDonde()
    {
        $elem = [1 => [1,2], 2=> [1,2]];
        $data = $this->service->pagar($elem);
        $this->assertDatabaseHas('profesores', $data);
    }


}
