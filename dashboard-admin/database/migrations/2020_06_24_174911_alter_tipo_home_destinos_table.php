<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTipoHomeDestinosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $types = array_map(function ($item) {
            return "'{$item}'";
        }, array_keys(\TourFacil\Core\Enum\TipoHomeDestinoEnum::TIPOS_HOME_DESTINO));

        $tipos = implode(",", $types);

        DB::statement("ALTER TABLE home_destinos MODIFY tipo ENUM({$tipos}) NOT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $types = array_map(function ($item) {
            return "'{$item}'";
        }, array_keys(array_except(\TourFacil\Core\Enum\TipoHomeDestinoEnum::TIPOS_HOME_DESTINO, \TourFacil\Core\Enum\TipoHomeDestinoEnum::DESTAQUES)));

        $tipos = implode(",", $types);

        DB::statement("ALTER TABLE home_destinos MODIFY tipo ENUM({$tipos}) NOT NULL");
    }
}
