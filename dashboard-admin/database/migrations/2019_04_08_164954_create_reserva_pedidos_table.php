<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservaPedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reserva_pedidos', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger("pedido_id");
            $table->foreign('pedido_id')->references('id')->on('pedidos');

            $table->unsignedInteger("servico_id");
            $table->foreign('servico_id')->references('id')->on('servicos');

            $table->unsignedInteger("fornecedor_id");
            $table->foreign('fornecedor_id')->references('id')->on('fornecedores');

            $table->unsignedInteger("agenda_data_servico_id");
            $table->foreign('agenda_data_servico_id')->references('id')->on('agenda_data_servicos');

            $table->string("voucher")->unique();
            $table->decimal("valor_total", 9, 2);
            $table->decimal("valor_net", 9,2);
            $table->integer("quantidade");
            $table->integer("bloqueio_consumido");
            $table->enum("status", [
                array_keys(\TourFacil\Core\Enum\StatusReservaEnum::STATUS)
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
        Schema::dropIfExists('reserva_pedidos');
    }
}
