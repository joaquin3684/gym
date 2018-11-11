<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 11/11/18
 * Time: 00:28
 */
$factory->define(App\ServicioProfesorDia::class, function (Faker\Generator $faker) {

    return [
        'id_servicio' => 1,
        'id_profesor' => 1,
        'id_dia' => 1,
        'desde' => '02:00:00',
        'hasta',
        'entrada_desde',
        'entrada_hasta'
    ];

});