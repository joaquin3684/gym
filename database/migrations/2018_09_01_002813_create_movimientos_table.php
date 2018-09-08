<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMovimientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        Schema::create('movimientos', function (Blueprint $table) {
            $table->increments('id');
            $table->double('ingreso')->default(0);
            $table->double('egreso')->default(0);
            $table->date('fecha');
            $table->string('observacion')->nullable();
            $table->string('tipo_pago');
            $table->string('concepto');
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

        Schema::dropIfExists('movimientos');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

    }
}
