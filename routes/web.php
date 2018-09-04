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

});


//SOCIO

Route::post('socio/crear', 'SocioController@store');
Route::post('socio/comprar', 'SocioController@comprar');
Route::get('socio/all', 'SocioController@all');
Route::put('socio/editar/{id}', 'SocioController@update');
Route::get('socio/find/{id}', 'SocioController@show');


//CAJA

Route::post('caja/ingres', 'CajaController@ingreso');
Route::post('caja/egreso', 'CajaController@egreso');
Route::post('caja/movimientos', 'CajaController@movimientos');


//VENDIBLES

Route::get('vendibles/clases', 'VendiblesController@clases');
Route::get('vendibles/articulos', 'VendiblesController@articulos');

//VENTAS

Route::post('ventas/all', 'VentaController@ventas');
