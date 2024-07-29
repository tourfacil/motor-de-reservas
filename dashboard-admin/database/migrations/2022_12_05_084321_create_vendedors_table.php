<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendedorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendedores', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome_fantasia');
            $table->string('razao_social')->nullable();
            $table->string('cpf')->nullable();
            $table->string('cnpj')->nullable();
            $table->string('email');
            $table->string('telefone');
            $table->string('site')->nullable();
            $table->string('cep');
            $table->string('endereco');
            $table->string('bairro');
            $table->string('cidade');
            $table->string('estado');
            $table->integer('comissao');
            $table->timestamps();
        });

        Schema::table('reserva_pedidos', function (Blueprint $table) {
            $table->unsignedInteger("vendedor_id")->nullable()->after('afiliado_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger("vendedor_id")->nullable()->after('afiliado_id');
        });

        Schema::table('venda_interna_links', function (Blueprint $table) {
            $table->unsignedInteger("vendedor_id")->nullable()->after('afiliado_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendedores');
    }
}
