<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDestinosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('destinos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger("canal_venda_id");
            $table->foreign("canal_venda_id")->references('id')->on('canal_vendas');
            $table->string("nome", 150);
            $table->string("slug");
            $table->string("descricao_curta");
            $table->longText("descricao_completa");
            $table->decimal("valor_minimo", 9,2)->default(0);
            $table->longText("foto")->nullable();
            $table->softDeletes();
            $table->timestamps();

            // Não pode possuir slug duplicados para o mesmo canal de venda
            $table->unique(['canal_venda_id', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('destinos');
    }
}
