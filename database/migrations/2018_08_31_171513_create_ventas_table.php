<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        Schema::create('ventas', function (Blueprint $table) {
            $table->increments('id');
            $table->date('fecha');
            $table->double('precio');
            $table->integer('cantidad')->nullable();
            $table->integer('id_socio')->unsigned();
            $table->foreign('id_socio')->references('id')->on('socios');
            $table->integer('id_membresia')->unsigned();
            $table->foreign('id_membresia')->references('id')->on('membresias');
            $table->integer('id_descuento_membresia')->unsigned()->nullable();
            $table->foreign('id_descuento_membresia')->references('id')->on('descuentos');
            $table->integer('id_descuento_socio')->unsigned()->nullable();
            $table->foreign('id_descuento_socio')->references('id')->on('descuentos');

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

        Schema::dropIfExists('ventas');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

    }
}
