<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCanalVendasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('canal_vendas', function (Blueprint $table) {
            $table->increments('id');
            $table->char("uuid", 36);
            $table->string("nome");
            $table->string("site");
            $table->integer("maximo_parcelas");
            $table->integer("parcelas_sem_juros");
            $table->decimal("juros_parcela", 5,1);
            $table->softDeletes();
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
        Schema::dropIfExists('canal_vendas');
    }
}
