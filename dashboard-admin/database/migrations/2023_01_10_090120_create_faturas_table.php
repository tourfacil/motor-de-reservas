<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use TourFacil\Core\Enum\Faturas\StatusFaturaEnum;
use TourFacil\Core\Enum\Faturas\TipoFaturaEnum;
use TourFacil\Core\Enum\Faturas\TipoPeriodoFaturaEnum;

class CreateFaturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faturas', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger("fornecedor_id");
            $table->foreign('fornecedor_id')->references('id')->on('fornecedores');

            $table->date('inicio');
            $table->date('final');

            $table->datetime('data_pagamento');

            $table->enum('status',array_keys(StatusFaturaEnum::STATUS));
            $table->enum('tipo', array_keys(TipoFaturaEnum::TIPOS));
            $table->enum('tipo_periodo', array_keys(TipoPeriodoFaturaEnum::TIPOS_PERIODO))->default(TipoPeriodoFaturaEnum::UTILIZACAO);

            $table->boolean('aprovacao_interna')->default(false);
            $table->boolean('aprovacao_externa')->default(false);

            $table->double('valor');
            $table->integer('quantidade');
            $table->integer('quantidade_reservas');
            $table->string('observacao')->nullable();

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
        Schema::dropIfExists('faturas');
    }
}
