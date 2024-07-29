<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgendaServicosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agenda_servicos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("disponibilidade_minima");
            $table->longText("dias_semana")->nullable();
            $table->enum("compartilhada", [
                \TourFacil\Core\Enum\AgendaEnum::COMPARTILHA,
                \TourFacil\Core\Enum\AgendaEnum::NAO_COMPARTILHA,
            ])->default(\TourFacil\Core\Enum\AgendaEnum::NAO_COMPARTILHA);
            $table->enum("status", [
                \TourFacil\Core\Enum\AgendaEnum::BAIXA_DISPONIBILIDADE,
                \TourFacil\Core\Enum\AgendaEnum::SEM_DISPONIBILIDADE,
                \TourFacil\Core\Enum\AgendaEnum::COM_DISPONIBILIDADE,
            ])->default(\TourFacil\Core\Enum\AgendaEnum::SEM_DISPONIBILIDADE);
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
        Schema::dropIfExists('agenda_servicos');
    }
}
