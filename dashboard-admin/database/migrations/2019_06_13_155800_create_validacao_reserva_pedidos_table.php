<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateValidacaoReservaPedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('validacao_reserva_pedidos', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger("pedido_id");
            $table->foreign('pedido_id')->references('id')->on('pedidos');

            $table->unsignedInteger("reserva_pedido_id");
            $table->foreign('reserva_pedido_id')->references('id')->on('reserva_pedidos');

            $table->dateTime("validado");
            $table->longText("observacoes")->nullable();

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
        Schema::dropIfExists('validacao_reserva_pedidos');
    }
}
