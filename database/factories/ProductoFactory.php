<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 25/11/18
 * Time: 21:55
 */
$factory->define(App\Producto::class, function (Faker\Generator $faker) {

    return [
        'nombre' => $faker->name,
        'precio_venta' => $faker->randomNumber(),
        'precio_compra' => $faker->randomNumber(),
        'punto_reposicion' => $faker->randomNumber(),
        'cantidad' => $faker->randomNumber()
    ];
});