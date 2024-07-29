<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger("canal_venda_id");
            $table->foreign("canal_venda_id")->references('id')->on('canal_vendas');
            $table->char("uuid", 36);
            $table->string("nome");
            $table->string("email");
            $table->string("cpf", 20)->nullable();
            $table->date("nascimento")->nullable();
            $table->string("telefone", 25)->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->softDeletes();
            $table->unique(['canal_venda_id', 'email']);
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
        Schema::dropIfExists('clientes');
    }
}