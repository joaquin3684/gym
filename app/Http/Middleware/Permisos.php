<?php

namespace App\Http\Middleware;

use App\Exceptions\NoTienePermisoARutaException;
use App\Pantalla;
use App\Ruta;
use App\User;
use Closure;
use Mockery\Exception;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Tymon\JWTAuth\Facades\JWTAuth;

class Permisos
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = JWTAuth::getToken();
        $permisos = JWTAuth::decode($token)['permisos'];
        $fullPath = $request->getPathInfo();
        $path = explode("/", $fullPath);
        $pantalla = $path[1];
        foreach($permisos as $permiso)
        {
            if($permiso == $pantalla)
            {
                return $next($request);
            }
        }
        $userId = JWTAuth::decode($token)['user_id'];
        $user = User::with('perfil.pantallas.rutas')->find($userId);
        $userRoute = $user->perfil->pantallas->first(function($pantalla) use ($fullPath){
            return $pantalla->rutas->first(function($ruta) use ($fullPath){
                return $ruta->ruta == $fullPath;
            });
        });

        if($userRoute == null)
        {
            throw new \RuntimeException('acceso denegado');
        } else {
            return $next($request);
        }
    }
}
