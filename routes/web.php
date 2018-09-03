<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('prueba', function() {
    $data = ['nombre' => 'pepe', 'apellido' => 'argento', 'celular' => '15-46727473', 'domicilio' => 'tres arroyos 3684', 'dni' => 39624527, 'fecha_nacimiento' => '2017-02-04'];
    $sus = new \App\Suscripcion(['fecha' => '2017-02-03', 'precio' => 30, 'id_socio' => 1, 'nombre' => 'focus', 'vencimiento' => '2018-02-03']);
    $sus->save();
    //\App\Suscripcion::create(['fecha' => '2017-02-03', 'precio' => 30, 'id_socio' => 1, 'nombre' => 'focus', 'vencimiento' => '2018-02-03']);
   return \App\Suscripcion::all();
});
