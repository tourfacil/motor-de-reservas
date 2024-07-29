<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableReservasEPedidosAddStatusNegado extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        /**
         * ADICIONA O STATUS NEGADO nos PEDIDOS
         */
        $types = array_map(function ($item) {
            return "'{$item}'";
        }, array_keys(\TourFacil\Core\Enum\StatusPedidoEnum::STATUS));

        $status = implode(",", $types);

        DB::statement("ALTER TABLE pedidos MODIFY `status` ENUM({$status}) NOT NULL");

        
        /**
         * ADICIONA O STATUS_PAGAMENTO EXPIRADO nos PEDIDOS
         */
        $types = array_map(function ($item) {
            return "'{$item}'";
        }, array_keys(\TourFacil\Core\Enum\StatusPagamentoEnum::STATUS));

        $status = implode(",", $types);

        DB::statement("ALTER TABLE pedidos MODIFY `status_pagamento` ENUM({$status}) NOT NULL");


        /**
         * ADICIONA O STATUS NEGADO nas RESERVAS
         */
        $types = array_map(function ($item) {
            return "'{$item}'";
        }, array_keys(\TourFacil\Core\Enum\StatusReservaEnum::STATUS));

        $status = implode(",", $types);

        DB::statement("ALTER TABLE reserva_pedidos MODIFY `status` ENUM({$status}) NOT NULL");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
