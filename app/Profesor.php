<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profesor extends Model
{
    use SoftDeletes;
    protected $table = 'profesores';
    protected $fillable = ['nombre', 'apellido', 'telefono', 'email', 'domicilio', 'fecha_cobro_dia', 'cantidad_dias_cobro', 'fijo'];

    public function clases()
    {
        return $this->belongsToMany('App\Clase', 'clase_profesor', 'id_profesor', 'id_clase')->withPivot('pagada', 'tipo_pago', 'precio');
    }

    public function cobros()
    {
        return $this->hasMany('App\Pago', 'id_profesor','id');
    }

    public function pagar()
    {
        $totalAPagar = $this->clases->sum(function($clase){
                $this->clases()->updateExistingPivot($clase->id, ['pagada' => true]);
                return $clase->pagar();
        }) + $this->fijo;



        Pago::create(['id_profesor' => $this->id, 'pago' => $totalAPagar, 'fecha' => Carbon::today()->toDateString()]);
    }


}
