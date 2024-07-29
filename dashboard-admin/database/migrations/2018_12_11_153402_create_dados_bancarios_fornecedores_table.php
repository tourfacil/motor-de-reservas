<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDadosBancariosFornecedoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dados_bancarios_fornecedores', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger("fornecedor_id");
            $table->foreign("fornecedor_id")->references('id')->on('fornecedores');
            $table->string("banco", 100);
            $table->string("agencia", 100);
            $table->string("conta", 100);
            $table->enum("tipo_conta", [
                \App\Enum\BancosEnum::CONTA_CORRENTE,
                \App\Enum\BancosEnum::CONTA_POUPANCA,
                \App\Enum\BancosEnum::CONTA_SALARIO,
            ]);
            $table->longText("observacoes")->nullable();
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
        Schema::dropIfExists('dados_bancarios_fornecedores');
    }
}
