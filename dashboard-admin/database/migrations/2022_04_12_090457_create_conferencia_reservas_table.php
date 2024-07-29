<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConferenciaReservasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conferencia_reservas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('reserva_pedido_id');
            $table->string('observacao')->nullable();
            $table->integer('status_conferencia_reserva')->default(0);
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
        Schema::dropIfExists('conferencia_reservas');
    }
}
