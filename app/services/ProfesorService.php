<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 30/10/18
 * Time: 20:15
 */

namespace App\services;


use App\Profesor;
use Carbon\Carbon;

class  ProfesorService
{
    public function crear($elem)
    {
        $profesor = Profesor::create($elem);
        return $profesor->id;
    }

    public function update($elem, $id)
    {
        $profesor = Profesor::find($id);
        $profesor->fill($elem);
        $profesor->save();
        return $profesor->id;
    }

    public function delete($id)
    {
        Profesor::destroy($id);
    }

    public function find($id)
    {
        return Profesor::find($id);
    }

    public function all()
    {
        return Profesor::all();
    }

    public function profesoresAPagar()
    {
        $hoy = Carbon::today();
        return Profesor::with(['clases' => function($q) use ($hoy){
            $q->where('fecha', '<=', $hoy->toDateString())
                ->where('pagada', false);
        }])->where('fecha_cobro_dia', $hoy->day)
            ->orWhere('cantidad_dias_cobro', '<=', function($q) use ($hoy){
                $q->from('pago_profesores')
                    ->selectRaw('DATEDIFF(?, fecha)',   [$hoy->toDateString()])
                    ->whereRaw('id_profesor = profesores.id')
                    ->orderBy('id', 'desc')
                    ->limit(1);

            })->get();
    }

    public function pagar($elem)
    {
        $col = collect($elem);
        $prof = $col->map(function($p, $key){return $key;});
        $profesores = Profesor::with(['clases' => function($q){$q->where('pagada', false);}])
            ->whereIn('id', $prof->toArray())->get();

        $prof = $profesores->map(function($profesor) use ($col){
            $p = $col->first(function($prof, $key) use ($profesor){return $key == $profesor->id;});
            $p = collect($p);
            $clases = $profesor->clases->filter(function($clase) use ($p){
                return $p->contains(function($c) use($clase){
                    return $c = $clase->id;
                });
            });
            $profesor->clases = $clases;
            return $profesor;
        });

        $prof->each(function(Profesor $profesor){
            $profesor->pagar();
        });



    }
}