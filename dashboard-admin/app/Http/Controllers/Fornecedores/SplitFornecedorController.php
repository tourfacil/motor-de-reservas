<?php namespace App\Http\Controllers\Fornecedores;

use App\Http\Requests\Fornecedor\StoreSplitRequest;
use App\Http\Requests\Fornecedor\UpdateSplitRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use TourFacil\Core\Models\Fornecedor;
use TourFacil\Core\Models\SplitFornecedor;

/**
 * Class SplitFornecedorController
 * @package App\Http\Controllers\Fornecedores
 */
class SplitFornecedorController extends Controller
{
    /**
     * Retorna os detalhes do split
     *
     * @param $id_split
     * @return mixed
     */
    public function view($id_split)
    {
        return SplitFornecedor::with('canalVenda')->find($id_split);
    }

    /**
     * Cadastra um split de pagamento para o fornecedor
     *
     * @param StoreSplitRequest $request
     * @return array
     */
    public function store(StoreSplitRequest $request)
    {
        // Procura o fornecedor
        $fornecedor = Fornecedor::withTrashed()->find($request->get('fornecedor_id'));

        // Caso encontre o fornecedor
        if(is_object($fornecedor)) {

            // Cadastra o split para o fornecedor
            $store = SplitFornecedor::create($request->all());

            if(is_object($store)) {
                return $this->autoResponseJson(true, "Token cadastrado", "Split de pagamento cadastrado com sucesso!");
            } else {
                return $this->autoResponseJson(false, "Token não cadastrado", "Não foi possível cadastrar o split de pagamento, tente novamente!");
            }
        }

        return $this->autoResponseJson(false, "Token não cadastrado", "Não foi possível encontrar o fornecedor informado, tente novamente!");
    }

    /**
     * Atualiza o token do split de pagamento
     *
     * @param UpdateSplitRequest $request
     * @return array
     */
    public function update(UpdateSplitRequest $request)
    {
        // Procura o split fornecedor
        $split = SplitFornecedor::find($request->get('split_pagamento_id'));

        // Caso encontre o split
        if(is_object($split)) {

            // Atualiza o token do split
            $update = $split->update(['token' => $request->get('token')]);

            if($update) {
                return $this->autoResponseJson(true, "Token atualizado", "Split de pagamento atualizado com sucesso!");
            } else {
                return $this->autoResponseJson(false, "Token não atualizado", "Não foi possível atualizar o split de pagamento, tente novamente!");
            }
        }

        return $this->autoResponseJson(false, "Token não atualizado", "Não foi possível encontrar o split informado, tente novamente!");
    }
}
