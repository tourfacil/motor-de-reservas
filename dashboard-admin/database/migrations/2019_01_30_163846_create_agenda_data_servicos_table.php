<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgendaDataServicosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agenda_data_servicos', function (Blueprint $table) {
            $table->increments('id');

            // Agenda
            $table->integer('agenda_servico_id')->unsigned();
            $table->foreign('agenda_servico_id')->references('id')->on('agenda_servicos')->onDelete('cascade');

            $table->date('data');
            $table->decimal("valor_net", 9,2);
            $table->decimal("valor_venda", 9,2);

            $table->integer("disponivel")->default(0);
            $table->integer("consumido")->default(0);

            $table->enum("status", [
                \TourFacil\Core\Enum\AgendaEnum::ATIVO,
                \TourFacil\Core\Enum\AgendaEnum::INATIVO,
                \TourFacil\Core\Enum\AgendaEnum::INDISPONIVEL,
            ])->default(\TourFacil\Core\Enum\AgendaEnum::INATIVO);

            $table->timestamps();

            // Nao pode conter data duplicada
            $table->unique(['agenda_servico_id', 'data']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agenda_data_servicos');
    }
}
