<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFornecedorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fornecedores', function (Blueprint $table) {
            $table->increments('id');
            $table->char("uuid", 36);
            $table->string("cnpj", 20)->unique();
            $table->string("razao_social", 150);
            $table->string("nome_fantasia", 150);
            $table->string("responsavel", 150);
            $table->string("email_responsavel", 150);
            $table->string("telefone_responsavel", 150);
            $table->string("cep", 10);
            $table->string("endereco", 150);
            $table->string("bairro", 150);
            $table->string("cidade", 150);
            $table->string("estado", 150);
            $table->string("email");
            $table->string("telefone", 150);
            $table->string("site", 150)->nullable();
            $table->longText("termos")->nullable();
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
        Schema::dropIfExists('fornecedores');
    }
}
