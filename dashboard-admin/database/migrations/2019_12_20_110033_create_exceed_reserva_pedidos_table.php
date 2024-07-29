<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExceedReservaPedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exceed_reserva_pedidos', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger("reserva_pedido_id");
            $table->foreign('reserva_pedido_id')->references('id')->on('reserva_pedidos');

            $table->string('bill_id');
            $table->date('data_servico');
            $table->string('voucher_impressao');
            $table->string('token_impressao');
            $table->enum('status', array_keys(\TourFacil\Core\Enum\IntegracaoEnum::STATUS_VOUCHER));

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
        Schema::dropIfExists('exceed_reserva_pedidos');
    }
}
