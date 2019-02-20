<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 20/02/19
 * Time: 19:30
 */

namespace App\services;


use App\Horario;
use App\Servicio;

class HorarioService
{
    public function crear(Servicio $servicio, $dia, $desde, $hasta, $entradaDesde, $entradaHasta, $profesores)
    {
        $horario = new Horario();
        $horario->id_servicio = $servicio->id;
        $horario->dia = $dia;
        $horario->desde = $desde;
        $horario->hasta = $hasta;
        $horario->entrada_desde = $entradaDesde;
        $horario->entrada_hasta = $entradaHasta;
        $horario->save();
        $horario->profesores()->attach($profesores);
    }

    public function delete(Horario $horario)
    {
        $horario->profesores()->detach();
        $horario->delete();
    }
}