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
        'precio' => 100,
        'id_socio' => 1,
        'id_membresia' => 1,
        'cantidad' => 1,
        'id_descuento_membresia' => null,
        'id_descuento_socio' => null,
        'vto' => \Carbon\Carbon::today()->addMonth()->toDateString()
    ];

});