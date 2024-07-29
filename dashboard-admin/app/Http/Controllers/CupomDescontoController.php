<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use TourFacil\Core\Enum\Descontos\TipoDescontoValor;
use TourFacil\Core\Models\CupomDesconto;
use TourFacil\Core\Models\Pedido;
use TourFacil\Core\Models\ReservaPedido;
use TourFacil\Core\Models\Servico;

/**
 *
 */
class CupomDescontoController extends Controller
{
    /**
     * Mostra a página com todos os cupons
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function index() {

        // Busca todos os cupons do banco de dados
        $cupons = CupomDesconto::all();

        // Monta o array com os dados da view
        $dados = [
            'cupons' => $cupons,
        ];

        // Retorna a view com os dados
        return view('paginas.descontos.cupons.cupons', $dados);
    }

    /**
     * Retorna a view para a criação de novos cupons de desconto
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function create() {

        // Busca todos os serviços ativos do banco de dados
        $servicos = Servico::where('status', 'ATIVO')->get();

        // Monta o array com os dados da view
        $dados = [
            'servicos' => $servicos,
        ];

        // Retorna a view com os dados
        return view('paginas.descontos.cupons.novo-cupom', $dados);
    }

    /**
     * Post para salvar os dados que vem da view de criação de novos cupons
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request) {

        // Monta um array com os dados do novo cupom
        $dados_cupom = [
            'nome_publico' => $request->get('nome_publico'),
            'nome_interno' => $request->get('nome_interno'),
            'codigo' => strtoupper($request->get('codigo')),
            'maximo_utilizacoes' => $request->get('maximo_utilizacao'),
            'tipo_desconto_fornecedor' => $request->get('desconto_net'),
            'tipo_desconto_valor' => $request->get('tipo_desconto'),
            'status' => $request->get('status'),
        ];

        // Caso o cupom seja somente para um serviço. Ele adiciona o ID do serviço no array
        if($request->get('servico') != '0') {
            $dados_cupom['servico_id'] = $request->get('servico');
        }

        // Salva o máximo de utilizações vindo do FORM
        $maximo_utilizacao = $request->get('maximo_utilizacao');

        // Caso o usuário informar o valor zero no máximo de utilizações..
        // Ele deixa NULL no banco para tornar ilimitado
        if($maximo_utilizacao == "0") {
            $dados_cupom['maximo_utilizacoes'] = null;
        } else {
            $dados_cupom['maximo_utilizacoes'] = $maximo_utilizacao;
        }

        // Caso o máximo de utilização seja informado negativo. Ele seta para NULL
        if($maximo_utilizacao < 0) {
            $dados_cupom['maximo_utilizacoes'] = null;
        }

        // Guarda o tipo de desconto em uma váriavel
        $tipo_desconto = $request->get('tipo_desconto');

        if($tipo_desconto == TipoDescontoValor::PERCENTUAL) {
            $dados_cupom['desconto'] = $request->get('valor_percentual');
        } else if($tipo_desconto == TipoDescontoValor::FIXO) {

            // Salva o valor_fixo em uma váriavel
            $valor_fixo = $request->get('valor_fixo');

            // Remove os pontos que separam as casas decimais
            $valor_fixo = str_replace('.', '', $valor_fixo);

            // Substitui as virgulas que separam os decimais por pontos
            $valor_fixo = str_replace(',', '.', $valor_fixo);

            // Salva no array
            $dados_cupom['desconto'] = $valor_fixo;
        }

        // Cria o novo cupom de desconto
        $novo_cupom = CupomDesconto::create($dados_cupom);

        // Redireciona para a listagem de cupons
        return redirect()->route('app.descontos.cupom.index');
    }

    /**
     * Retorna a view de edição de cupons de desconto
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function edit(Request $request) {

        // Busca o cupom no banco de dados
        $cupom = CupomDesconto::find($request->get('cupom_id'));

        // Caso não encontre o cupom, retorna a listagem
        if($cupom == null) {
            return redirect()->route('app.descontos.cupom.index');
        }

        // Busca todos os serviços ativos do banco de dados
        $servicos = Servico::where('status', 'ATIVO')->get();

        // Monta o array com os dados da view
        $dados = [
            'cupom' => $cupom,
            'servicos' => $servicos,
        ];

        return view('paginas.descontos.cupons.editar-cupom', $dados);
    }

    /**
     * Post para salvar os cupons de foram editados
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request) {

        // Busca o cupom no banco de dados
        $cupom = CupomDesconto::find($request->get('cupom_id'));

        // Monta um array com os dados do novo cupom
        // Somente os dados que podem ser atualizados
        // Nome_interno, Maximo_utilizacoes e status
        $dados_cupom = [
            'nome_interno' => $request->get('nome_interno'),
            'maximo_utilizacoes' => $request->get('maximo_utilizacao'),
            'status' => $request->get('status'),
        ];

        // Cria o novo cupom de desconto
        $cupom->update($dados_cupom);

        return redirect()->back();
    }

    public function relatorio(Request $request)
    {
        $dados = [];

        $pedidos = Pedido::whereHas('cupom')
        ->whereIn('status', ['PAGO', 'UTILIZADO']);

        if($request->get('cupom')) {

            $pedidos->whereHas('cupom', function ($cupom) use ($request) {
                $cupom->where('id', $request->get('cupom'));
            });

            $dados['cupom'] = CupomDesconto::find($request->get('cupom'));
        }

        $dados['pedidos'] = $pedidos->get();

        return view('paginas.descontos.cupons.relatorio-cupom', $dados);
    }
}
