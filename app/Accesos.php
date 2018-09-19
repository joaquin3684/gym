<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Accesos extends Model
{
    protected $table = 'accesos';
    protected $fillable = ['id_socio', 'id_servicio'];

}
