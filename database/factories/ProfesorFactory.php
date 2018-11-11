<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 30/10/18
 * Time: 19:38
 */
$factory->define(App\Profesor::class, function (Faker\Generator $faker) {

    return [
        'nombre' => $faker->firstName,
        'apellido' => $faker->lastName,
        'telefono' => $faker->phoneNumber,
        'email' => $faker->email,
        'domicilio' => $faker->address,
        'fecha_cobro_dia' => 1,
        'cantidad_dias_cobro' => 0,
        'fijo' => 100
    ];
});