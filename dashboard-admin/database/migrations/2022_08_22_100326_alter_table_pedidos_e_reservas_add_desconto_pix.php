<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTablePedidosEReservasAddDescontoPix extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Adicionado o campo de desconto pix nos pedidos
        Schema::table('pedidos', function (Blueprint $table) {
            $table->double('desconto_pix')->default(0)->after('juros');
        });

        // Adicionado o campo de desconto pix nas reservas
        Schema::table('reserva_pedidos', function (Blueprint $table) {
            $table->double('desconto_pix')->default(0)->after('valor_net');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Remove o campo de desconto do pix dos pedidos
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropColumn('desconto_pix');
        });

        // Remove o campo de desconto do pix das reservas
        Schema::table('reserva_pedidos', function (Blueprint $table) {
            $table->dropColumn('desconto_pix');
        });
    }
}
