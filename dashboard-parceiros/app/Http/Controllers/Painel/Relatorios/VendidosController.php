<?php namespace App\Http\Controllers\Painel\Relatorios;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use PDF;
use TourFacil\Core\Enum\StatusReservaEnum;
use TourFacil\Core\Models\Fornecedor;
use TourFacil\Core\Services\FornecedorService;
use TourFacil\Core\Services\Relatorios\IngressosVendidoService;
use TourFacil\Core\Services\Relatorios\ReservasAutenticadaService;

/**
 * Class VendidosController
 * @package App\Http\Controllers\Painel\Relatorios
 */
class VendidosController extends Controller
{
    /**
     * Lista dos ingressos vendidos
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
        $reservas = FornecedorService::reservasVendidasFornecedorUtilizacao($fornecedor_id, $pp_start, $pp_end, $servicos_url);

        // Servicos do fornecedor
        $servicos = FornecedorService::getServicosFornecedor($fornecedor_id);

        // Soma dos valores
        $vendas = 0; $tarifa_net = 0; $quantidade = 0;
        foreach ($reservas as $reserva) {
            if($reserva->status == StatusReservaEnum::ATIVA || $reserva->status == StatusReservaEnum::UTILIZADO) {
                $tarifa_net += $reserva->valor_net;
                $quantidade += $reserva->quantidade;
                $vendas++;
            }
        }

        return view('paginas.relatorios.vendidos',compact(
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
        $reservas = IngressosVendidoService::relatorioVendidoFornecedor($fornecedor->id, $pp_start, $pp_end, $canal_venda->id, $servicos_url, [
            'quantidadeReserva.variacaoServico',
            'pedido.cliente',
        ]);

        // Soma os valores totais das reservas
        $valores_reservas = $this->somarValoresVendidos($reservas);

        // Soma dos valores
        $total_vendido = $valores_reservas['vendido'];
        $total_net_vendido = $valores_reservas['net'];
        $total_quantidade = $valores_reservas['quantidade'];

        // Nome da view
        $view_name = "paginas.relatorios.download.vendidos-$tipo";
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
                ->inline("Vendidos {$fornecedor->nome_fantasia} - {$canal_venda->nome}.pdf");
        }

        // XLS com as vendas
        if($tipo == "xls") {
            return (new RelatorioVendasTerminaisExport($view_name, $variavies))
                ->download("Vendidos {$fornecedor->nome_fantasia} - {$canal_venda->nome}.xlsx");
        }

        return view($view_name, $variavies);
    }

    /**
     * Soma os valores somente das reservas validas
     *
     * @param $reservas
     * @return array
     */
    public function somarValoresVendidos($reservas) {

        // Array com as variaveis do resultado
        $return = ['vendido' => 0, 'net' => 0, 'quantidade' => 0];

        // Soma os valores somente das reservas validas
        foreach ($reservas as $reserva) {
            if(in_array($reserva->status, StatusReservaEnum::RESERVAS_VALIDAS)) {
                $return['vendido'] += $reserva->valor_total;
                $return['net'] += $reserva->valor_net;
                $return['quantidade'] += $reserva->quantidade;
            }
        }

        return $return;
    }
}
