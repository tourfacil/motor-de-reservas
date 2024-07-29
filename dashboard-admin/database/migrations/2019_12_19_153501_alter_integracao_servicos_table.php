<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterIntegracaoServicosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * ADICIONA A INTEGRAÇÃO COM O EXCEED PARK
         */
        $types = array_map(function ($item) {
            return "'{$item}'";
        }, array_keys(\TourFacil\Core\Enum\IntegracaoEnum::INTEGRACOES));

        $integracoes = implode(",", $types);

        DB::statement("ALTER TABLE servicos MODIFY integracao ENUM({$integracoes}) NOT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /**
         * REMOVE A INTEGRAÇÃO COM O EXCEED PARK
         */
        $types = array_map(function ($item) {
            return "'{$item}'";
        }, array_keys(array_except(\TourFacil\Core\Enum\IntegracaoEnum::INTEGRACOES, \TourFacil\Core\Enum\IntegracaoEnum::EXCEED_PARK)));

        $integracoes = implode(",", $types);

        DB::statement("ALTER TABLE servicos MODIFY integracao ENUM({$integracoes}) NOT NULL");
    }
}
