<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 24/10/18
 * Time: 11:08
 */
$factory->define(App\Clase::class, function (Faker\Generator $faker) {

    return [
            'fecha' => \Carbon\Carbon::today()->toDateString(),
            'dia' => 'Lunes',
            'id_servicio' => 1,
            'estado' => 1,
            'desde' => '01:00:00',
            'hasta' => '22:00:00',
            'entrada_desde' => '01:00:00',
            'entrada_hasta' => '22:00:00',
            'id_dia' => 1
    ];
});