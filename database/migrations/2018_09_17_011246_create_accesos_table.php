<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccesosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        Schema::create('accesos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_socio')->unsigned();
            $table->foreign('id_socio')->references('id')->on('socios');
            $table->integer('id_servicio')->unsigned();
            $table->foreign('id_servicio')->references('id')->on('servicios');
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

        Schema::dropIfExists('accesos');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

    }
}
