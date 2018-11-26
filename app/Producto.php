<?php

namespace App;

use App\services\CajaService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Producto extends Model
{
    use SoftDeletes;


    protected $table = 'productos';
    protected $fillable = ['nombre', 'precio_venta', 'precio_compra', 'punto_reposicion', 'cantidad'];


    public function comprar($cantidad, $precio, $observacion, $tipoPago, $idUsuario)
    {
        $this->cantidad += $cantidad;
        $this->precio_compra = $precio;
        RegistroStock::create(['cantidad' => $cantidad, 'precio' => $precio, 'observacion' => $observacion, 'id_producto' => $this->id, 'tipo_pago' => $tipoPago, 'id_usuario' => $idUsuario, 'tipo' => 'Compra', 'fecha' => Carbon::today()->toDateString()]);
        CajaService::egreso($precio*$cantidad, $this->nombre, $observacion, $tipoPago, $idUsuario);
        $this->save();
    }

    public function vender($cantidad, $precio, $observacion, $tipoPago, $idUsuario)
    {
        $this->cantidad -= $cantidad;
        $this->precio_venta = $precio;
        $this->save();
        CajaService::ingreso($precio*$cantidad, $this->nombre, $observacion, $tipoPago, $idUsuario);
        RegistroStock::create(['cantidad' => $cantidad, 'precio' => $precio, 'observacion' => $observacion, 'id_producto' => $this->id, 'tipo_pago' => $tipoPago, 'id_usuario' => $idUsuario, 'tipo' => 'Venta', 'fecha' => Carbon::today()->toDateString()]);

    }
}
