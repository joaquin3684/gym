<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $table = 'ventas';
    protected $fillable = ['fecha', 'precio', 'id_socio', 'id_membresia', 'cantidad', 'id_descuento_membresia', 'id_descuento_socio'];

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
