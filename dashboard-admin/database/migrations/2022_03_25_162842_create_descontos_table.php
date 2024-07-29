<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDescontosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('descontos', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('inicio');
            $table->dateTime('final');
            $table->double('desconto');
            $table->string('nome_publico')->nullable();
            $table->string('nome_interno');
            $table->unsignedInteger("servico_id");
            $table->foreign('servico_id')->references('id')->on('servicos');
            $table->double('total_vendido_venda')->default(0);
            $table->double('total_vendido_net')->default(0);
            $table->double('total_descontado_venda')->default(0);
            $table->double('total_descontado_net')->default(0);
            $table->boolean('status')->default(\TourFacil\Core\Enum\Descontos\StatusDesconto::ATIVO);
            $table->integer('tipo_desconto_valor')->default(\TourFacil\Core\Enum\Descontos\TipoDescontoValor::PERCENTUAL);
            $table->integer('tipo_desconto_fornecedor')->default(\TourFacil\Core\Enum\Descontos\TipoDesconto::NET);
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
        Schema::dropIfExists('descontos');
    }
}
