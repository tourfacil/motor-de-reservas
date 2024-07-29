<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableAfiliadosAddComissao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $comissao_default = 5;

        Schema::table('afiliados', function (Blueprint $table) use ($comissao_default) {
            $table->double('comissao_passeios')->default($comissao_default);
            $table->double('comissao_ingressos')->default($comissao_default);
            $table->double('comissao_gastronomia')->default($comissao_default);
            $table->double('comissao_transfer')->default($comissao_default);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('afiliados', function (Blueprint $table) {
            $table->dropColumn('comissao_passeios');
            $table->dropColumn('comissao_ingressos');
            $table->dropColumn('comissao_gastronomia');
            $table->dropColumn('comissao_transfer');
        });
    }
}
