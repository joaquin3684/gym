<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 18/09/18
 * Time: 11:28
 */
$factory->define(App\Servicio::class, function (Faker\Generator $faker) {

    return [
        'nombre' => '50%',
        'creditos_minimos' => 1,
    ];
});