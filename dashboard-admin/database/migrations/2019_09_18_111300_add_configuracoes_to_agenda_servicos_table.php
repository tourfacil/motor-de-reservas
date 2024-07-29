<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddConfiguracoesToAgendaServicosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agenda_servicos', function (Blueprint $table) {
            $table->longText("configuracoes")->nullable()->after("dias_semana");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agenda_servicos', function (Blueprint $table) {
            $table->dropColumn('configuracoes');
        });
    }
}
