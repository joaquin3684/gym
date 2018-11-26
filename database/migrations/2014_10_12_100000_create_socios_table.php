<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSociosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        Schema::create('socios', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre')->nullable();
            $table->string('genero')->nullable();
            $table->string('email')->nullable();
            $table->string('nro_socio')->nullable();
            $table->string('apellido')->nullable();
            $table->string('celular')->nullable();
            $table->string('domicilio')->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->integer('dni')->nullable();
            $table->integer('id_descuento')->unsigned()->nullable();
            $table->foreign('id_descuento')->references('id')->on('descuentos');
            $table->timestamps();
            $table->softDeletes();

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

        Schema::dropIfExists('socios');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

    }
}
