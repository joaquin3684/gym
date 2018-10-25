<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 22/10/18
 * Time: 18:29
 */

namespace App\services;


use App\Clase;
use Carbon\Carbon;

class ClaseService
{
    public function update($elem, $id)
    {
        $clase = Clase::find($id);
        $clase->fill($elem);
        $clase->save();
    }

    public function crear($elem)
    {
        $clase = Clase::create($elem);
    }

    public function registrarAlumnos($elem)
    {

    }

    public function sacarAlumnos($elem)
    {

    }

    public function clasesDelDia()
    {
        return Clase::where('fecha', Carbon::today()->toDateString());
    }

    public function clasesEnTranscurso()
    {
        $ahora = Carbon::now()->toTimeString();
        return Clase::where('desde', '<=', $ahora)->where('hasta', '>=', $ahora)->where('fecha', Carbon::today()->toDateString());
    }

    public function clasesFuturas()
    {
        return Clase::where('fecha', '>', Carbon::today()->toDateString());
    }

}