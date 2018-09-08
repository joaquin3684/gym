<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

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


//CAJA

    Route::post('caja/ingres', 'CajaController@ingreso');
    Route::post('caja/egreso', 'CajaController@egreso');
    Route::post('caja/movimientos', 'CajaController@movimientos');


//VENDIBLES

    Route::get('vendibles/clases', 'VendiblesController@clases');
    Route::get('vendibles/articulos', 'VendiblesController@articulos');

//VENTAS

    Route::post('ventas/all', 'VentaController@ventas');

});