<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Descuento extends Model
{
    use SoftDeletes;
    protected $table = 'descuentos';
    protected $fillable = ['nombre', 'porcentaje', 'vencimiento_dias', 'aplicable_enconjunto'];

    public function membresias()
    {
        return $this->belongsToMany('App\Membresia', 'membresia_descuento', 'id_membresia', 'id_descuento');
    }

    public function esAplicableEnConjunto()
    {
        return $this->aplicable_enconjunto;
    }
}
