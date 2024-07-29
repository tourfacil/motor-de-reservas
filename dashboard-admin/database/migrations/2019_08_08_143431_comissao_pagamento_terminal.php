<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ComissaoPagamentoTerminal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comissao_pagamento_terminais', function (Blueprint $table) {

            // Pagamento do terminal
            $table->integer('pagamento_terminal_id')->unsigned();
            $table->foreign('pagamento_terminal_id')->references('id')->on('pagamento_terminais')->onDelete('cascade');

            // Comissao
            $table->integer('comissao_terminal_id')->unsigned();
            $table->foreign('comissao_terminal_id')->references('id')->on('comissao_terminais')->onDelete('cascade');

            // Não pode ter 2x pagamentos para a mesma comissao
            $table->unique(['comissao_terminal_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comissao_pagamento_terminais');
    }
}
