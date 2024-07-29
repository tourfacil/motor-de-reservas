<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServicosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servicos', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger("fornecedor_id");
            $table->foreign("fornecedor_id")->references('id')->on('fornecedores');

            $table->unsignedInteger("canal_venda_id");
            $table->foreign("canal_venda_id")->references('id')->on('canal_vendas');

            $table->unsignedInteger("destino_id");
            $table->foreign("destino_id")->references('id')->on('destinos');

            $table->char("uuid", 36);
            $table->string("nome");
            $table->string("slug");
            $table->decimal("valor_venda", 9,2);
            $table->decimal("comissao_afiliado", 9);
            $table->integer("antecedencia_venda")->default(\TourFacil\Core\Enum\ServicoEnum::ANTECEDENCIA_DEFAULT);
            $table->enum("tipo_corretagem", [
                \TourFacil\Core\Enum\ServicoEnum::CORRETAGEM_PORCENTUAL,
                \TourFacil\Core\Enum\ServicoEnum::CORRETAGEM_FIXA,
                \TourFacil\Core\Enum\ServicoEnum::SEM_CORRETAGEM,
            ])->default(\TourFacil\Core\Enum\ServicoEnum::SEM_CORRETAGEM);
            $table->decimal("corretagem", 9,2)->default(0);
            $table->string("horario", 150);
            $table->longText("descricao_curta");
            $table->longText("descricao_completa");
            $table->longText("regras")->nullable();
            $table->longText("observacao_voucher")->nullable();
            $table->longText("palavras_chaves")->nullable();
            $table->enum("info_clientes", [
                \TourFacil\Core\Enum\ServicoEnum::SOLICITA_INFO_CLIENTES,
                \TourFacil\Core\Enum\ServicoEnum::SEM_INFO_CLIENTES,
            ])->default(\TourFacil\Core\Enum\ServicoEnum::SOLICITA_INFO_CLIENTES);
            $table->string("localizacao")->nullable();
            $table->string("cidade");
            $table->enum("status", [
                \TourFacil\Core\Enum\ServicoEnum::ATIVO,
                \TourFacil\Core\Enum\ServicoEnum::INATIVO,
                \TourFacil\Core\Enum\ServicoEnum::INDISPONIVEL,
                \TourFacil\Core\Enum\ServicoEnum::PENDENTE,
            ])->default(\TourFacil\Core\Enum\ServicoEnum::PENDENTE);
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['canal_venda_id', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('servicos');
    }
}
