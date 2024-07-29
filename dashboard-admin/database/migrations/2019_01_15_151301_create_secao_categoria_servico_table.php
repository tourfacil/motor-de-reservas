<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSecaoCategoriaServicoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('secao_categoria_servico', function (Blueprint $table) {

            // Secao Categoria
            $table->integer('secao_categoria_id')->unsigned();
            $table->foreign('secao_categoria_id')->references('id')->on('secao_categorias')->onDelete('cascade');

            // Servico
            $table->integer('servico_id')->unsigned();
            $table->foreign('servico_id')->references('id')->on('servicos')->onDelete('cascade');

            $table->timestamps();

            // Não pode ter 2x a mesma secao categoria para o mesmo serviço
            $table->unique(['secao_categoria_id', 'servico_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('secao_categoria_servico');
    }
}
