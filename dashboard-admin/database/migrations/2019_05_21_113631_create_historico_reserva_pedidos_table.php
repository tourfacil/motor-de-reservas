<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoricoReservaPedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historico_reserva_pedidos', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger("pedido_id");
            $table->foreign('pedido_id')->references('id')->on('pedidos');

            $table->unsignedInteger("reserva_pedido_id");
            $table->foreign('reserva_pedido_id')->references('id')->on('reserva_pedidos');

            $table->enum("motivo", array_keys(\TourFacil\Core\Enum\MotivosReservaEnum::MOTIVOS));

            $table->unsignedInteger("user_id");
            $table->foreign("user_id")->references('id')->on('users');

            $table->decimal("valor", 9,2)->default(0);
            $table->decimal("valor_fornecedor", 9,2)->default(0);

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
        Schema::dropIfExists('historico_reserva_pedidos');
    }
}
