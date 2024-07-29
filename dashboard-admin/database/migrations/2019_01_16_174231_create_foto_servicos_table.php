<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFotoServicosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('foto_servicos', function (Blueprint $table) {
            $table->increments('id');
            // Servico
            $table->integer('servico_id')->unsigned();
            $table->foreign('servico_id')->references('id')->on('servicos')->onDelete('cascade');
            $table->longText("foto");
            $table->string("legenda");
            $table->enum("tipo", [
                \TourFacil\Core\Enum\FotoServicoEnum::PRINCIPAL,
                \TourFacil\Core\Enum\FotoServicoEnum::NORMAL,
            ])->default(\TourFacil\Core\Enum\FotoServicoEnum::NORMAL);
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
        Schema::dropIfExists('foto_servicos');
    }
}
