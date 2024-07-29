<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSecaoCategoriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('secao_categorias', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger("categoria_id");
            $table->foreign("categoria_id")->references('id')->on('categorias');
            $table->string("nome", 150);
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
        Schema::dropIfExists('secao_categorias');
    }
}
