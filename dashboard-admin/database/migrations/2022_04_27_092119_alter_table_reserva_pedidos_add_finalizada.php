<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableReservaPedidosAddFinalizada extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reserva_pedidos', function (Blueprint $table) {
            $table->boolean("finalizada")->default(\TourFacil\Core\Enum\StatusFinalizacaoReservaEnum::FINALIZADA);
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
            $table->dropColumn("finalizada");
        });
    }
}
