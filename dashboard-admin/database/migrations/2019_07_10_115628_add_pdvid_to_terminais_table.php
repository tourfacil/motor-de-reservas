<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPdvidToTerminaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('terminais', function (Blueprint $table) {
            $table->string("pdv_id")->nullable()->after("codigo")->unique();
        });

        // Recupera os terminais
        $terminais = \TourFacil\Core\Models\Terminal::all();

        // Atualiza o PDV ID
        foreach ($terminais as $terminal) {
            $terminal->update(['pdv_id' => substr($terminal->identificacao, 1, strlen($terminal->identificacao))]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('terminais', function (Blueprint $table) {
            $table->dropColumn("pdv_id");
        });
    }
}
