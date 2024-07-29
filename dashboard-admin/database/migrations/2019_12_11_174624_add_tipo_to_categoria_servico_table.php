<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTipoToCategoriaServicoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categoria_servico', function (Blueprint $table) {
            $table->enum("padrao", array_keys(\TourFacil\Core\Enum\CategoriasEnum::CATEGORIA_PADRAO_SERVICO))
                ->default(\TourFacil\Core\Enum\CategoriasEnum::CATEGORIA_NORMAL)->after("servico_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categoria_servico', function (Blueprint $table) {
            $table->dropColumn("padrao");
        });
    }
}
