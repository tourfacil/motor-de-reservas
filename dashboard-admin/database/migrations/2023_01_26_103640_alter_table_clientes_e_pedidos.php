<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableClientesEPedidos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * Altera os leveis de permissão do user
         */
        $types = array_map(function ($item) {
            return "'{$item}'";
        }, array_keys(\App\Enum\UserLevelEnum::LEVELS));

        $integracoes = implode(",", $types);

        DB::statement("ALTER TABLE users MODIFY `level` ENUM({$integracoes}) NOT NULL");

        /**
         * Altera a origem dos pedidos
         */
        $types = array_map(function ($item) {
            return "'{$item}'";
        }, array_keys(TourFacil\Core\Enum\OrigemEnum::ORIGENS));

        $integracoes = implode(",", $types);

        DB::statement("ALTER TABLE pedidos MODIFY origem ENUM({$integracoes}) NOT NULL");
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
