<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 18/09/18
 * Time: 11:28
 */
$factory->define(App\Descuento::class, function (Faker\Generator $faker) {

    return [
        'nombre' => '50%',
        'porcentaje' => 50,
        'vencimiento_dias' => 30,
        'aplicable_enconjunto' => false,
        'tipo' => 1
    ];
});