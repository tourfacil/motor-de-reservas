<?php namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use function GuzzleHttp\Psr7\build_query;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use TourFacil\Core\Services\ServicoService;

/**
 * Class PesquisaController
 * @package App\Http\Controllers\Ecommerce
 */
class PesquisaController extends Controller
{
    /**
     * Pesquisa no site
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     * @throws \Exception
     */
    public function pesquisar()
    {
        // Destino na sessao
        $destino = $this->getDestinoSession()['destino'];

        // Filtros ativos na URL
        $filtros_ativos = $this->filtrosUrl();

        // Busca na URL
        $keyword = mb_strtolower(htmlspecialchars(request()->get('q', null),ENT_QUOTES, 'UTF-8'));

        // Caso tenha pesquisa vazia
        if(is_null($keyword)) return redirect()->route('ecommerce.index');

        // Recupera os servicos
        $servicos = ServicoService::pesquisarServicos($keyword);

        // Aplica os filtros nos servicos
        $dados_categoria = $this->applyFilters($servicos, $filtros_ativos);

        // Servicos filtrados
        $servicos = $dados_categoria['servicos'];

        // Filtro por secoes
        $filtro_secoes = $dados_categoria['secoes'];

        // Filtro das cidades
        $filtro_cidades = $dados_categoria['cidades'];

        // Secao do filtro
        $secao_atual = $dados_categoria['secao_atual'];

        // Cidade atual do filtro
        $cidade_atual = $dados_categoria['cidade_atual'];

        // Menor valor
        $menor_valor = $dados_categoria['menor_valor'];

        // Quantidade de servicos
        $qtd_servicos = sizeof($servicos);

        return view('paginas.pesquisa', compact(
            'destino',
            'servicos',
            'filtro_secoes',
            'filtro_cidades',
            'filtros_ativos',
            'qtd_servicos',
            'secao_atual',
            'cidade_atual',
            'keyword',
            'menor_valor'
        ));
    }

    /**
     * Filtros na URL
     *
     * @return array
     */
    private function filtrosUrl()
    {
        // Filtros aplicados
        $filtros = [];

        // Filtros na URL
        $cidade = request()->get('cidade');
        $secao = request()->get('secao');
        $ordem = request()->get('ordem');

        // Verifica se tem filtro na URL
        if(is_string($cidade)) $filtros['cidade'] = $cidade;
        if(is_string($secao)) $filtros['secao'] = $secao;
        if(is_string($ordem)) $filtros['ordem'] = $ordem;

        // Array com as querys
        $query_secoes = Arr::except($filtros, 'secao');
        $query_cidade = Arr::except($filtros, 'cidade');
        $query_ordem = Arr::except($filtros, 'ordem');

        return [
            'cidade' => $cidade,
            'secao' => $secao,
            'ordem' => $ordem,
            'query_secoes' => (sizeof($query_secoes)) ? build_query($query_secoes) : "",
            'query_cidade' => (sizeof($query_cidade)) ? build_query($query_cidade) : "",
            'query_ordem' => (sizeof($query_ordem)) ? build_query($query_ordem) : "",
        ];
    }

    /**
     * Aplica os filtros nos servicos
     *
     * @param $servicos
     * @param $filtros
     * @return array
     */
    private function applyFilters($servicos, $filtros)
    {
        $novos_servicos = collect();
        $secoes = [];
        $cidades = [];
        $secao_atual = null;
        $cidade_atual = null;
        $menor_valor = 0;

        // Filtros ativos
        $filtro_secao = $filtros['secao'];
        $filtro_cidade = $filtros['cidade'];
        $filtro_ordem = $filtros['ordem'];

        // Percorre os servicos da categoria
        foreach ($servicos as $servico) {

            // Variaveis para filtro
            $secoes_servico = "";
            $f_secao = true;
            $f_cidade = true;

            // Monta a lista dos filtros por secao
            foreach ($servico->secoesCategoria as $secao) {
                $secao_slug = Str::slug($secao->nome);
                $secoes[$secao_slug] = ['nome' => $secao->nome, 'slug' => $secao_slug];
                // String com as secoes do servico
                $secoes_servico .= $secao_slug;
            }

            // Lista das cidades
            $cidade_slug = Str::slug($servico->cidade);
            $cidades[$cidade_slug] = ['cidade' => $servico->cidade, 'slug' => $cidade_slug];

            // Verifica se o servico possui a secao do filtro
            if(is_string($filtro_secao)) {
                $f_secao = Str::contains($secoes_servico, $filtro_secao);
                $secao_atual = $secoes[$filtro_secao] ?? "";
            }

            // Verifica se o servico possui a cidade do filtro
            if(is_string($filtro_cidade)) {
                $f_cidade = ($cidade_slug == $filtro_cidade);
                if($cidade_slug == $filtro_cidade) $cidade_atual = $servico->cidade;
            }

            // Novo array com os servicos
            if($f_secao && $f_cidade) {

                // Salva o menor valor dos servicos
                if($servico->valor_venda < $menor_valor || $menor_valor == 0) {
                    $menor_valor = $servico->valor_venda;
                }

                // Salva o ranking do servico
                $servico->posicao = $servico->ranking->ranking ?? 0;

                // Salva o servico no array
                $novos_servicos->push($servico);
            }
        }

        // Caso tenha ordenação por valor
        if($filtro_ordem == "menor-preco") {
            $novos_servicos = $novos_servicos->sortBy('valor_venda');
        } else {
            $novos_servicos = $novos_servicos->sortByDesc('posicao');
        }

        return [
            'servicos' => $novos_servicos,
            'secoes' => $secoes,
            'cidades' => $cidades,
            'secao_atual' => $secao_atual,
            'cidade_atual' => $cidade_atual,
            'menor_valor' => $menor_valor
        ];
    }

    /**
     * Pesquisa serviço ajax
     *
     * @return mixed
     */
    public function searchAjax()
    {
        // Busca na URL
        $keyword = mb_strtolower(htmlspecialchars(request()->get('q', null),ENT_QUOTES, 'UTF-8'));

        // Caso tenha pesquisa vazia
        if(is_null($keyword)) return redirect()->route('ecommerce.index');

        // Recupera os servicos na JSON
        $servicos_json = json_decode(file_get_contents(public_path('/search.json')), true);
        $match = collect();

        // Filtra os servicos
        foreach ($servicos_json as $servico) {
            if(Str::contains($servico['palavras_chaves'], $keyword)) {
                $match->add($servico);
            }
        }

        return $match->sortByDesc('ranking')->values()->slice(0, 10);
    }
}
