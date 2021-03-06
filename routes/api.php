<?php

use Illuminate\Http\Request;


Route::get('prueba', function(){
    return "asjdflaksjd";
});

Route::post('login', 'LoginController@login');


Route::group(['middleware' => ['permisos', 'jwt.auth']], function() {

//SOCIO
    Route::post('socio/acceder', 'SocioController@acceder');
    Route::post('socio/comprar', 'SocioController@comprar');

    Route::post('socio/crear', 'SocioController@store');
    Route::get('socio/all', 'SocioController@all');
    Route::put('socio/editar/{id}', 'SocioController@update');
    Route::get('socio/find/{id}', 'SocioController@show');
    Route::get('socio/accesos/{id}', 'SocioController@accesos');

//CAJA

    Route::post('caja/ingreso', 'CajaController@ingreso');
    Route::post('caja/egreso', 'CajaController@egreso');
    Route::post('caja/movimientos', 'CajaController@movimientos');


//MEMBRESIA

    Route::get('membresia/all', 'MembresiaController@membresias');
    Route::get('membresia/allConTodo', 'MembresiaController@membresiasConTodo');
    Route::post('membresia/crear', 'MembresiaController@store');
    Route::put('membresia/editar/{id}', 'MembresiaController@update');
    Route::get('membresia/find/{id}', 'MembresiaController@find');
    Route::post('membresia/borrar', 'MembresiaController@delete');

//VENTAS

    Route::post('ventas/all', 'VentaController@ventas');
    Route::get('ventas/historialCompra/{idSocio}', 'VentaController@historialCompra');

 // ACCESOS

    Route::post('accesos/all', 'AccesosController@accesos');

 // SERVICIOS

    Route::get('servicio/all', 'ServicioController@servicios');
    Route::post('servicio/crear', 'ServicioController@store');
    Route::put('servicio/editar/{id}', 'ServicioController@update');
    Route::get('servicio/find/{id}', 'ServicioController@find');
    Route::post('servicio/borrar', 'ServicioController@delete');

    Route::post('servicio/registrarEntradas', 'ServicioController@registrarEntradas');
    Route::post('servicio/devolverEntradas', 'ServicioController@devolverEntradas');

  // DESCUENTOS

    Route::get('descuento/all', 'DescuentoController@descuentos');
    Route::post('descuento/crear', 'DescuentoController@store');
    Route::put('descuento/editar/{id}', 'DescuentoController@update');
    Route::get('descuento/find/{id}', 'DescuentoController@find');
    Route::post('descuento/borrar', 'DescuentoController@delete');
});