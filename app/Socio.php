<?php

namespace App;

use App\services\CajaService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Socio extends Model
{
    use SoftDeletes;
    protected $table = 'socios';
    protected $fillable = ['nombre', 'apellido', 'celular', 'domicilio', 'dni', 'fecha_nacimiento'];
    protected $dates = ['deleted_at'];

    public function comprar(Vendible $vendible, $cantidad, $tipoPago)
    {
        $venta = new Venta(['fecha' => Carbon::today()->toDateString(), 'precio' => $vendible->precio, 'id_vendible' => $vendible->id, 'id_socio' => $this->id, 'cantidad' => $cantidad]);
        $venta->save();
        CajaService::ingreso($vendible->precio * $cantidad, $vendible->nombre, null, $tipoPago);
    }


}
