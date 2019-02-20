<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 17/09/18
 * Time: 11:10
 */

namespace App\services;


use App\Accesos;
use App\Servicio;
use App\Horario;
use App\Socio;

class ServicioService
{

    private $horarioSrv;
    public function __construct()
    {
        $this->horarioSrv = new HorarioService();
    }

    public function crear($nombre, $creditosMinimos, $registraEntrada, $dias)
    {
        $servicio = new Servicio();
        $servicio->nombre =  $nombre;
        $servicio->creditos_minimos = $creditosMinimos;
        $servicio->registra_entrada = $registraEntrada;
        $servicio->save();

        foreach ($dias as $dia) {
            foreach($dia['horarios'] as $horario)
                $this->horarioSrv->crear($servicio, $dia['id'], $horario['desde'], $horario['hasta'], $horario['entrada_desde'], $horario['entrada_hasta'], $horario['profesores']);
        }

        return $servicio->id;
    }

    public function update($nombre, $creditosMinimos, $registraEntrada, $dias, Servicio $servicio)
    {

        $servicio->nombre = $nombre;
        $servicio->creditos_minimos = $creditosMinimos;
        $servicio->registra_entrada = $registraEntrada;
        $servicio->save();

        $horarios = Horario::where('id_servicio', $servicio->id)->get();
        $horarios->each(function($horario){
            $this->horarioSrv->delete($horario);
        });

        foreach ($dias as $dia) {
            foreach($dia['horarios'] as $horario)
                $this->horarioSrv->crear($servicio, $dia['id'], $horario['desde'], $horario['hasta'], $horario['entrada_desde'], $horario['entrada_hasta'], $horario['profesores']);
        }
        return $servicio->id;
    }

    public function delete(Servicio $servicio)
    {

        $horarios = Horario::where('id_servicio', $servicio->id)->get();
        $horarios->each(function($horario){
            $this->horarioSrv->delete($horario);
        });
        $servicio->delete();
    }

    public function find($id)
    {
        return Servicio::with('horarios.profesores')->find($id);
    }

    public function servicios()
    {
        return Servicio::with('horarios.profesores')->get();
    }

    public function devolverEntradas($elem)
    {
        $servicios = $elem['servicios'];
        foreach ($elem['socios'] as $socio) {
            $soc = Socio::with(['servicios' => function ($q) use ($servicios) {
                $q->whereIn('id_servicio', $servicios);
            }])->find($socio);
            $serv = $soc->servicios->first();
            $serv->devolverEntrada($soc);
        }
    }

    public function registrarEntradas($elem)
    {
        $servicios = $elem['servicios'];
        foreach ($elem['socios'] as $socio) {
            $soc = Socio::with(['servicios' => function ($q) use ($servicios) {
                $q->whereIn('id_servicio', $servicios);
            }])->find($socio);
            $serv = $soc->servicios->first();
            $serv->registrarEntrada($soc);
        }
    }



}