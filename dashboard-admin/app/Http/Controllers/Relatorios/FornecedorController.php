<?php namespace App\Http\Controllers\Relatorios;

use App\Enum\LogoCanalVendaEnum;
use App\Exports\Terminais\RelatorioVendasTerminaisExport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PDF;
use TourFacil\Core\Services\Relatorios\IngressosVendidoService;

/**
 * Class FornecedorController
 * @package App\Http\Controllers\Relatorios
 */
class FornecedorController extends Controller
{
    /**
     * Lista dos ingressos vendidos
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        // Canal de venda na sessao do usuario
        $canal_venda = canalSession()->getCanal();

        // Datas para pesquisa
        $periodo_pesquisa = periodoPesquisa();

        // Status do filtro para puxar somente fornecedores que autenticaram no periodo
        $somente_autenticados = request()->get('autenticados', 'false');
        $bool_autenticados = ($somente_autenticados == 'true');

        // Preset do filtro selecionado
        $filtro_selecionado = request()->get('periodo', 'este_mes');

        // Data de inicio do filtro
        $pp_start = request()->get('inicio', $periodo_pesquisa[$filtro_selecionado]['start'] ?? null);

        // Data final do filtro
        $pp_end = request()->get('final', $periodo_pesquisa[$filtro_selecionado]['end'] ?? null);

        // Data de utilização ou de data de venda
        $tipo_data = request()->get('tipo_data');

        // Caso não seja inserida uma data para a pesquisa. Ele busca a data de venda
        if($tipo_data == null) {
            $tipo_data = "VENDA";
        }

        // Para as datas que estão na URl
        if(! is_object($pp_start) || ! is_object($pp_end)) {
            // Converte as datas para a Carbon
            $pp_start = Carbon::createFromFormat('d-m-Y H:i:s', $pp_start . " 00:00:00");
            // Converte as datas para a Carbon
            $pp_end = Carbon::createFromFormat('d-m-Y H:i:s', $pp_end . " 23:59:59");
        }

        // Recupera os fornecedores que tiveram vendas ou autenticaram ingressos no periodo
        $fornecedores = IngressosVendidoService::relatorioFornecedoresComVendas($pp_start, $pp_end, $canal_venda->id, $bool_autenticados, $tipo_data);

        // Soma os valores totais das reservas
        $valores = $this->somarValoresVendidos($fornecedores);

        // Soma dos valores
        $total_vendido = $valores['vendido'];
        $total_net = $valores['tarifa_net'];
        $total_quantidade = $valores['quantidade'];

        $dados = [
            'pp_start' => $pp_start,
            'pp_end' => $pp_end,
            'fornecedores' => $fornecedores,
            'total_vendido' => $total_vendido,
            'total_net' => $total_net,
            'total_quantidade' => $total_quantidade,
            'canal_venda' => $canal_venda,
            'somente_autenticados' => $somente_autenticados,
            'periodo_pesquisa' => $periodo_pesquisa,
            'filtro_selecionado' => $filtro_selecionado,
            'tipo_data' => $tipo_data
        ];

        return view('paginas.relatorios.fornecedor', $dados);
    }

    /**
     * Soma os valores para o relatorio
     *
     * @param $fornecedores
     * @return array
     */
    public function somarValoresVendidos($fornecedores) {

        // Array com as variaveis do resultado
        $return = ['vendido' => 0, 'tarifa_net' => 0, 'quantidade' => 0];

        // Soma os valores somente das reservas validas
        foreach ($fornecedores as $fornecedor) {
            $return['vendido'] += $fornecedor->vendido;
            $return['tarifa_net'] += $fornecedor->tarifa_net;
            $return['quantidade'] += $fornecedor->quantidade;
        }

        return $return;
    }

    /**
     * Download do relatorio em PDF ou XLS
     *
     * @return mixed
     */
    public function download()
    {
        // Canal de venda na sessao do usuario
        $canal_venda = canalSession()->getCanal();

        // Tipo de download
        $tipo = request()->get('type', 'pdf');

        // Status do filtro para puxar somente fornecedores que autenticaram no periodo
        $somente_autenticados = request()->get('autenticados', 'false');
        $bool_autenticados = ($somente_autenticados == 'true');

        // Data de inicio do filtro
        $pp_start = request()->get('inicio');

        // Data final do filtro
        $pp_end = request()->get('final');

        // Converte as datas para a Carbon
        $pp_start = Carbon::createFromFormat('d-m-Y H:i:s', $pp_start . " 00:00:00");
        // Converte as datas para a Carbon
        $pp_end = Carbon::createFromFormat('d-m-Y H:i:s', $pp_end . " 23:59:59");

        $tipo_data = request()->get('tipo_data');

        // Recupera os fornecedores que tiveram vendas ou autenticaram ingressos no periodo
        $fornecedores = IngressosVendidoService::relatorioFornecedoresComVendas($pp_start, $pp_end, $canal_venda->id, $bool_autenticados, $tipo_data);

        // Soma os valores totais das reservas
        $valores = $this->somarValoresVendidos($fornecedores);

        // Soma dos valores
        $total_vendido = $valores['vendido'];
        $total_net = $valores['tarifa_net'];
        $total_quantidade = $valores['quantidade'];

        // Nome da view
        $view_name = "paginas.relatorios.download.fornecedor-$tipo";
        $logo_path = LogoCanalVendaEnum::LOGOS[$canal_venda->id];

        // Variavel para controlar como puxar os assets
        $inline_pdf = true;

        // Titulo do relatorio
        $title = "Relatório de ";
        $title .= ($somente_autenticados == 'true') ? "ingressos autenticados" : "vendas";

        // Variaveis para as views
        $variavies = compact(
            'pp_start',
            'pp_end',
            'fornecedores',
            'total_vendido',
            'total_net',
            'total_quantidade',
            'canal_venda',
            'somente_autenticados',
            'logo_path',
            'title',
            'inline_pdf'
        );

        // PDF com as vendas
        if($inline_pdf && $tipo == "pdf") {
            return PDF::loadView($view_name, $variavies)
                ->inline("{$title} - {$canal_venda->nome}.pdf");
        }

        // XLS com as vendas
        if($tipo == "xls") {
            return (new RelatorioVendasTerminaisExport($view_name, $variavies))
                ->download("{$title} - {$canal_venda->nome}.xlsx");
        }

        return view($view_name, $variavies);
    }
}
