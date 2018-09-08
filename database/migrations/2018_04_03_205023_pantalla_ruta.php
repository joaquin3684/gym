<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PantallaRuta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        Schema::create('pantalla_ruta', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_pantalla')->unsigned();
            $table->foreign('id_pantalla')->references('id')->on('pantallas');
            $table->integer('id_ruta')->unsigned();
            $table->foreign('id_ruta')->references('id')->on('rutas');
            $table->timestamps();
        });
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        Schema::dropIfExists('pantalla_ruta');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
