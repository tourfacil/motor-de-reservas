<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriaServicoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categoria_servico', function (Blueprint $table) {

            // Categoria
            $table->integer('categoria_id')->unsigned();
            $table->foreign('categoria_id')->references('id')->on('categorias')->onDelete('cascade');

            // Servico
            $table->integer('servico_id')->unsigned();
            $table->foreign('servico_id')->references('id')->on('servicos')->onDelete('cascade');

            $table->timestamps();

            // Não pode ter 2x a mesma categoria para o mesmo serviço
            $table->unique(['categoria_id', 'servico_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categoria_servico');
    }
}
