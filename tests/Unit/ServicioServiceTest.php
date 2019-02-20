<?php

namespace Tests\Unit;

use App\Horario;
use App\services\ServicioService;
use App\Servicio;
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
        $this->artisan('db:seed', ['--class' => 'ProfesoresSeeder', '--database' => 'mysql_testing']);

    }

    public function testCrearServicio()
    {

        $data = [
            'nombre' => 'prueba',
            'creditos_minimos' => 1,
            'registra_entrada' => 1,
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
        $this->service->crear($data['nombre'], $data['creditos_minimos'], 1, $data['dias']);
        unset($data['dias']);
        $this->assertDatabaseHas('servicios', $data);
        $this->assertDatabaseHas('horarios', ['id' => 1, 'id_servicio' => 1, 'dia' => 1, 'desde' => '17:00:00', 'hasta' => '18:00:00', 'entrada_desde' => '16:45:00', 'entrada_hasta' => '17:15:00']);
        $this->assertDatabaseHas('horarios', ['id' => 2, 'id_servicio' => 1, 'dia' => 1, 'desde' => '17:00:00', 'hasta' => '18:00:00', 'entrada_desde' => '16:45:00', 'entrada_hasta' => '17:15:00']);
        $this->assertDatabaseHas('horarios', ['id' => 3, 'id_servicio' => 1, 'dia' => 2, 'desde' => '17:00:00', 'hasta' => '18:00:00', 'entrada_desde' => '16:45:00', 'entrada_hasta' => '17:15:00']);
        $this->assertDatabaseHas('horarios', ['id' => 4, 'id_servicio' => 1, 'dia' => 2, 'desde' => '17:00:00', 'hasta' => '18:00:00', 'entrada_desde' => '16:45:00', 'entrada_hasta' => '17:15:00']);


        $this->assertDatabaseHas('horario_profesor', ['id_horario' => 1, 'id_profesor' => 1]);
        $this->assertDatabaseHas('horario_profesor', ['id_horario' => 1, 'id_profesor' => 2]);
        $this->assertDatabaseHas('horario_profesor', ['id_horario' => 2, 'id_profesor' => 1]);
        $this->assertDatabaseHas('horario_profesor', ['id_horario' => 2, 'id_profesor' => 2]);
        $this->assertDatabaseHas('horario_profesor', ['id_horario' => 3, 'id_profesor' => 1]);
        $this->assertDatabaseHas('horario_profesor', ['id_horario' => 3, 'id_profesor' => 2]);
        $this->assertDatabaseHas('horario_profesor', ['id_horario' => 4, 'id_profesor' => 1]);
        $this->assertDatabaseHas('horario_profesor', ['id_horario' => 4, 'id_profesor' => 2]);
    }

    public function testUpdateServicio()
    {

        $data = [
            'id' => 1,
            'nombre' => 'prueba',
            'creditos_minimos' => 1,
            'registra_entrada' => 1,
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
            $horario = Horario::create(['dia' => 1, 'desde' => '17:00:00', 'hasta' => '18:00:00', 'entrada_desde' => '16:45:00', 'entrada_hasta' => '17:15:00', 'id_servicio' => $ser->id]);
            $horario->profesores()->attach(1);
        });

        $this->service->update($data['nombre'], $data['creditos_minimos'], 1, $data['dias'], Servicio::find($data['id']));
        unset($data['dias']);
        $this->assertDatabaseHas('servicios', $data);
        $this->assertDatabaseHas('horarios', ['id' => 4, 'id_servicio' => 1, 'dia' => 1, 'desde' => '17:00:00', 'hasta' => '18:00:00', 'entrada_desde' => '16:45:00', 'entrada_hasta' => '17:15:00']);
        $this->assertDatabaseHas('horarios', ['id' => 5, 'id_servicio' => 1, 'dia' => 1, 'desde' => '17:00:00', 'hasta' => '18:00:00', 'entrada_desde' => '16:45:00', 'entrada_hasta' => '17:15:00']);
        $this->assertDatabaseHas('horarios', ['id' => 6, 'id_servicio' => 1, 'dia' => 2, 'desde' => '17:00:00', 'hasta' => '18:00:00', 'entrada_desde' => '16:45:00', 'entrada_hasta' => '17:15:00']);
        $this->assertDatabaseHas('horarios', ['id' => 7, 'id_servicio' => 1, 'dia' => 2, 'desde' => '17:00:00', 'hasta' => '18:00:00', 'entrada_desde' => '16:45:00', 'entrada_hasta' => '17:15:00']);


        $this->assertDatabaseHas('horario_profesor', ['id_horario' => 4, 'id_profesor' => 1]);
        $this->assertDatabaseHas('horario_profesor', ['id_horario' => 4, 'id_profesor' => 2]);
        $this->assertDatabaseHas('horario_profesor', ['id_horario' => 5, 'id_profesor' => 1]);
        $this->assertDatabaseHas('horario_profesor', ['id_horario' => 5, 'id_profesor' => 2]);
        $this->assertDatabaseHas('horario_profesor', ['id_horario' => 6, 'id_profesor' => 1]);
        $this->assertDatabaseHas('horario_profesor', ['id_horario' => 6, 'id_profesor' => 2]);
        $this->assertDatabaseHas('horario_profesor', ['id_horario' => 7, 'id_profesor' => 1]);
        $this->assertDatabaseHas('horario_profesor', ['id_horario' => 7, 'id_profesor' => 2]);
    }

    public function testFindServicio()
    {
        factory(\App\Servicio::class, 3)->create()->each(function($ser){
            $horario = Horario::create(['dia' => 1, 'desde' => '17:00:00', 'hasta' => '18:00:00', 'entrada_desde' => '16:45:00', 'entrada_hasta' => '17:15:00', 'id_servicio' => $ser->id]);
            $horario->profesores()->attach(1);
        });


        $servicio = $this->service->find(1);
        $this->assertEquals(false, is_null($servicio));
    }

    public function testDelete()
    {
        factory(\App\Servicio::class, 3)->create()->each(function($ser){
            $horario = Horario::create(['dia' => 1, 'desde' => '17:00:00', 'hasta' => '18:00:00', 'entrada_desde' => '16:45:00', 'entrada_hasta' => '17:15:00', 'id_servicio' => $ser->id]);
            $horario->profesores()->attach(1);
        });


        $this->service->delete(Servicio::find(1));
        $this->assertSoftDeleted('servicios', ['id' => 1]);
        $this->assertDatabaseMissing('horarios', ['id_servicio' => 1]);
        $this->assertDatabaseMissing('horario_profesor', ['id_horario' => 1]);
    }

    public function testTraerServicios()
    {
        factory(\App\Servicio::class, 3)->create()->each(function($ser){
            $horario = Horario::create(['dia' => 1, 'desde' => '17:00:00', 'hasta' => '18:00:00', 'entrada_desde' => '16:45:00', 'entrada_hasta' => '17:15:00', 'id_servicio' => $ser->id]);
            $horario->profesores()->attach(1);
        });
        $servicios = $this->service->servicios();
        $this->assertEquals(3, $servicios->count());
    }
}
