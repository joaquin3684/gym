<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 01/09/18
 * Time: 13:03
 */

$factory->define(App\Socio::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'nombre' => $faker->name,
        'apellido' => $faker->lastName,
        'celular' => $faker->phoneNumber,
        'domicilio' => $faker->address,
        'dni' => $faker->randomNumber(8),
        'fecha_nacimiento' => $faker->date('Y-m-d')

    ];
});