<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaginaPadraoUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum("pagina_padrao", array_keys(\App\Enum\ConfiguracoesEnum::PAGES))
                ->default(\App\Enum\ConfiguracoesEnum::DASHBOARD)->after("canal_venda_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn("pagina_padrao");
        });
    }
}
