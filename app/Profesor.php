<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profesor extends Model
{
    use SoftDeletes;
    protected $table = 'profesores';
    protected $fillable = ['nombre', 'apellido', 'telefono', 'email', 'domicilio'];

}
