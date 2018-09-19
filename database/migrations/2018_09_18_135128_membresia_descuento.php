<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MembresiaDescuento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        Schema::create('membresia_descuento', function (Blueprint $table) {
            $table->integer('id_membresia')->unsigned();
            $table->foreign('id_membresia')->references('id')->on('membresias');
            $table->integer('id_descuento')->unsigned();
            $table->foreign('id_descuento')->references('id')->on('descuentos');
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

        Schema::dropIfExists('membresia_descuento');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
