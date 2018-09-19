<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMembresiasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        Schema::create('membresias', function (Blueprint $table) {
            $table->increments('id');
            $table->double('precio');
            $table->string('nombre');
            $table->integer('vencimiento_dias');
            $table->integer('nro_cuotas');
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

        Schema::dropIfExists('membresias');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

    }
}
