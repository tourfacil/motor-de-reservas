<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSplitFornecedoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('split_fornecedores', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger("fornecedor_id");
            $table->foreign("fornecedor_id")->references('id')->on('fornecedores');

            $table->unsignedInteger("canal_venda_id");
            $table->foreign("canal_venda_id")->references('id')->on('canal_vendas');

            $table->string("token");

            $table->unique(['fornecedor_id', 'canal_venda_id']);

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
        Schema::dropIfExists('split_fornecedores');
    }
}
