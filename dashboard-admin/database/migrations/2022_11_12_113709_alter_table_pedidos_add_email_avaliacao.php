<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTablePedidosAddEmailAvaliacao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->enum('email_avaliacao', array_keys(\TourFacil\Core\Enum\StatusEmailAvaliacaoEnum::STATUS))->default(\TourFacil\Core\Enum\StatusEmailAvaliacaoEnum::NAO_ENVIAR)->after('metodo_pagamento_interno');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
