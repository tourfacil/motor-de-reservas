<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use TourFacil\Core\Enum\Faturas\TipoFaturaEnum;
use TourFacil\Core\Enum\Faturas\TipoPeriodoFaturaEnum;

class AlterTableFornecedoresAddTipoFaturamento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fornecedores', function (Blueprint $table) {
            $table->enum('tipo_fatura', array_keys(TipoFaturaEnum::TIPOS))->after('termos')->default(TipoFaturaEnum::MENSAL);
            $table->enum('tipo_periodo_fatura', array_keys(TipoPeriodoFaturaEnum::TIPOS_PERIODO))->after('tipo_fatura')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fornecedores', function (Blueprint $table) {
            $table->dropColumn('tipo_fatura');
            $table->dropColumn('tipo_periodo_fatura');
        });
    }
}
