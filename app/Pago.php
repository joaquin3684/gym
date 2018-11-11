<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pago extends Model
{
    protected $table = 'pago_profesores';
    protected $fillable = ['id_profesor', 'pago', 'fecha'];

}
