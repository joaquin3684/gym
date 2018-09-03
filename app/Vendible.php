<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendible extends Model
{
    protected $table = 'vendibles';
    protected $fillable = ['precio', 'nombre', 'vencimiento', 'tipo'];

}
