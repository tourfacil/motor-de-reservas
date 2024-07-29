<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePedidoAvaliacaoMailHashLoginsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedido_avaliacao_mail_hash_logins', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger("pedido_id");
            $table->foreign('pedido_id')->references('id')->on('pedidos');

            $table->string('uuid');
            $table->string('hash');

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
        Schema::dropIfExists('pedido_avaliacao_mail_hash_logins');
    }
}
