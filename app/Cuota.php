<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cuota extends Model
{
    protected $table = 'cuotas';
    protected $fillable = ['pago', 'pagada', 'fecha_inicio', 'fecha_vto', 'id_socio_membresia', 'nro_cuota'];
}
