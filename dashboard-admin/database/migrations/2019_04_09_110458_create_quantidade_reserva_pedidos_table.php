<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuantidadeReservaPedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quantidade_reserva_pedidos', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger("variacao_servico_id");
            $table->foreign('variacao_servico_id')->references('id')->on('variacao_servicos');

            $table->unsignedInteger("reserva_pedido_id");
            $table->foreign('reserva_pedido_id')->references('id')->on('reserva_pedidos');

            $table->integer("quantidade");
            $table->decimal("valor_total", 9,2);
            $table->decimal("valor_net", 9,2);

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
        Schema::dropIfExists('quantidade_reserva_pedidos');
    }
}
