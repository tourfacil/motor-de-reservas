<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHomeDestinosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('home_destinos', function (Blueprint $table) {
            $table->increments('id');
            // Destino
            $table->integer('destino_id')->unsigned();
            $table->foreign('destino_id')->references('id')->on('destinos')->onDelete('cascade');

            $table->integer('ordem');
            $table->string('titulo', 40);
            $table->string('descricao', 70);
            $table->enum('tipo', array_keys(\TourFacil\Core\Enum\TipoHomeDestinoEnum::TIPOS_HOME_DESTINO));

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
        Schema::dropIfExists('home_destinos');
    }
}
