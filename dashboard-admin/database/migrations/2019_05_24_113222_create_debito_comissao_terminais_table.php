<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDebitoComissaoTerminaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('debito_comissao_terminais', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger("terminal_id");
            $table->foreign("terminal_id")->references('id')->on('terminais');

            $table->unsignedInteger("comissao_terminal_id");
            $table->foreign('comissao_terminal_id')->references('id')->on('comissao_terminais');

            $table->decimal("valor", 9,2)->default(0);
            $table->enum('status', array_keys(\TourFacil\Core\Enum\ComissaoStatus::STATUS));

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('debito_comissao_terminais');
    }
}
