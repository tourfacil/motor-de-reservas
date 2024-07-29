<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgendaHasServicoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agenda_has_servico', function (Blueprint $table) {
            $table->increments('id');

            // Agenda
            $table->integer('agenda_servico_id')->unsigned();
            $table->foreign('agenda_servico_id')->references('id')->on('agenda_servicos')->onDelete('cascade');

            // Servico
            $table->integer('servico_id')->unsigned();
            $table->foreign('servico_id')->references('id')->on('servicos')->onDelete('cascade');

            $table->timestamps();

            // Não pode ter 2x a mesma agenda para o mesmo serviço
            $table->unique('servico_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agenda_has_servico');
    }
}
