<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class HorarioProfesor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        Schema::create('horario_profesor', function (Blueprint $table) {

            $table->integer('id_horario')->unsigned();
            $table->foreign('id_horario')->references('id')->on('horarios');
            $table->integer('id_profesor')->unsigned();
            $table->foreign('id_profesor')->references('id')->on('profesores');
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

        Schema::dropIfExists('horario_profesor');

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
