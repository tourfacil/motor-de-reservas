<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableServicosAddStatusInvisivel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * ADICIONA O STATUS INVISIVEL NO SERVICO
         */
        $types = array_map(function ($item) {
            return "'{$item}'";
        }, array_keys(\TourFacil\Core\Enum\ServicoEnum::STATUS_SERVICO));

        $status = implode(",", $types);

        DB::statement("ALTER TABLE servicos MODIFY `status` ENUM({$status}) NOT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}
