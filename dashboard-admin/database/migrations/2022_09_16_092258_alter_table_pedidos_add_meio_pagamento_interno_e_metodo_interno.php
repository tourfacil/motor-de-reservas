<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTablePedidosAddMeioPagamentoInternoEMetodoInterno extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('pedidos', function (Blueprint $table) {

            $table->enum("meio_pagamento_interno", [
                array_keys(\TourFacil\Core\Enum\MeioPagamentoInternoEnum::MEIOS)
            ])->nullable()->after('metodo_pagamento');

            $table->enum("metodo_pagamento_interno", [
                array_keys(\TourFacil\Core\Enum\MetodoPagamentoInternoEnum::METODOS)
            ])->nullable()->after('meio_pagamento_interno');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Remove o campo de desconto do pix dos pedidos
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropColumn('meio_pagamento_interno');
            $table->dropColumn('metodo_pagamento_interno');
        });
    }
}
