<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $table = 'ventas';
    protected $fillable = ['fecha', 'precio', 'id_socio', 'id_vendible', 'cantidad', 'id_descuento'];

    public function membresia()
    {
        return $this->belongsTo('App\Membresia', 'id_membresia', 'id');
    }

    public function socio()
    {
        return $this->belongsTo('App\Socio', 'id_socio', 'id');
    }

    public function descuento()
    {
        return $this->belongsTo('App\Descuento', 'id_descuento', 'id');
    }
}
