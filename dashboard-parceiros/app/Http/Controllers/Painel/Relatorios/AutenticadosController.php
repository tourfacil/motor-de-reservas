<?php namespace App\Http\Controllers\Painel\Relatorios;

use App\Exports\ExcelExport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use TourFacil\Core\Models\Fornecedor;
use TourFacil\Core\Services\FornecedorService;

/**
 * Class AutenticadosController
 * @package App\Http\Controllers\Relatorios
 */
class AutenticadosController extends Controller
{
    /**
     * Relatorio das reservas autenticadas
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        // Fornecedor ID do usuario logado
        $fornecedor_id = auth()->user()->fornecedor_id;

        // Mes de pesquisa
        $mes_pesquisa = request()->get('periodo', date('m-Y'));

        // Servicos na URL
        $servicos_url = request()->get('servicos');

        // Datas para pesquisa
        if($mes_pesquisa == "custom") {
            $pp_start = Carbon::createFromFormat("d-m-Y", request()->get('inicio'))->startOfDay();
            $pp_end = Carbon::createFromFormat("d-m-Y", request()->get('final'))->endOfDay();
        } else {
            $pp_start = Carbon::createFromFormat("d-m-Y", "01-$mes_pesquisa")->startOfDay();
            $pp_end = $pp_start->copy()->endOfMonth();
        }

        // Recupera as reservas do fornecedor
        $reservas = FornecedorService::reservasAutenticadasFornecedor($fornecedor_id, $pp_start, $pp_end, $servicos_url);

        // Servicos do fornecedor
        $servicos = FornecedorService::getServicosFornecedor($fornecedor_id);

        // Soma dos valores
        $vendas = 0; $tarifa_net = 0; $quantidade = 0;
        foreach ($reservas as $reserva) {
            $tarifa_net += $reserva->valor_net;
            $quantidade += $reserva->quantidade;
            $vendas++;
        }

        return view('paginas.relatorios.autenticados', compact(
            'pp_start',
            'pp_end',
            'reservas',
            'vendas',
            'tarifa_net',
            'quantidade',
            'servicos_url',
            'servicos'
        ));
    }

    /**
     * Download do PDF com o relatorio
     *
     * @return mixed
     */
    public function download()
    {
        // Fornecedor ID do usuario logado
        $fornecedor_id = auth()->user()->fornecedor_id;

        // Servicos na URL
        $servicos_url = request()->get('servicos');

        // Tipo de download
        $tipo = request()->get('type', 'pdf');

        // Datas para pesquisa
        $pp_start = Carbon::createFromFormat("d-m-Y", request()->get('inicio'))->startOfDay();
        $pp_end = Carbon::createFromFormat("d-m-Y", request()->get('final'))->endOfDay();

        // Dados do fornecedor
        $fornecedor = Fornecedor::find($fornecedor_id);

        // Reservas autenticadas
        $reservas = FornecedorService::reservasAutenticadasFornecedor($fornecedor_id, $pp_start, $pp_end, $servicos_url, [
            'quantidadeReserva.variacaoServico',
            'pedido.cliente'
        ]);

        // Soma dos valores
        $vendas = 0; $tarifa_net = 0; $quantidade = 0;
        foreach ($reservas as $reserva) {
            $tarifa_net += $reserva->valor_net;
            $quantidade += $reserva->quantidade;
            $vendas++;
        }

        // Nome da view
        $view_name = "paginas.relatorios.download.autenticados-$tipo";

        // Variavel para controlar como puxa os assets
        $inline_pdf = true;

        // Variaveis para as views
        $variavies = compact(
            'inline_pdf',
            'pp_start',
            'pp_end',
            'fornecedor',
            'reservas',
            'quantidade',
            'vendas',
            'tarifa_net'
        );

        // PDF com as vendas
        if($inline_pdf && $tipo == "pdf") {
            return \PDF::loadView($view_name, $variavies)
                ->inline("Ingressos autenticados - Grupo Portal Gramado.pdf");
        }

        // XLS com as vendas
        if($tipo == "xls") {
            return (new ExcelExport($view_name, $variavies))
                ->download("Ingressos autenticados - Grupo Portal Gramado.xlsx");
        }

        return view($view_name, $variavies);
    }
}
