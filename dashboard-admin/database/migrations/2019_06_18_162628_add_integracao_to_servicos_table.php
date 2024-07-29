<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIntegracaoToServicosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('servicos', function (Blueprint $table) {
            $table->enum('integracao', array_keys(\TourFacil\Core\Enum\IntegracaoEnum::INTEGRACOES))
                ->after('info_clientes')->default(\TourFacil\Core\Enum\IntegracaoEnum::NAO);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('servicos', function (Blueprint $table) {
            $table->dropColumn("integracao");
        });
    }
}
