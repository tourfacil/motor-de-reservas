<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHomeDestinoServicoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('home_destino_servico', function (Blueprint $table) {
            $table->increments('id');

            // Home Destino
            $table->integer('home_destino_id')->unsigned();
            $table->foreign('home_destino_id')->references('id')->on('home_destinos')->onDelete('cascade');

            // Servico
            $table->integer('servico_id')->unsigned();
            $table->foreign('servico_id')->references('id')->on('servicos')->onDelete('cascade');

            // Ordem
            $table->integer('ordem');

            $table->timestamps();

            // Não pode ter 2x o mesmo servico para a mesma home
            $table->unique(['servico_id', 'home_destino_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('home_destino_servico');
    }
}
