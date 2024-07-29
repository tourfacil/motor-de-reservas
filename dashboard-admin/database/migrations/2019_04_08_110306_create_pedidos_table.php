<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger("cliente_id");
            $table->foreign('cliente_id')->references('id')->on('clientes');

            $table->unsignedInteger("canal_venda_id");
            $table->foreign("canal_venda_id")->references('id')->on('canal_vendas');

            $table->string("codigo")->unique();
            $table->decimal("valor_total", 9, 2);
            $table->decimal("juros", 9,2)->default(0);

            $table->enum("origem", [
                array_keys(\TourFacil\Core\Enum\OrigemEnum::ORIGENS)
            ]);
            $table->enum("status", [
                array_keys(\TourFacil\Core\Enum\StatusPedidoEnum::STATUS)
            ]);
            $table->enum("status_pagamento", [
                array_keys(\TourFacil\Core\Enum\StatusPagamentoEnum::STATUS)
            ]);
            $table->enum("metodo_pagamento", [
                array_keys(\TourFacil\Core\Enum\MetodoPagamentoEnum::METHODS)
            ]);

            $table->softDeletes();
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
        Schema::dropIfExists('pedidos');
    }
}
