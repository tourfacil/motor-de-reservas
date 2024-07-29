<?php namespace App\Http\Controllers\Relatorios;

use App\Enum\LogoCanalVendaEnum;
use App\Exports\Terminais\RelatorioVendasTerminaisExport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PDF;
use TourFacil\Core\Models\Fornecedor;
use TourFacil\Core\Services\Relatorios\ReservasAutenticadaService;

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
        // Canal de venda na sessao do usuario
        $canal_venda = canalSession()->getCanal();

        // Fornecedor id na URL
        $fornecedor_id = request()->get('fornecedor', 1);

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

        // Lista de fornecedores
        $fornecedores = Fornecedor::select(['id', 'nome_fantasia'])->orderBy('nome_fantasia')->get();

        // Fornecedor atual
        $fornecedor = $fornecedores->first(function ($fornecedor) use ($fornecedor_id) {
            return ($fornecedor->id == $fornecedor_id);
        });

        // Recupera as reservas do fornecedor
        $reservas = ReservasAutenticadaService::relatorioAutenticadoFornecedor($fornecedor->id, $pp_start, $pp_end, $canal_venda->id, $servicos_url);

        // Soma dos valores
        $total_vendido = $reservas->sum('valor_total');
        $total_net = $reservas->sum('valor_net');
        $total_quantidade = $reservas->sum('quantidade');

        return view('paginas.relatorios.autenticados',compact(
            'pp_start',
            'pp_end',
            'reservas',
            'fornecedor',
            'total_vendido',
            'total_net',
            'total_quantidade',
            'fornecedores',
            'canal_venda',
            'servicos_url'
        ));
    }

    /**
     * Download do PDF com o relatorio
     *
     * @return mixed
     */
    public function download()
    {
        // Canal de venda na sessao do usuario
        $canal_venda = canalSession()->getCanal();

        // Fornecedor id na URL
        $fornecedor_id = request()->get('fornecedor', 1);

        // Servicos na URL
        $servicos_url = request()->get('servicos');

        // Tipo de download
        $tipo = request()->get('type', 'pdf');

        // Datas para pesquisa
        $pp_start = Carbon::createFromFormat("d-m-Y", request()->get('inicio'))->startOfDay();
        $pp_end = Carbon::createFromFormat("d-m-Y", request()->get('final'))->endOfDay();

        // Recupera os dados do fornecedor
        $fornecedor = Fornecedor::find($fornecedor_id);

        // Reservas autenticadas
        $reservas = ReservasAutenticadaService::relatorioAutenticadoFornecedor($fornecedor->id, $pp_start, $pp_end, $canal_venda->id, $servicos_url, [
            'quantidadeReserva.variacaoServico',
            'pedido.cliente'
        ]);

        // Soma dos valores
        $total_vendido = $reservas->sum('valor_total');
        $total_net_vendido = $reservas->sum('valor_net');
        $total_quantidade = $reservas->sum('quantidade');

        // Nome da view
        $view_name = "paginas.relatorios.download.autenticados-$tipo";
        $logo_path = LogoCanalVendaEnum::LOGOS[$canal_venda->id];

        // Variavel para controlar como puxa os assets
        $inline_pdf = true;

        // Variaveis para as views
        $variavies = compact(
            'inline_pdf',
            'pp_start',
            'pp_end',
            'fornecedor',
            'reservas',
            'canal_venda',
            'total_quantidade',
            'total_vendido',
            'total_net_vendido',
            'logo_path'
        );

        // PDF com as vendas
        if($inline_pdf && $tipo == "pdf") {
            return PDF::loadView($view_name, $variavies)
                ->inline("Autenticados {$fornecedor->nome_fantasia} - {$canal_venda->nome}.pdf");
        }

        // XLS com as vendas
        if($tipo == "xls") {
            return (new RelatorioVendasTerminaisExport($view_name, $variavies))
                ->download("Autenticados {$fornecedor->nome_fantasia} - {$canal_venda->nome}.xlsx");
        }

        return view($view_name, $variavies);
    }
}
