<?php

namespace App\Http\Controllers;

use App\Descuento;
use App\Membresia;
use App\services\VentaService;
use App\Socio;
use App\User;
use App\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{

    private $service;
    public function __construct()
    {
        $this->service = new VentaService();
    }

    public function ventas(Request $request)
    {
        $fechaInicio = $request['fechaInicio'];
        $fechaFin = $request['fechaFin'];
        return $this->service->ventas($fechaInicio, $fechaFin);
    }

    public function historialCompra($idSocio)
    {
        return $this->service->historialCompra($idSocio);
    }

    public function crear(Request $request)
    {
        DB::transaction(function () use ($request){
            $idSocio = $request['idSocio'];

            $socio = Socio::with('descuento', 'ventas.cuotas')->find($idSocio);
            foreach ($request['membresias'] as $membresia)
            {
                $mem = Membresia::find($membresia['id']);
                $descuento = is_null($membresia['idDescuento']) ? null : Descuento::find($membresia['idDescuento']);
                $usuario = User::find($request['userId']);

                $this->service->realizarCompra($socio, $request['tipoPago'], $request['observacion'], $usuario, $mem, $membresia['cantidad'], $descuento);
            }

        });
    }

    public function delete(Request $request)
    {
        DB::transaction(function () use ($request){
           $venta = Venta::find($request['idVenta']);
           $this->service->delete($venta);
        });
    }

}
