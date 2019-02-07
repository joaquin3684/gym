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


    public function comprar($cantidad, $observacion, $tipoPago, $idUsuario)
    {
        $this->cantidad += $cantidad;
        RegistroStock::create(['cantidad' => $cantidad, 'precio' => $this->precio_compra, 'observacion' => $observacion, 'id_producto' => $this->id, 'tipo_pago' => $tipoPago, 'id_usuario' => $idUsuario, 'tipo' => 'Compra', 'fecha' => Carbon::today()->toDateString()]);
        CajaService::egreso($this->precio_compra*$cantidad, $this->nombre, $observacion, $tipoPago, $idUsuario);
        $this->save();
    }

    public function vender($cantidad, $observacion, $tipoPago, $idUsuario)
    {
        $this->cantidad -= $cantidad;
        $this->save();
        CajaService::ingreso($this->precio_venta*$cantidad, $this->nombre, $observacion, $tipoPago, $idUsuario);
        RegistroStock::create(['cantidad' => $cantidad, 'precio' => $this->precio_venta, 'observacion' => $observacion, 'id_producto' => $this->id, 'tipo_pago' => $tipoPago, 'id_usuario' => $idUsuario, 'tipo' => 'Venta', 'fecha' => Carbon::today()->toDateString()]);

    }
}
