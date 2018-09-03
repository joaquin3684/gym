<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $table = 'ventas';
    protected $fillable = ['fecha', 'precio', 'id_socio', 'id_vendible', 'cantidad'];

    public function vendible()
    {
        return $this->belongsTo('App\Vendible', 'id_vendible', 'id');
    }

    public function socio()
    {
        return $this->belongsTo('App\Socio', 'id_socio', 'id');
    }
}
