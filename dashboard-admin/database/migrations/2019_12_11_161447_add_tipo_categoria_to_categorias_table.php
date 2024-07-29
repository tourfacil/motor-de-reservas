<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTipoCategoriaToCategoriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categorias', function (Blueprint $table) {
            $table->enum("tipo", array_keys(\TourFacil\Core\Enum\CategoriasEnum::TIPOS_CATEGORIA))
                ->default(\TourFacil\Core\Enum\CategoriasEnum::NORMAL)->after("posicao_menu");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categorias', function (Blueprint $table) {
            $table->dropColumn("tipo");
        });
    }
}
