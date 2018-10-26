<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ClasesSocios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        Schema::create('clases_socios', function (Blueprint $table) {
            $table->integer('id_socio')->unsigned();
            $table->foreign('id_socio')->references('id')->on('clases');
            $table->integer('id_clase')->unsigned();
            $table->foreign('id_clase')->references('id')->on('socios');
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

        Schema::dropIfExists('clases_socios');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
