<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLevelVendedorUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * ADICIONA O STATUS NEGADO nos PEDIDOS
         */
        $types = array_map(function ($item) {
            return "'{$item}'";
        }, array_keys(\App\Enum\UserLevelEnum::LEVELS));

        $status = implode(",", $types);

        DB::statement("ALTER TABLE users MODIFY `level` ENUM({$status}) NOT NULL");

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
