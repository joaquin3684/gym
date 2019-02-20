<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 20/02/19
 * Time: 20:19
 */
$factory->define(App\Horario::class, function (Faker\Generator $faker) {

    return [
        'id_servicio' => 1,
        'dia' => 1,
        'desde' => '7:00:00',
        'hasta' => '15:00:00',
        'entrada_desde' => '7:00:00',
        'entrada_hasta' => '15:00:00',
    ];
});