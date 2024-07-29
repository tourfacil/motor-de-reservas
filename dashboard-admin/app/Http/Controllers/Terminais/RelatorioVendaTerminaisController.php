<?php namespace App\Http\Controllers\Terminais;

use App\Exports\Terminais\RelatorioVendasTerminaisExport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PDF;
use TourFacil\Core\Enum\CanaisVendaEnum;
use TourFacil\Core\Enum\ComissaoStatus;
use TourFacil\Core\Models\Terminal;
use TourFacil\Core\Services\TerminalService;

/**
 * Class RelatorioVendaTerminaisController
 * @package App\Http\Controllers\Terminais
 */
class RelatorioVendaTerminaisController extends Controller
{
    /**
     * Vendas dos terminais
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        // Lista de meses para tras
        $max_month = 4;

        // Mes de pesquisa
        $mes_pesquisa = request()->get('periodo', date('m-Y'));

        // Datas para pesquisa
        $data_inicio = Carbon::createFromFormat("d-m-Y", "01-$mes_pesquisa")->startOfDay();
        $data_final = $data_inicio->copy()->endOfMonth();

        // Vendas dos terminais
        $relatorio = TerminalService::relatorioVendasTerminais($data_inicio, $data_final);

        // Inicio da pesquisa
        $lista_mes = Carbon::today()->subMonths($max_month);

        // URL dos terminais
        $url_terminais = str_replace("www.", "", CanaisVendaEnum::URL_TERMINAIS_CDI);

        // Dados das vendas
        $total_vendido = $relatorio->sum('vendido');
        $total_comissao = $relatorio->sum('comissao');
        $total_ingressos = $relatorio->sum('ingressos');

        return view('paginas.terminais.relatorios.relatorios-terminais', compact(
            'relatorio',
            'url_terminais',
            'data_inicio',
            'data_final',
            'lista_mes',
            'max_month',
            'mes_pesquisa',
            'total_comissao',
            'total_ingressos',
            'total_vendido'
        ));
    }

    /**
     * Detalhe das vendas do terminal
     *
     * @param $terminal_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view($terminal_id)
    {
        // Lista de meses para tras
        $max_month = 4;

        // Mes de pesquisa
        $mes_pesquisa = request()->get('periodo', date('m-Y'));

        // Datas para pesquisa
        $data_inicio = Carbon::createFromFormat("d-m-Y", "01-$mes_pesquisa")->startOfDay();
        $data_final = $data_inicio->copy()->endOfMonth();

        // Terminal com as comissoes
        $terminal = Terminal::with([
            'comissaoTerminal' => function ($q) use ($data_inicio, $data_final) {
                return $q->with([
                    'reservaPedido.servico',
                    'reservaPedido.agendaDataServico'
                ])->whereBetween('created_at', [$data_inicio, $data_final]);
            }
        ])->find($terminal_id);

        // Valores de vendas do terminal
        $valores_venda = TerminalService::valoresVendaTerminal($terminal->id, $data_inicio, $data_final, false);

        // Inicio da pesquisa
        $lista_mes = Carbon::today()->subMonths($max_month);

        return view('paginas.terminais.relatorios.detalhe-relatorio-terminal', compact(
            'terminal',
            'data_inicio',
            'data_final',
            'valores_venda',
            'mes_pesquisa',
            'lista_mes',
            'max_month'
        ));
    }

    /**
     * relatorio de vendas
     *
     * @param $terminal_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadVendasTerminal($terminal_id)
    {
        // Tipo do download
        $tipo = request()->get('type', 'pdf');

        // Mes de pesquisa
        $mes_pesquisa = request()->get('periodo', date('m-Y'));

        // Datas para pesquisa
        $data_inicio = Carbon::createFromFormat("d-m-Y", "01-$mes_pesquisa")->startOfDay();
        $data_final = $data_inicio->copy()->endOfMonth();

        // Nome do mes de pesquisa
        $mes_pesquisa_pt = mesPT($data_inicio->month);

        // Terminal com as comissoes
        $terminal = Terminal::with([
            'comissaoTerminal' => function ($q) use ($data_inicio, $data_final) {
                return $q->with([
                    'reservaPedido.servico',
                    'reservaPedido.agendaDataServico',
                    'reservaPedido.quantidadeReserva.variacaoServico'
                ])->whereIn('status', [ComissaoStatus::AGUARDANDO, ComissaoStatus::PAGO])
                    ->whereBetween('created_at', [$data_inicio, $data_final]);
            }
        ])->find($terminal_id);

        // Valores de vendas do terminal
        $valores_venda = TerminalService::valoresVendaTerminal($terminal->id, $data_inicio, $data_final, false);

        // URL dos terminais
        $url_terminais = CanaisVendaEnum::URL_TERMINAIS_CDI;

        // Nome da view
        $view_name = "paginas.terminais.download.detalhe-vendas-terminal-$tipo";

        // Variavel para controlar como puxa os assets
        $inline_pdf = true;

        // Variaveis para as views
        $variavies = compact(
            'data_inicio',
            'data_final',
            'inline_pdf',
            'terminal',
            'valores_venda',
            'url_terminais'
        );

        // PDF com as vendas
        if($inline_pdf && $tipo == "pdf") {
            return PDF::loadView($view_name, $variavies)
                ->download("Vendas {$terminal->nome} - {$mes_pesquisa_pt} de {$data_final->year}.pdf");
        }

        // XLS com as vendas
        if($tipo == "xls") {
            return (new RelatorioVendasTerminaisExport($view_name, $variavies))
                ->download("{$terminal->nome} - {$mes_pesquisa_pt} de {$data_final->year}.xlsx");
        }

        return view($view_name, $variavies);
    }

    /**
     * Faz o download dos relatorios de TODOS os terminais
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function download()
    {
        // Tipo do download
        $tipo = request()->get('type', 'pdf');

        // Mes de pesquisa
        $mes_pesquisa = request()->get('periodo', date('m-Y'));

        // Datas para pesquisa
        $data_inicio = Carbon::createFromFormat("d-m-Y", "01-$mes_pesquisa")->startOfDay();
        $data_final = $data_inicio->copy()->endOfMonth();

        // Vendas dos terminais
        $relatorio = TerminalService::relatorioVendasTerminais($data_inicio, $data_final, true);

        // Nome do mes de pesquisa
        $mes_pesquisa_pt = mesPT($data_inicio->month);

        // URL dos terminais
        $url_terminais = CanaisVendaEnum::URL_TERMINAIS_CDI;

        // Dados das vendas
        $total_vendido = $relatorio->sum('vendido');
        $total_comissao = $relatorio->sum('comissao');
        $total_ingressos = $relatorio->sum('ingressos');

        // Nome da view
        $view_name = "paginas.terminais.download.vendas-terminais-$tipo";

        // Variavel para controlar como puxa os assets
        $inline_pdf = true;

        // Variaveis para as views
        $variavies = compact(
            'relatorio',
            'inline_pdf',
            'data_inicio',
            'data_final',
            'url_terminais',
            'mes_pesquisa_pt',
            'total_vendido',
            'total_ingressos',
            'total_comissao'
        );

        // PDF com as vendas
        if($inline_pdf && $tipo == "pdf") {
            return PDF::loadView($view_name, $variavies)
                ->download("Relatório Vendas Terminais - {$mes_pesquisa_pt} de {$data_final->year}.pdf");
        }

        // XLS com as vendas
        if($tipo == "xls") {
            return (new RelatorioVendasTerminaisExport($view_name, $variavies))
                ->download("Relatório Vendas Terminais - {$mes_pesquisa_pt} de {$data_final->year}.xlsx");
        }

        return view($view_name, $variavies);
    }
}
