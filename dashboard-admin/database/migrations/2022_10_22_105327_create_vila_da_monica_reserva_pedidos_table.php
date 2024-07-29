<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVilaDaMonicaReservaPedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vila_da_monica_reserva_pedidos', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger("reserva_pedido_id");
            $table->foreign('reserva_pedido_id')->references('id')->on('reserva_pedidos');

            $table->string('bill_id');
            $table->date('data_servico');
            $table->string('voucher_impressao');
            $table->string('token_impressao');
            $table->enum('status', array_keys(\TourFacil\Core\Enum\IntegracaoEnum::STATUS_VOUCHER));
            $table->timestamps();

            /**
             * ADICIONA A INTEGRAÇÃO COM O DREAMS
             */
            $types = array_map(function ($item) {
                return "'{$item}'";
            }, array_keys(\TourFacil\Core\Enum\IntegracaoEnum::INTEGRACOES));

            $integracoes = implode(",", $types);

            DB::statement("ALTER TABLE servicos MODIFY integracao ENUM({$integracoes}) NOT NULL");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vila_da_monica_reserva_pedidos');
    }
}
