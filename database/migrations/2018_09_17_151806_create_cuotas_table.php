<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCuotasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        Schema::create('cuotas', function (Blueprint $table) {

            $table->increments('id');
            $table->double('pago');
            $table->integer('nro_cuota');
            $table->boolean('pagada');
            $table->date('fecha_inicio');
            $table->date('fecha_vto');
            $table->integer('id_socio_membresia')->unsigned();
            $table->foreign('id_socio_membresia')->references('id')->on('socio_membresia');
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

        Schema::dropIfExists('cuotas');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

    }
}
