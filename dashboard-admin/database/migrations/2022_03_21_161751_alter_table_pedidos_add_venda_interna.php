<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTablePedidosAddVendaInterna extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * ADICIONA MÉTODO DE PAGAMENTO INTERNO
         */
        $metodos_pagamento = array_map(function ($metodo_pagamento) {
            return "'{$metodo_pagamento}'";
        }, array_keys(\TourFacil\Core\Enum\MetodoPagamentoEnum::METHODS));

        $metodos_pagamento = implode(",", $metodos_pagamento);

        DB::statement("ALTER TABLE pedidos MODIFY metodo_pagamento ENUM({$metodos_pagamento}) COLLATE utf8_unicode_ci NOT NULL AFTER status_pagamento");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
