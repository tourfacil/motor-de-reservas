<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableReservasAddFaturaId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reserva_pedidos', function (Blueprint $table) {
            $table->unsignedInteger("fatura_id")->after('agenda_data_servico_id')->nullable();
            $table->foreign('fatura_id')->references('id')->on('faturas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reserva_pedidos', function (Blueprint $table) {
            $table->dropColumn('fatura_id');
        });
    }
}
