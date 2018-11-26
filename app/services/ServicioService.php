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
use App\ServicioProfesorDia;
use App\Socio;

class ServicioService
{
    public function crear($elem)
    {
        $servicio = new Servicio($elem);
        $servicio->save();
        $dias = $elem['dias'];
        foreach ($dias as $dia) {
            foreach($dia['horarios'] as $horario)
            {
                foreach($horario['profesores'] as $profesor)
                {
                    $a = ['id_dia' => $dia['id'], 'id_servicio' => $servicio->id, 'desde' => $horario['desde'], 'hasta' => $horario['hasta'], 'entrada_desde' => $horario['entrada_desde'], 'entrada_hasta' => $horario['entrada_hasta'], 'id_profesor' => $profesor];
                    ServicioProfesorDia::create($a);
                }
            }
        }
        return $servicio->id;
    }

    public function update($elem, $id)
    {
        $servicio = Servicio::find($id);
        $servicio->fill($elem);
        $servicio->save();
        $dias = $elem['dias'];
        ServicioProfesorDia::where('id_servicio', $servicio->id)->delete();

        foreach ($dias as $dia) {
            foreach($dia['horarios'] as $horario)
            {
                foreach($horario['profesores'] as $profesor)
                {
                    $a = ['id_dia' => $dia['id'], 'id_servicio' => $servicio->id, 'desde' => $horario['desde'], 'hasta' => $horario['hasta'], 'entrada_desde' => $horario['entrada_desde'], 'entrada_hasta' => $horario['entrada_hasta'], 'id_profesor' => $profesor];
                    ServicioProfesorDia::create($a);
                }
            }
        }
        return $servicio->id;
    }

    public function delete($id)
    {
        $servicio = Servicio::find($id);
        ServicioProfesorDia::where('id_servicio', $servicio->id)->delete();
        Servicio::destroy($id);
    }

    public function find($id)
    {
        return ServicioProfesorDia::with('servicio', 'profesor', 'dia')->where('id_servicio', $id)->get();
    }

    public function servicios()
    {
        return ServicioProfesorDia::with('servicio', 'profesor', 'dia')->get();
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