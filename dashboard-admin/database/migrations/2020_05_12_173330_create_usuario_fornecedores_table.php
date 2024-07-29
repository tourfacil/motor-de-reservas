<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsuarioFornecedoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuario_fornecedores', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger("fornecedor_id");
            $table->foreign("fornecedor_id")->references('id')->on('fornecedores');

            $table->string('nome');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('level', [
                \App\Enum\UserLevelEnum::ADMIN,
                \App\Enum\UserLevelEnum::MEDIO,
                \App\Enum\UserLevelEnum::BASICO,
            ])->default(\App\Enum\UserLevelEnum::BASICO);

            $table->rememberToken();
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
        Schema::dropIfExists('usuario_fornecedores');
    }
}
