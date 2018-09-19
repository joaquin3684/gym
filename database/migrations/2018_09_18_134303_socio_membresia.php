<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SocioMembresia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        Schema::create('socio_membresia', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_socio')->unsigned();
            $table->foreign('id_socio')->references('id')->on('socios');
            $table->integer('id_membresia')->unsigned();
            $table->foreign('id_membresia')->references('id')->on('membresias');
            $table->date('vto');
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

        Schema::dropIfExists('socio_membresia');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
