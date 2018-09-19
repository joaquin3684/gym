<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDescuentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        Schema::create('descuentos', function (Blueprint $table) {

            $table->increments('id');
            $table->string('nombre');
            $table->double('porcentaje');
            $table->integer('vencimiento_dias');
            $table->boolean('aplicable_enconjunto');
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

        Schema::dropIfExists('descuentos');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

    }
}
