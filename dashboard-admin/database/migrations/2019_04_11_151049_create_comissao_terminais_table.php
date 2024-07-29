<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComissaoTerminaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comissao_terminais', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger("pedido_id");
            $table->foreign('pedido_id')->references('id')->on('pedidos');

            $table->unsignedInteger("reserva_pedido_id");
            $table->foreign('reserva_pedido_id')->references('id')->on('reserva_pedidos');

            $table->unsignedInteger("terminal_id");
            $table->foreign("terminal_id")->references('id')->on('terminais');

            $table->integer("quantidade");
            $table->decimal("comissao", 9,2);
            $table->date('data_previsao');
            $table->enum("status", array_keys(\TourFacil\Core\Enum\ComissaoStatus::STATUS));

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
        Schema::dropIfExists('comissao_terminais');
    }
}
