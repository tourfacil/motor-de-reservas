<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVariacaoServicosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('variacao_servicos', function (Blueprint $table) {
            $table->increments('id');
            // Servico
            $table->integer('servico_id')->unsigned();
            $table->foreign('servico_id')->references('id')->on('servicos')->onDelete('cascade');
            $table->string("nome");
            $table->string("descricao");
            $table->decimal("percentual", 9, 5);
            $table->decimal("markup", 9,5);
            $table->enum("consome_bloqueio", [
                \TourFacil\Core\Enum\VariacaoServicoEnum::CONSOME_BLOQUEIO,
                \TourFacil\Core\Enum\VariacaoServicoEnum::NAO_CONSOME_BLOQUEIO,
            ]);
            $table->softDeletes();
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
        Schema::dropIfExists('variacao_servicos');
    }
}
