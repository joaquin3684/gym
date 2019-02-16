<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 17/10/18
 * Time: 17:10
 */
$factory->define(App\Cuota::class, function (Faker\Generator $faker) {

    return [
        'pago' => 100,
        'pagada' => false,
        'fecha_inicio' => '2017-02-03',
        'fecha_vto' => '2017-02-03',
        'id_venta' => 1,
        'nro_cuota' => 1
    ];
});