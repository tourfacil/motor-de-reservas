<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampoAdicionalServicosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campo_adicional_servicos', function (Blueprint $table) {
            $table->increments('id');
            // Servico
            $table->integer('servico_id')->unsigned();
            $table->foreign('servico_id')->references('id')->on('servicos')->onDelete('cascade');
            $table->string("campo");
            $table->string("placeholder");
            $table->enum("obrigatorio", [
                \TourFacil\Core\Enum\CampoAdicionalEnum::REQUIRIDO,
                \TourFacil\Core\Enum\CampoAdicionalEnum::NAO_REQUIRIDO,
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
        Schema::dropIfExists('campo_adicional_servicos');
    }
}
