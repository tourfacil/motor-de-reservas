<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categorias', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger("destino_id");
            $table->foreign("destino_id")->references('id')->on('destinos');
            $table->char("uuid", 36);

            $table->string('nome', 150);
            $table->string("slug");
            $table->longText("descricao");
            $table->longText("foto")->nullable();
            $table->decimal("valor_minimo", 9,2)->default(0);
            $table->integer("posicao_menu")->default(99);

            $table->softDeletes();
            $table->timestamps();

            // Não pode possuir slug duplicados para o mesmo destino
            $table->unique(['destino_id', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categorias');
    }
}
