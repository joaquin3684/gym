<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Horarios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        Schema::create('horarios', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_servicio')->unsigned();
            $table->foreign('id_servicio')->references('id')->on('servicios');
            $table->integer('dia');
            $table->time('desde');
            $table->time('hasta');
            $table->time('entrada_desde')->nullable();
            $table->time('entrada_hasta')->nullable();
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

        Schema::dropIfExists('horarios');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
