<?php

use Illuminate\Http\Request;


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', 'LoginController@login');


Route::group(['middleware' => ['permisos', 'jwt.auth']], function() {

//SOCIO

    Route::post('socio/crear', 'SocioController@store');
    Route::post('socio/comprar', 'SocioController@comprar');
    Route::get('socio/all', 'SocioController@all');
    Route::put('socio/editar/{id}', 'SocioController@update');
    Route::get('socio/find/{id}', 'SocioController@show');
    Route::post('socio/acceder', 'SocioController@acceder');
    Route::post('socio/registrarEntradaAServicio', 'SocioController@registrarEntradaAServicio');

//CAJA

    Route::post('caja/ingreso', 'CajaController@ingreso');
    Route::post('caja/egreso', 'CajaController@egreso');
    Route::post('caja/movimientos', 'CajaController@movimientos');


//MEMBRESIA

    Route::get('membresia/all', 'MembresiaController@membresias');
    Route::post('membresia/crear', 'MembresiaController@store');
    Route::put('membresia/editar/{id}', 'MembresiaController@update');
    Route::get('membresia/find/{id}', 'MembresiaController@find');
    Route::post('membresia/borrar', 'MembresiaController@delete');

//VENTAS

    Route::post('ventas/all', 'VentaController@ventas');

 // ACCESOS

    Route::post('accesos/all', 'AccesosController@accesos');

 // SERVICIOS

    Route::get('servicio/all', 'ServicioController@servicios');
    Route::post('servicio/crear', 'ServicioController@store');
    Route::put('servicio/editar/{id}', 'ServicioController@update');
    Route::get('servicio/find/{id}', 'ServicioController@find');
    Route::post('servicio/borrar', 'ServicioController@delete');

  // DESCUENTOS

    Route::get('descuento/all', 'DescuentoController@descuentos');
    Route::post('descuento/crear', 'DescuentoController@store');
    Route::put('descuento/editar/{id}', 'DescuentoController@update');
    Route::get('descuento/find/{id}', 'DescuentoController@find');
    Route::post('descuento/borrar', 'DescuentoController@delete');
});