<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 22/10/18
 * Time: 18:29
 */

namespace App\services;


use App\Clase;
use App\Socio;
use Carbon\Carbon;

class ClaseService
{
    public function update($elem, $id)
    {
        $clase = Clase::find($id);
        $clase->fill($elem);
        $clase->save();
        $clase->profesores()->sync($elem['profesores']);
    }

    public function crear($elem)
    {
        $clase = Clase::create($elem);
        $clase->profesores()->attach($elem['profesores']);
        return $clase->id;
    }

    public function registrarAlumnos($elem)
    {
        $clase = Clase::find($elem['id']);
        $clase->alumnos()->attach($elem['alumnos']);
    }

    public function sacarAlumnos($elem)
    {
        $clase = Clase::find($elem['id']);
        $clase->alumnos()->detach($elem['alumnos']);
    }

    public function all()
    {
        return Clase::with('servicio', 'profesores', 'alumnos')->get();
    }

    public function clasesDelDia()
    {
        return Clase::with('profesores', 'servicio', 'alumnos')->where('fecha', Carbon::today()->toDateString())->get();
    }

    public function clasesEnTranscurso()
    {
        $ahora = Carbon::now()->toTimeString();
        return Clase::with('profesores', 'servicio', 'alumnos')->where('entrada_desde', '<=', $ahora)->where('hasta', '>=', $ahora)->where('fecha', Carbon::today()->toDateString());
    }

    public function clasesFuturas()
    {
        return Clase::with('profesores', 'servicio', 'alumnos')->where('fecha', '>', Carbon::today()->toDateString());
    }

    public function registrarEntradas($elem)
    {
        $cl = $elem['clase'];
        $clase = Clase::find($cl);
        foreach ($elem['socios'] as $socio) {
            $soc = Socio::find($socio);
           $clase->registrarEntrada($soc);

        }
    }

    public function devolverEntradas($elem)
    {
        $clases = $elem['clases'];
        foreach ($elem['socios'] as $socio) {
            $soc = Socio::with(['servicios', 'clases' => function ($q) use ($clases) {
                $q->whereIn('id_clase', $clases);
            }])->find($socio);

            $soc->clases->each(function(Clase $clase) use ($soc){
                $clase->devolverEntrada($soc);

            });
        }
    }

}