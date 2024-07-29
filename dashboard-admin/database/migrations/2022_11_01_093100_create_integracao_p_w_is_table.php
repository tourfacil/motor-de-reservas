<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntegracaoPWIsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('integracao_p_w_is', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger("reserva_pedido_id");
            $table->foreign('reserva_pedido_id')->references('id')->on('reserva_pedidos');

            $table->enum('integracao', [
                array_keys(\TourFacil\Core\Enum\IntegracaoEnum::INTEGRACOES_PWI)
            ]);

            $table->enum('status', [
                \TourFacil\Core\Enum\StatusReservaEnum::ATIVA,
                \TourFacil\Core\Enum\StatusReservaEnum::CANCELADO,
            ]);

            $table->json('dados');
            $table->date('data_utilizacao');
            $table->timestamps();
        });

        /**
         * ADICIONA A INTEGRAÇÃO COM O SKYGLASS
         */
        $types = array_map(function ($item) {
            return "'{$item}'";
        }, array_keys(\TourFacil\Core\Enum\IntegracaoEnum::INTEGRACOES));

        $integracoes = implode(",", $types);

        DB::statement("ALTER TABLE servicos MODIFY integracao ENUM({$integracoes}) NOT NULL");


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('integracao_p_w_is');
    }
}
