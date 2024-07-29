<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string("phone", '20')->nullable();
            $table->date("birthdate")->nullable();
            $table->longText("avatar")->nullable();
            $table->enum('level', [
                \App\Enum\UserLevelEnum::ADMIN,
                \App\Enum\UserLevelEnum::MEDIO,
                \App\Enum\UserLevelEnum::BASICO,
            ])->default(\App\Enum\UserLevelEnum::BASICO);
            $table->integer("canal_venda_id")->default(1)->nullable();
            $table->string("address")->nullable();
            $table->string("city", '100')->nullable();
            $table->string("state", '100')->nullable();
            $table->string("zip", '10')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
