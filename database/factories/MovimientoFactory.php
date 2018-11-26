<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 03/09/18
 * Time: 11:51
 */
$factory->define(App\Movimiento::class, function (Faker\Generator $faker) {

    return [
        'ingreso' => 100,
        'egreso' => 0,
        'fecha' => \Carbon\Carbon::today()->toDateString(),
        'observacion' => null,
        'tipo_pago' => 'Efectivo',
        'concepto' => 'prueba',
        'id_usuario' => 1

    ];
});

$factory->state(App\Movimiento::class, 'egreso', function ($faker) {
    return [
        'ingreso' => 0,
        'egreso' => 100,
        'fecha' => \Carbon\Carbon::today()->toDateString(),
        'observacion' => null,
        'tipo_pago' => 'Efectivo',
        'concepto' => 'prueba',
        'id_usuario' => 1

    ];
});