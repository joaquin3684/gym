<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SocioMembresia extends Model
{
    protected $table = 'socio_membresia';
    protected $fillable = ['id_socio', 'id_membresia', 'vto'];

    public function cuotas()
    {
        return $this->hasMany('App\Cuota', 'id_socio_membresia', 'id');
    }
}
