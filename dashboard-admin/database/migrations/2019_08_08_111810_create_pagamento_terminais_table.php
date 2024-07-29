<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagamentoTerminaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pagamento_terminais', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger("terminal_id");
            $table->foreign("terminal_id")->references('id')->on('terminais');

            $table->date("mes_referencia");
            $table->date("mes_pagamento");

            $table->decimal("total_comissao", 9,2)->default(0);
            $table->decimal("total_pago", 9,2)->default(0);
            $table->dateTime("data_pagamento")->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pagamento_terminais');
    }
}
