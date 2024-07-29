<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCupomDescontosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cupom_descontos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome_publico');
            $table->string('nome_interno');
            $table->string('codigo');
            $table->double('desconto');
            $table->integer('maximo_utilizacoes')->nullable();
            $table->integer('vezes_utilizado')->default(0);
            $table->integer('tipo_desconto_fornecedor')->default(\TourFacil\Core\Enum\Descontos\TipoDesconto::NET);
            $table->integer('tipo_desconto_valor')->default(\TourFacil\Core\Enum\Descontos\TipoDescontoValor::PERCENTUAL);
            $table->boolean('status')->default(\TourFacil\Core\Enum\Descontos\StatusDesconto::ATIVO);
            $table->unsignedBigInteger('servico_id')->nullable();
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
        Schema::dropIfExists('cupom_descontos');
    }
}
