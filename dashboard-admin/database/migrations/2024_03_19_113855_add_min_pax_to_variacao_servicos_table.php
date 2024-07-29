<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMinPaxToVariacaoServicosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('variacao_servicos', function (Blueprint $table) {
            $table->integer('min_pax')->unsigned()->nullable()->after('consome_bloqueio');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('variacao_servicos', function (Blueprint $table) {
            $table->dropColumn('min_pax');
        });
    }
}
