<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableDescontos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('descontos', function (Blueprint $table) {

            $table->dropColumn('total_vendido_venda');
            $table->dropColumn('total_vendido_net');
            $table->dropColumn('total_descontado_venda');
            $table->dropColumn('total_descontado_net');

            $table->dateTime('inicio_utilizacao')->after('final');
            $table->dateTime('final_utilizacao')->after('inicio_utilizacao');

            $table->double('desconto_net')->after('desconto');
            $table->double('valor_de')->after('desconto_net');
            $table->double('valor_por')->after('valor_de');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
