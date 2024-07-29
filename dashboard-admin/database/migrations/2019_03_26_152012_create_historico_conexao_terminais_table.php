<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoricoConexaoTerminaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historico_conexao_terminais', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger("terminal_id");
            $table->foreign("terminal_id")->references('id')->on('terminais');
            $table->enum("type", array_keys(\TourFacil\Core\Enum\TerminaisEnum::TIPOS_HISTORICO))
                ->default(\TourFacil\Core\Enum\TerminaisEnum::HISTORICO_CONEXAO);
            $table->longText("payload");
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
        Schema::dropIfExists('historico_conexao_terminais');
    }
}
