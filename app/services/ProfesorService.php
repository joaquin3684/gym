<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 30/10/18
 * Time: 20:15
 */

namespace App\services;


use App\Profesor;

class ProfesorService
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
}