<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableReservaPedidosAddAfiliadoId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reserva_pedidos', function (Blueprint $table) {
            $table->unsignedInteger("afiliado_id")->nullable();
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
            $table->dropColumn("afiliado_id");
        });
    }
}
