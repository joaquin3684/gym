<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        Schema::create('pago_profesores', function (Blueprint $table) {
            $table->increments('id');
            $table->date('fecha');
            $table->double('pago');
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

        Schema::dropIfExists('pago_profesores');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

    }
}
