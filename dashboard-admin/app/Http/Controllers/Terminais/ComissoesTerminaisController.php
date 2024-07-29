<?php namespace App\Http\Controllers\Terminais;

use App\Http\Requests\Terminais\UpdatePagamentoTerminalRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use TourFacil\Core\Enum\CanaisVendaEnum;
use TourFacil\Core\Enum\ComissaoStatus;
use TourFacil\Core\Enum\TerminaisEnum;
use TourFacil\Core\Models\PagamentoTerminal;
use TourFacil\Core\Services\TerminalService;

/**
 * Class ComissoesTerminaisController
 * @package App\Http\Controllers\Terminais
 */
class ComissoesTerminaisController extends Controller
{
    /**
     * Lista das comissoes a serem pagas
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        // Lista de meses para tras
        $max_month = 4;

        // Relatorio com 1 mes de atraso
        $mes_passado = Carbon::today();

        // Mes de pesquisa
        $mes_pesquisa = request()->get('periodo', $mes_passado->format('m-Y'));

        // Datas para pesquisa
        $data_inicio = Carbon::createFromFormat("d-m-Y", "01-$mes_pesquisa")->startOfDay();
        $data_final = $data_inicio->copy()->endOfMonth();

        // Pagamento ao terminais
        $relatorio = TerminalService::relatorioPagamentoDeComissoes($data_inicio, $data_final);

        // Inicio da pesquisa
        $lista_mes = $mes_passado->copy()->subMonths($max_month);

        // URL dos terminais
        $url_terminais = str_replace("www.", "", CanaisVendaEnum::URL_TERMINAIS_CDI);

        // Dados das vendas
        $total_comissao = $relatorio->sum('total_comissao');
        $total_pago = $relatorio->sum('total_pago');

        // Mes de referencia das vendas
        $mes_referencia = $data_inicio->copy()->subMonth(TerminaisEnum::MES_PAGAMENTO);

        return view('paginas.terminais.comissoes.comissoes-terminais', compact(
            'relatorio',
            'url_terminais',
            'data_inicio',
            'data_final',
            'lista_mes',
            'max_month',
            'mes_pesquisa',
            'total_comissao',
            'total_pago',
            'mes_referencia'
        ));
    }

    /**
     * @param $pagamento_id
     * @return mixed
     */
    public function view($pagamento_id)
    {
        return PagamentoTerminal::with('terminal')->find($pagamento_id);
    }

    /**
     * Efetua os pagamento das comissoes ao terminal
     *
     * @param UpdatePagamentoTerminalRequest $request
     * @return array
     */
    public function update(UpdatePagamentoTerminalRequest $request)
    {
        // Pagamento do terminal
        $pagamento = PagamentoTerminal::with('comissoesPagamento')->find($request->get('pagamento_id'));

        // Valor pago
        $valor_pago = str_replace(",", ".", str_replace(".", "", $request->get('valor_pago')));

        // Data de pagamento
        $data_pagamento = Carbon::createFromFormat("d/m/Y", $request->get('data_pagamento'));

        // Atualiza os dados do pagamento
        $update = $pagamento->update(["total_pago" => $valor_pago, "data_pagamento" => $data_pagamento]);

        // Atualiza as comissoes para pago
        $pagamento->comissoesPagamento()->update(['status' => ComissaoStatus::PAGO]);

        if($update) {
            return $this->autoResponseJson(true, "Pagamento efetuado", "As comissões foram pagas com sucesso!");
        }

        return $this->autoResponseJson(false, "Pagamento não efetuado", "Não foi possível efetuar o pagamento, tente novamente!");
    }
}
