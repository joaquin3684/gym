<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $table = 'ventas';
    protected $fillable = ['fecha', 'precio', 'id_socio', 'id_membresia', 'cantidad', 'id_descuento_membresia', 'id_descuento_socio', 'vto'];

    public function membresia()
    {
        return $this->belongsTo('App\Membresia', 'id_membresia', 'id');
    }

    public function servicios()
    {
        return $this->belongsToMany('App\Servicio', 'venta_servicio', 'id_venta', 'id_servicio')->withPivot('creditos', 'vto', 'id');
    }

    public function socio()
    {
        return $this->belongsTo('App\Socio', 'id_socio', 'id');
    }

    public function descuentoMembresia()
    {
        return $this->belongsTo('App\Descuento', 'id_descuento_membresia', 'id');
    }

    public function descuentoSocio()
    {
        return $this->belongsTo('App\Descuento', 'id_descuento_socio', 'id');
    }

    public function cuotas()
    {
        return $this->hasMany('App\Cuota', 'id_venta', 'id');
    }
}
