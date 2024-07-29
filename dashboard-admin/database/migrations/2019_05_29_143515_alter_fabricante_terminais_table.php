<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFabricanteTerminaisTable extends Migration
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
        }, array_keys(\TourFacil\Core\Enum\TerminaisEnum::FABRICANTES));

        $fabricantes = implode(",", $types);

        DB::statement("ALTER TABLE terminais MODIFY fabricante ENUM({$fabricantes}) NOT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('terminais', function (Blueprint $table) {

            $types = array_map(function ($item) {
                return "'{$item}'";
            }, array_keys(array_except(\TourFacil\Core\Enum\TerminaisEnum::FABRICANTES, \TourFacil\Core\Enum\TerminaisEnum::SCHALTER)));

            $fabricantes = implode(",", $types);

            DB::statement("ALTER TABLE terminais MODIFY fabricante ENUM({$fabricantes}) NOT NULL");
        });
    }
}
