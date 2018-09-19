<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 03/09/18
 * Time: 10:48
 */
$factory->define(App\Membresia::class, function (Faker\Generator $faker) {
    static $password;
    return [
        'precio' => 700,
        'nombre' => $faker->name,
        'vencimiento_dias' => 30,
        'nro_cuotas' => 1
    ];
});