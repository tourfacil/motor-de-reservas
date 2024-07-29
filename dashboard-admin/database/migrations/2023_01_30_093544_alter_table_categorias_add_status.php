<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use TourFacil\Core\Enum\StatusCategoriaEnum;

class AlterTableCategoriasAddStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $categorias = array_keys(StatusCategoriaEnum::STATUS);

        Schema::table('categorias', function (Blueprint $table) use ($categorias) {
            $table->enum('status', $categorias)->default(StatusCategoriaEnum::ATIVA)->after('slug');
        });
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
