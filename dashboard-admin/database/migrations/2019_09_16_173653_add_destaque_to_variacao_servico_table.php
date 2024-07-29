<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDestaqueToVariacaoServicoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('variacao_servicos', function (Blueprint $table) {
            $table->enum('destaque', array_keys(\TourFacil\Core\Enum\VariacaoServicoEnum::TIPO_VARIACAO))
                ->default(\TourFacil\Core\Enum\VariacaoServicoEnum::VARIACAO_NORMAL)->after("consome_bloqueio");
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
            $table->dropColumn('destaque');
        });
    }
}
