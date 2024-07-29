<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTerminaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('terminais', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger("fornecedor_id");
            $table->foreign("fornecedor_id")->references('id')->on('fornecedores');

            $table->unsignedInteger("destino_id");
            $table->foreign("destino_id")->references('id')->on('destinos');

            $table->string("codigo")->unique();
            $table->string("nome");
            $table->string("identificacao");
            $table->enum("fabricante", array_keys(\TourFacil\Core\Enum\TerminaisEnum::FABRICANTES));

            $table->string("nome_responsavel");
            $table->string("email_responsavel");
            $table->string("telefone_responsavel");

            $table->string("endereco_mapa");
            $table->string("nome_local");
            $table->string("endereco");
            $table->string("cidade");
            $table->string("cep");
            $table->enum("estado", array_keys(\TourFacil\Core\Enum\EstadosEnum::ESTADOS));
            $table->string("geolocation");

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('terminais');
    }
}
