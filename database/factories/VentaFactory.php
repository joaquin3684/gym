<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 03/09/18
 * Time: 11:29
 */
$factory->define(App\Venta::class, function (Faker\Generator $faker) {

    return [
        'fecha' => \Carbon\Carbon::today()->toDateString(),
        'precio' => $faker->randomNumber(3),
        'id_socio' => 1,
        'id_vendible' => 1,
        'cantidad' => 1

    ];
});