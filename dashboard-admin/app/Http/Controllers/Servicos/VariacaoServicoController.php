<?php

namespace App\Http\Controllers\Servicos;

use App\Http\Requests\UpdateVariacaoServicoRequest;
use Illuminate\Http\Request;
use App\Http\Requests\StoreVariacaoServicoRequest;
use App\Http\Controllers\Controller;
use TourFacil\Core\Enum\VariacaoServicoEnum;
use TourFacil\Core\Models\VariacaoServico;

/**
 * Class VariacaoServicoController
 * @package App\Http\Controllers\Servicos
 */
class VariacaoServicoController extends Controller
{
    /**
     * Detalhes da variacao
     *
     * @param $variacao_id
     * @return mixed
     */
    public function view($variacao_id)
    {
        return VariacaoServico::find($variacao_id);
    }

    /**
     * Cadastra uma nova variação para o servico
     *
     * @param StoreVariacaoServicoRequest $request
     * @return array
     */
    public function store(StoreVariacaoServicoRequest $request)
    {
        // Valor net do SERVICO
        $net_servico = str_replace(",", ".", str_replace(".", "", $request->get('net_servico')));

        // Valor net da VARIACAO
        $net_variacao = str_replace(",", ".", str_replace(".", "", $request->get('net_variacao')));

        // Valor venda da VARIACAO
        $venda_variacao = str_replace(",", ".", str_replace(".", "", $request->get('venda_variacao')));

        // Calcula o markup e porcentagem da variacao
        $markup_variacao = calculaMarkupVariacao($net_servico, $net_variacao, $venda_variacao);

        // Processa o valor do campo 'min_pax', garantindo que é um valor numérico válido
        $min_pax = $request->get('min_pax') ? (int) $request->get('min_pax') : null;

        // Mescla os dados da variacao com markup
        $dados_variacao = array_merge($request->all(), $markup_variacao, ['min_pax' => $min_pax]);

        // Caso a variacao seja destaque
        if ($request->get('destaque') == VariacaoServicoEnum::VARIACAO_DESTAQUE) {
            // Retira as outras variacoes do destaque
            VariacaoServico::where('servico_id', $request->get('servico_id'))->update([
                'destaque' => VariacaoServicoEnum::VARIACAO_NORMAL
            ]);
        }

        // Cadastra a variacao
        $variacao = VariacaoServico::create($dados_variacao);

        // Caso encontre o campo
        if (is_object($variacao)) {
            return $this->autoResponseJson(true, "Variação cadastrada", "A variação " . $variacao->nome . " foi cadastrada com sucesso!");
        }

        return $this->autoResponseJson(false, "Variação não cadastrada", "Não foi possível cadastrar a variação, tente novamente!");
    }

    /**
     * Atualiza ou desativa a variacao no servico
     *
     * @param UpdateVariacaoServicoRequest $request
     * @return array
     */
    public function update(UpdateVariacaoServicoRequest $request)
    {
        $markup_variacao = [];

        // Recupera os dados da variacao
        $variacao = VariacaoServico::find($request->get('variacao_id'));

        // Caso seja para atualiza a comissao do variacao
        if ($request->get('edit_comissao') == "on") {
            // Valor net do SERVICO
            $net_servico = str_replace(",", ".", str_replace(".", "", $request->get('net_servico')));

            // Valor net da VARIACAO
            $net_variacao = str_replace(",", ".", str_replace(".", "", $request->get('net_variacao')));

            // Valor venda da VARIACAO
            $venda_variacao = str_replace(",", ".", str_replace(".", "", $request->get('venda_variacao')));

            // Calcula o markup e porcentagem da variacao
            $markup_variacao = calculaMarkupVariacao($net_servico, $net_variacao, $venda_variacao);
        }

        // Mescla os dados para atualizar com o markup
        $dados = array_merge($request->all(), $markup_variacao);

        // Caso seja para deletar a variacao
        $delete_varation = $request->get('delete_variacao');

        // Caso seja para deletar a variacao
        if ($delete_varation == "on") {
            // Retira do destaque
            $variacao->update(['destaque' => VariacaoServicoEnum::VARIACAO_NORMAL]);
            // Deleta a variacao
            $variacao->delete();
            // Retorno auto json
            return $this->autoResponseJson(true, "Variação desativada", "A variação foi desativada com sucesso!");
        }

        // Caso a variacao vire destaque
        if ($request->get('destaque') == VariacaoServicoEnum::VARIACAO_DESTAQUE && $variacao->destaque == VariacaoServicoEnum::VARIACAO_NORMAL) {
            // Retira as outras variacoes do destaque
            VariacaoServico::where('servico_id', $variacao->servico_id)->update([
                'destaque' => VariacaoServicoEnum::VARIACAO_NORMAL
            ]);
        }

        // Se a requisição contém um valor para min_pax, processa esse valor
        if ($request->has('min_pax')) {
            $dados['min_pax'] = $request->input('min_pax');
        }

        // Atualiza a variacao
        $variacao->update($dados);

        return $this->autoResponseJson(true, "Variação atualizada", "A variação foi atualizada com sucesso!");
    }

    /**
     * Atualiza o markup de uma variacao
     *
     * @param Request $request
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateMarkup(Request $request)
    {
        $this->validate($request, [
            'markup' => 'required',
            'variacao_id' => 'required|integer'
        ]);

        // Recupera os dados da variacao
        $variacao = VariacaoServico::find($request->get('variacao_id'));

        // Atualiza o markup da varicao
        $update = $variacao->update(['markup' => $request->get('markup')]);

        if ($update) {
            return $this->autoResponseJson(true, "Markup atualizado", "O markup foi atualizado com sucesso!");
        }

        return $this->autoResponseJson(false, "Markup não atualizado", "Não foi possível atualizar o markup, tente novamente!");
    }

    /**
     * Reativa uma variação
     *
     * @param Request $request
     * @return array
     */
    public function reactivate(Request $request)
    {
        // Recupera a variacao
        $variacao = VariacaoServico::withTrashed()->find($request->get('variacao_id'));

        // Caso encontre a variacao
        if (is_object($variacao)) {

            // Reativa a variacao
            $variacao->restore();

            return $this->autoResponseJson(true, "Variação reativada", "A variação {$variacao->nome} foi reativada com sucesso!");
        }

        return $this->autoResponseJson(false, "Variação não encontrado", "A variação não foi encontrada, tente novamente!");
    }
}
