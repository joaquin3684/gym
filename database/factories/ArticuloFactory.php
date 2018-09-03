<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 03/09/18
 * Time: 10:56
 */
$factory->define(App\Articulo::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'precio' => $faker->randomNumber(4),
        'nombre' => $faker->name,
        'vencimiento' => null,
        'tipo' => 'articulo'
    ];
});