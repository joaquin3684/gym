<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 03/09/18
 * Time: 10:48
 */
$factory->define(App\Clase::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'precio' => $faker->randomNumber(4),
        'nombre' => $faker->name,
        'vencimiento' => $faker->randomDigit,
        'tipo' => 'suscripcion'
    ];
});