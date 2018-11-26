<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComprasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        Schema::create('registro_stock', function (Blueprint $table) {

            $table->increments('id');
            $table->double('cantidad');
            $table->double('precio');
            $table->string('observacion')->nullable();
            $table->integer('id_producto')->unsigned();
            $table->foreign('id_producto')->references('id')->on('productos');
            $table->string('tipo_pago');
            $table->string('tipo');
            $table->date('fecha');
            $table->integer('id_usuario')->unsigned();
            $table->foreign('id_usuario')->references('id')->on('usuarios');
            $table->integer('id_socio')->unsigned()->nullable();
            $table->foreign('id_socio')->references('id')->on('socios');
            $table->timestamps();
        });

        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        Schema::dropIfExists('registro_stock');

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

    }
}
