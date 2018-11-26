<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    protected $table = 'movimientos';
    protected $fillable = ['ingreso', 'egreso', 'fecha', 'observacion', 'tipo_pago', 'concepto', 'id_usuario'];

    public function usuario()
    {
        return $this->belongsTo('App\User', 'id_usuario');
    }
}
