<?php

namespace App\Http\Controllers;

use App\Cuota;
use App\services\CuotaService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CuotaController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = new CuotaService();
    }

    public function cancelarPago(Request $request)
    {
        DB::transaction(function($request){
            $cuota = Cuota::find($request['idCuota']);
            $user = User::find($request['userId']);
            $this->service->cancelarPago($cuota, $user);
        });
    }
}
