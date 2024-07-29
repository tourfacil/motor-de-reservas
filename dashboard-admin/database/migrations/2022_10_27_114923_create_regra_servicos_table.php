<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegraServicosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('regra_servicos', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger("servico_id");
            $table->foreign('servico_id')->references('id')->on('servicos');

            $table->enum('tipo_regra', array_keys(\TourFacil\Core\Enum\RegraServicoEnum::REGRAS));

            $table->enum('status', [
                \TourFacil\Core\Enum\StatusEnum::ATIVA,
                \TourFacil\Core\Enum\StatusEnum::INATIVA,
            ])->default(\TourFacil\Core\Enum\StatusEnum::INATIVA);

            $table->json('regras');
            $table->integer('prioridade');

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
        Schema::dropIfExists('regra_servicos');
    }
}
