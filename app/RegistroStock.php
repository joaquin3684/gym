<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RegistroStock extends Model
{
    protected $table = 'registro_stock';
    protected $fillable = ['cantidad', 'precio', 'observacion', 'id_producto', 'tipo_pago', 'id_usuario', 'tipo', 'fecha'];

    public function producto()
    {
        return $this->belongsTo('App\Producto', 'id_producto', 'id');
    }

    public function usuario()
    {
        return $this->belongsTo('App\User', 'id_usuario', 'id');
    }

}
