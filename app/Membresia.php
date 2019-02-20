<?php

namespace App;

use App\services\CajaService;
use App\services\VentaService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Membresia extends Model
{
    use SoftDeletes;

    protected $table = 'membresias';
    protected $fillable = ['precio', 'nombre', 'vencimiento_dias', 'nro_cuotas'];


    public function cuotas()
    {
        return $this->hasManyThrough('App\Cuota', 'App\Venta', 'id_membresia', 'id_venta', 'id', 'id');
    }

    public function descuentos()
    {
        return $this->belongsToMany('App\Descuento', 'membresia_descuento', 'id_membresia', 'id_descuento');
    }

    public function servicios()
    {
        return $this->belongsToMany('App\Servicio', 'membresia_servicio','id_membresia', 'id_servicio')->withPivot('creditos', 'vto');
    }
    
    public function adjuntarServicios(Socio $socio)
    {
        $ids = array();
        $vtoMembresia = $this->generarVencimiento($socio);
        foreach($this->servicios as $servicio)
        {
            $ids[$servicio->id] = ['creditos' => $servicio->pivot->creditos, 'vto' => Carbon::createFromFormat('Y-m-d',$vtoMembresia)->subDays($this->vencimiento_dias)->addDays($servicio->pivot->vto)->toDateString()];
        }

        $socio->servicios()->attach($ids);
    }




}
