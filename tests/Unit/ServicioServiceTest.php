<?php

namespace Tests\Unit;

use App\services\ServicioService;
use Illuminate\Support\Facades\Artisan;
use SeguridadSeeder;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ServicioServiceTest extends TestCase
{

    use DatabaseMigrations;

    private $service;
    public function setUp()
    {
        parent::setUp();
        $this->service = new ServicioService();
        $this->artisan('migrate', ['--database' => 'mysql_testing']);
        $this->artisan('db:seed', ['--class' => 'DiasSeeder', '--database' => 'mysql_testing']);
        $this->artisan('db:seed', ['--class' => 'ProfesoresSeeder', '--database' => 'mysql_testing']);

    }

    public function testCrearServicio()
    {

        $data = [
            'nombre' => 'prueba',
            'creditos_minimos' => 1,
            'dias' => [
                [
                    'id' => 1,
                    'horarios'=> [
                        [
                            'desde' => '17:00:00',
                            'hasta' => '18:00:00',
                            'entrada_desde' => '16:45:00',
                            'entrada_hasta' => '17:15:00',
                            'profesores' => [1,2]
                        ],
                        [
                            'desde' => '17:00:00',
                            'hasta' => '18:00:00',
                            'entrada_desde' => '16:45:00',
                            'entrada_hasta' => '17:15:00',
                            'profesores' => [1,2]
                        ]
                    ]
                ],
                [
                    'id' => 2,
                    'horarios'=> [
                        [
                            'desde' => '17:00:00',
                            'hasta' => '18:00:00',
                            'entrada_desde' => '16:45:00',
                            'entrada_hasta' => '17:15:00',
                            'profesores' => [1,2]
                        ],
                        [
                            'desde' => '17:00:00',
                            'hasta' => '18:00:00',
                            'entrada_desde' => '16:45:00',
                            'entrada_hasta' => '17:15:00',
                            'profesores' => [1,2]
                        ]
                    ]
                ]
            ]
        ];
        $this->service->crear($data);
        unset($data['dias']);
        $this->assertDatabaseHas('servicios', $data);
        $this->assertDatabaseHas('servicio_profesor_dia', ['id_servicio' => 1, 'id_dia' => 1, 'id_profesor' => 1, 'desde' => '17:00:00', 'hasta' => '18:00:00', 'entrada_desde' => '16:45:00', 'entrada_hasta' => '17:15:00']);
        $this->assertDatabaseHas('servicio_profesor_dia', ['id_servicio' => 1, 'id_dia' => 1, 'id_profesor' => 1, 'desde' => '17:00:00', 'hasta' => '18:00:00', 'entrada_desde' => '16:45:00', 'entrada_hasta' => '17:15:00']);
        $this->assertDatabaseHas('servicio_profesor_dia', ['id_servicio' => 1, 'id_dia' => 1, 'id_profesor' => 2, 'desde' => '17:00:00', 'hasta' => '18:00:00', 'entrada_desde' => '16:45:00', 'entrada_hasta' => '17:15:00']);
        $this->assertDatabaseHas('servicio_profesor_dia', ['id_servicio' => 1, 'id_dia' => 1, 'id_profesor' => 2, 'desde' => '17:00:00', 'hasta' => '18:00:00', 'entrada_desde' => '16:45:00', 'entrada_hasta' => '17:15:00']);
        $this->assertDatabaseHas('servicio_profesor_dia', ['id_servicio' => 1, 'id_dia' => 2, 'id_profesor' => 1, 'desde' => '17:00:00', 'hasta' => '18:00:00', 'entrada_desde' => '16:45:00', 'entrada_hasta' => '17:15:00']);
        $this->assertDatabaseHas('servicio_profesor_dia', ['id_servicio' => 1, 'id_dia' => 2, 'id_profesor' => 1, 'desde' => '17:00:00', 'hasta' => '18:00:00', 'entrada_desde' => '16:45:00', 'entrada_hasta' => '17:15:00']);
        $this->assertDatabaseHas('servicio_profesor_dia', ['id_servicio' => 1, 'id_dia' => 2, 'id_profesor' => 2, 'desde' => '17:00:00', 'hasta' => '18:00:00', 'entrada_desde' => '16:45:00', 'entrada_hasta' => '17:15:00']);
        $this->assertDatabaseHas('servicio_profesor_dia', ['id_servicio' => 1, 'id_dia' => 2, 'id_profesor' => 2, 'desde' => '17:00:00', 'hasta' => '18:00:00', 'entrada_desde' => '16:45:00', 'entrada_hasta' => '17:15:00']);
    }

    public function testUpdateServicio()
    {

        $data = [
            'id' => 1,
            'nombre' => 'prueba',
            'creditos_minimos' => 1,
            'dias' => [
                [
                    'id' => 1,
                    'horarios'=> [
                        [
                            'desde' => '17:00:00',
                            'hasta' => '18:00:00',
                            'entrada_desde' => '16:45:00',
                            'entrada_hasta' => '17:15:00',
                            'profesores' => [1,2]
                        ],
                        [
                            'desde' => '17:00:00',
                            'hasta' => '18:00:00',
                            'entrada_desde' => '16:45:00',
                            'entrada_hasta' => '17:15:00',
                            'profesores' => [1,2]
                        ]
                    ]
                ],
                [
                    'id' => 2,
                    'horarios'=> [
                        [
                            'desde' => '17:00:00',
                            'hasta' => '18:00:00',
                            'entrada_desde' => '16:45:00',
                            'entrada_hasta' => '17:15:00',
                            'profesores' => [1,2]
                        ],
                        [
                            'desde' => '17:00:00',
                            'hasta' => '18:00:00',
                            'entrada_desde' => '16:45:00',
                            'entrada_hasta' => '17:15:00',
                            'profesores' => [1,2]
                        ]
                    ]
                ]
            ]
        ];
        factory(\App\Servicio::class, 3)->create()->each(function($ser){
            $ser->dias()->attach([
                1 => ['desde' => '17:00:00', 'hasta' => '18:00:00', 'entrada_desde' => '16:45:00', 'entrada_hasta' => '17:15:00', 'id_profesor' => 1]
            ]);
        });

        $this->service->update($data, $data['id']);
        unset($data['dias']);
        $this->assertDatabaseHas('servicios', $data);
        $this->assertDatabaseHas('servicio_profesor_dia', ['id_servicio' => 1, 'id_dia' => 1, 'id_profesor' => 1, 'desde' => '17:00:00', 'hasta' => '18:00:00', 'entrada_desde' => '16:45:00', 'entrada_hasta' => '17:15:00']);
        $this->assertDatabaseHas('servicio_profesor_dia', ['id_servicio' => 1, 'id_dia' => 1, 'id_profesor' => 1, 'desde' => '17:00:00', 'hasta' => '18:00:00', 'entrada_desde' => '16:45:00', 'entrada_hasta' => '17:15:00']);
        $this->assertDatabaseHas('servicio_profesor_dia', ['id_servicio' => 1, 'id_dia' => 1, 'id_profesor' => 2, 'desde' => '17:00:00', 'hasta' => '18:00:00', 'entrada_desde' => '16:45:00', 'entrada_hasta' => '17:15:00']);
        $this->assertDatabaseHas('servicio_profesor_dia', ['id_servicio' => 1, 'id_dia' => 1, 'id_profesor' => 2, 'desde' => '17:00:00', 'hasta' => '18:00:00', 'entrada_desde' => '16:45:00', 'entrada_hasta' => '17:15:00']);
        $this->assertDatabaseHas('servicio_profesor_dia', ['id_servicio' => 1, 'id_dia' => 2, 'id_profesor' => 1, 'desde' => '17:00:00', 'hasta' => '18:00:00', 'entrada_desde' => '16:45:00', 'entrada_hasta' => '17:15:00']);
        $this->assertDatabaseHas('servicio_profesor_dia', ['id_servicio' => 1, 'id_dia' => 2, 'id_profesor' => 1, 'desde' => '17:00:00', 'hasta' => '18:00:00', 'entrada_desde' => '16:45:00', 'entrada_hasta' => '17:15:00']);
        $this->assertDatabaseHas('servicio_profesor_dia', ['id_servicio' => 1, 'id_dia' => 2, 'id_profesor' => 2, 'desde' => '17:00:00', 'hasta' => '18:00:00', 'entrada_desde' => '16:45:00', 'entrada_hasta' => '17:15:00']);
        $this->assertDatabaseHas('servicio_profesor_dia', ['id_servicio' => 1, 'id_dia' => 2, 'id_profesor' => 2, 'desde' => '17:00:00', 'hasta' => '18:00:00', 'entrada_desde' => '16:45:00', 'entrada_hasta' => '17:15:00']);
    }

    public function testFindServicio()
    {
        factory(\App\Servicio::class, 3)->create()->each(function($ser){
            $ser->dias()->attach([
                1 => ['desde' => '17:00:00', 'hasta' => '18:00:00', 'entrada_desde' => '16:45:00', 'entrada_hasta' => '17:15:00', 'id_profesor' => 1],
                2 => ['desde' => '17:00:00', 'hasta' => '18:00:00', 'entrada_desde' => '16:45:00', 'entrada_hasta' => '17:15:00', 'id_profesor' => 2]
            ]);
            $ser->dias()->attach([
                1 => ['desde' => '18:00:00', 'hasta' => '20:00:00', 'entrada_desde' => '16:45:00', 'entrada_hasta' => '17:15:00', 'id_profesor' => 1],
                2 => ['desde' => '18:00:00', 'hasta' => '20:00:00', 'entrada_desde' => '16:45:00', 'entrada_hasta' => '17:15:00', 'id_profesor' => 2]
            ]);
        });


        $servicio = $this->service->find(1);
        $this->assertEquals(false, is_null($servicio));
    }

    public function testDelete()
    {
        factory(\App\Servicio::class, 3)->create()->each(function($ser){
            $ser->dias()->attach([
                1 => ['desde' => '17:00:00', 'hasta' => '18:00:00', 'entrada_desde' => '16:45:00', 'entrada_hasta' => '17:15:00'],
                2 => ['desde' => '17:00:00', 'hasta' => '18:00:00', 'entrada_desde' => '16:45:00', 'entrada_hasta' => '17:15:00']
            ]);
        });


        $this->service->delete(1);
        $this->assertSoftDeleted('servicios', ['id' => 1]);
        $this->assertDatabaseMissing('servicio_profesor_dia', ['id_servicio' => 1]);
    }

    public function testTraerServicios()
    {
        factory(\App\Servicio::class, 3)->create()->each(function($ser){
            $ser->dias()->attach([
                1 => ['desde' => '17:00:00', 'hasta' => '18:00:00', 'entrada_desde' => '16:45:00', 'entrada_hasta' => '17:15:00', 'id_profesor' => 1],
                2 => ['desde' => '17:00:00', 'hasta' => '18:00:00', 'entrada_desde' => '16:45:00', 'entrada_hasta' => '17:15:00', 'id_profesor' => 1]
            ]);
        });
        $servicios = $this->service->servicios();
        $this->assertEquals(6, $servicios->count());
    }
}
