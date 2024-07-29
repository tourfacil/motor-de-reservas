<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampoAdicionalReservaPedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campo_adicional_reserva_pedidos', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger("campo_adicional_servico_id");
            $table->foreign('campo_adicional_servico_id', 'campo_adicional_servico_id_foreign')->references('id')->on('campo_adicional_servicos');

            $table->unsignedInteger("reserva_pedido_id");
            $table->foreign('reserva_pedido_id')->references('id')->on('reserva_pedidos');

            $table->string("informacao");

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
        Schema::dropIfExists('campo_adicional_reserva_pedidos');
    }
}
