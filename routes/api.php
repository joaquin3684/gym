<?php

use App\Clase;
use App\ServicioProfesorDia;
use Illuminate\Http\Request;


Route::get('a', function(){
   return 1;
});

Route::get('prueba', function(){
    $spd = ServicioProfesorDia::with('servicio', 'profesor', 'dia')->get();
    $serv = $spd->map(function($s){
        $ser = $s->servicio;
        return $ser;
    })->unique(function($serv){
        return $serv->id;
    });

    $serv = $serv->map(function($ser) use ($spd){
        $filtroPorElServicioActual = $spd->filter(function($s) use ($ser){return $s->id_servicio == $ser->id;});
        $ser->dias = $filtroPorElServicioActual->map(function($s) use ($spd){


            $s->profesores = $filtroPorDiaYHoraYservicio = $spd->filter(function($s2) use ($s){
                return $s2->id_servicio == $s->id_servicio && $s2->id_dia == $s->id_dia && $s2->desde == $s->desde && $s2->hasta == $s->hasta && $s2->entrada_desde == $s->entrada_desde && $s2->entrada_hasta == $s->entrada_hasta;
            })->map(function($s){ return $s->profesor;});

            return $s;
        })->unique(function($s){ $s->id_servicio.$s->id_dia.$s->desde.$s->hasta.$s->entrada_desde.$s->entrada_hasta;});

        return $ser;
    });


    $serv->each(function($servicio){
        $servicio->dias->each(function($dia) use ($servicio){
            Clase::create(['fecha' => Carbon::today()->toDateString(), 'dia' => $dia->id_dia, 'id_servicio' => $servicio->id, 'estado' => 1, 'desde' => $dia->desde, 'hasta' => $dia->hasta, 'entrada_desde' => $dia->entrada_desde, 'entrada_hasta' => $dia->entrada_hasta, 'id_dia' => $dia->id_dia]);

        });

    });

    return 1;
});

Route::post('login', 'LoginController@login');


Route::group(['middleware' => ['permisos', 'jwt.auth']], function() {

    //SOCIO
    Route::post('socio/acceder', 'SocioController@acceder');
    Route::post('socio/borrarMembresia', 'SocioController@borrarMembresia');

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
    Route::post('ventas/crear', 'VentaController@crear');
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


    // CLASES

    Route::put('clase/editar/{id}', 'ClaseController@update');
    Route::post('clase/crear', 'ClaseController@create');
    Route::get('clase/delDia', 'ClaseController@clasesDelDia');
    Route::get('clase/enTranscurso', 'ClaseController@clasesEnTranscurso');
    Route::get('clase/all', 'ClaseController@all');
    Route::get('clase/futuras', 'ClaseController@clasesFuturas');
    Route::post('clase/registrarAlumnos', 'ClaseController@registrarAlumnos');
    Route::post('clase/sacarAlumnos', 'ClaseController@sacarAlumnos');
    Route::post('clase/registrarEntrada', 'ClaseController@registrarEntrada');
    Route::post('clase/devolverEntrada', 'ClaseController@devolverEntrada');

    // PROFESOR

    Route::put('profesor/editar/{id}', 'ProfesorController@update');
    Route::get('profesor/all', 'ProfesorController@all');
    Route::get('profesor/find/{id}', 'ProfesorController@find');
    Route::post('profesor/borrar', 'ProfesorController@delete');
    Route::post('profesor/crear', 'ProfesorController@store');

    // PRODUCTOS

    Route::put('producto/editar/{id}', 'ProductoController@update');
    Route::get('producto/all', 'ProductoController@all');
    Route::get('producto/find/{id}', 'ProductoController@find');
    Route::post('producto/borrar', 'ProductoController@delete');
    Route::post('producto/crear', 'ProductoController@store');

    Route::post('producto/comprar', 'ProductoController@comprar');
    Route::post('producto/vender', 'ProductoController@vender');
    Route::post('producto/registrosDeStock', 'ProductoController@registrosDeStock');
});