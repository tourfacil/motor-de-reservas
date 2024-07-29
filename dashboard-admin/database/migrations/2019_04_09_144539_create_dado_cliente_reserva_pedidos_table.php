<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDadoClienteReservaPedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dado_cliente_reserva_pedidos', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger("variacao_servico_id");
            $table->foreign('variacao_servico_id')->references('id')->on('variacao_servicos');

            $table->unsignedInteger("reserva_pedido_id");
            $table->foreign('reserva_pedido_id')->references('id')->on('reserva_pedidos');

            $table->string("nome");
            $table->string("documento");
            $table->date("nascimento");

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
        Schema::dropIfExists('dado_cliente_reserva_pedidos');
    }
}
