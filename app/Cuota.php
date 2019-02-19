<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cuota extends Model
{
    protected $table = 'cuotas';
    protected $fillable = ['pago', 'pagada', 'fecha_inicio', 'fecha_vto', 'id_venta', 'nro_cuota'];

    public function venta()
    {
       return $this->belongsTo('App\Venta', 'id_venta', 'id');
    }
}
