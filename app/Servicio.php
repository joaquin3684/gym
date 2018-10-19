<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Servicio extends Model
{
    use SoftDeletes;
    protected $table = 'servicios';
    protected $fillable = ['nombre', 'creditos_minimos'];

    public function dias()
    {
        return $this->belongsToMany('App\Dia', 'servicio_dia', 'id_servicio', 'id_dia')->withPivot('desde', 'hasta', 'entrada_desde', 'entrada_hasta');
    }


    public function registrarEntrada(Socio $socio)
    {
        if ($this->pivot->creditos != null) {
            $creditos = --$this->pivot->creditos;
            $socio->servicios()->updateExistingPivot($this->id, ['creditos' => $creditos]);
        }

        Accesos::create(['id_socio' => $socio->id, 'id_servicio' => $this->id]);
    }

    public function devolverEntrada(Socio $socio)
    {
        if($socio->pivot->creditos != null )
        {
            $creditos = $socio->pivot->creditos++;
            $socio->servicios()->updateExistingPivot($this->id, ['creditos' => $creditos]);
        }
        
        Accesos::where('id_socio', $socio->id)->where('id_servicio', $this->id)->orderByDesc('created_at')->first()->delete();
    }


}
