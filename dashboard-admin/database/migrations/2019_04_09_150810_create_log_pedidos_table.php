<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogPedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_pedidos', function (Blueprint $table) {
            $table->increments('id');

            $table->string("codigo_pedido")->nullable();
            $table->longText("log");
            $table->string("cookie_ga")->nullable();

            $table->enum("tipo", array_keys(\TourFacil\Core\Enum\LogPedidoEnum::LOGS));

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
        Schema::dropIfExists('log_pedidos');
    }
}
