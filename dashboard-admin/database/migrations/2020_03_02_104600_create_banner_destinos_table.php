<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBannerDestinosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banner_destinos', function (Blueprint $table) {
            $table->increments('id');

            // Destino
            $table->integer('destino_id')->unsigned();
            $table->foreign('destino_id')->references('id')->on('destinos')->onDelete('cascade');

            // Servico
            $table->integer('servico_id')->unsigned()->nullable();
            $table->foreign('servico_id')->references('id')->on('servicos')->onDelete('cascade');

            $table->integer("ordem");
            $table->string("titulo");
            $table->string("descricao")->nullable();
            $table->string("banner");
            $table->string("url")->nullable();
            $table->enum("tipo", array_keys(\TourFacil\Core\Enum\BannerEnum::TIPOS))
                ->default(\TourFacil\Core\Enum\BannerEnum::TIPO_SERVICO);

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
        Schema::dropIfExists('banner_destinos');
    }
}
