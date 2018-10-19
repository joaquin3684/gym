<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Accesos extends Model
{
    protected $table = 'accesos';
    protected $fillable = ['id_socio', 'id_servicio'];

    public function socio()
    {
        return $this->belongsTo('App\Socio', 'id_socio', 'id');
    }

    public function servicio()
    {
        return $this->belongsTo('App\Servicio', 'id_servicio', 'id');
    }
}
