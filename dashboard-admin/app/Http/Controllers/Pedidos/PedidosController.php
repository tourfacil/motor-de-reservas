<?php namespace App\Http\Controllers\Pedidos;

use App\Enum\UserLevelEnum;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use TourFacil\Core\Enum\MetodoPagamentoEnum;
use TourFacil\Core\Enum\StatusPagamentoEnum;
use TourFacil\Core\Enum\StatusPedidoEnum;
use TourFacil\Core\Enum\StatusPixEnum;
use TourFacil\Core\Enum\StatusReservaEnum;
use TourFacil\Core\Models\Afiliado;
use TourFacil\Core\Models\Pedido;
use TourFacil\Core\Models\ReservaPedido;
use TourFacil\Core\Services\AdminEcommerceAPI\AdminEcommerceAPI;
use TourFacil\Core\Services\FinalizacaoService;
use TourFacil\Core\Services\Pagamento\PixService;

/**
 * Class PedidosController
 * @package App\Http\Controllers\Pedidos
 */
class PedidosController extends Controller
{
    /**
     * Listagem dos pedidos
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        // Canal de venda na sessao do usuario
        $canal_venda = canalSession()->getCanal();

        // Datas para pesquisa
        $periodo_pesquisa = periodoPesquisa();

        // Preset do filtro selecionado
        $filtro_selecionado = request()->get('periodo', 'ultimos_7');

        // Data de inicio do filtro
        $pp_start = request()->get('inicio', $periodo_pesquisa[$filtro_selecionado]['start'] ?? null);

        // Data final do filtro
        $pp_end = request()->get('final', $periodo_pesquisa[$filtro_selecionado]['end'] ?? null);

        // Para as datas que estão na URl
        if(! is_object($pp_start) || ! is_object($pp_end)) {
            // Converte as datas para a Carbon
            $pp_start = Carbon::createFromFormat('d-m-Y H:i:s', $pp_start . " 00:00:00");
            // Converte as datas para a Carbon
            $pp_end = Carbon::createFromFormat('d-m-Y H:i:s', $pp_end . " 23:59:59");
        }

        // Recupera as reservas de acordo com a data e canal de venda
        $dados_reservas = $this->reservasAdmin($canal_venda, $pp_start, $pp_end);

        return view('paginas.pedidos.pedidos', compact(
            'dados_reservas',
            'canal_venda',
            'periodo_pesquisa',
            'pp_start',
            'pp_end',
            'filtro_selecionado'
        ));
    }

    /**
     * Dados para a pagina index dos pedidos
     *
     * @param $canal_venda
     * @param $pp_start
     * @param $pp_end
     * @return array
     */
    private function reservasAdmin($canal_venda, $pp_start, $pp_end)
    {
        $return = [];

        $afiliado = Afiliado::find(auth()->user()->afiliado_id);

        // Reservas do canal de venda
        $return['reservas'] = ReservaPedido::with('pedido.cliente')
            ->whereHas('pedido', function ($q) use ($canal_venda) {
                return $q->where('canal_venda_id', $canal_venda->id);
            })->whereBetween('created_at', [$pp_start, $pp_end]);

            if($afiliado && auth()->user()->level == UserLevelEnum::VENDEDOR) {
                $return['reservas']->where('afiliado_id', $afiliado->id);
            }

        $return['reservas'] = $return['reservas']->latest()->get();

        // Total vendido somente reservas ATIVAS, PENDENTES DE FINALIZAR ou UTILIZADAS
        $return['valor_vendido'] = $return['reservas']->sum(function ($reserva) {
            return (in_array($reserva->status, [
                StatusReservaEnum::ATIVA, StatusReservaEnum::FINALIZAR, StatusReservaEnum::UTILIZADO
            ])) ? $reserva->valor_total : 0;
        });

        // Quantidade de ingressos vendidos ATIVAS, PENDENTES DE FINALIZAR ou UTILIZADAS
        $return['valor_pendente'] = $return['reservas']->sum(function ($reserva) {
            return (in_array($reserva->status, [
                StatusReservaEnum::AGUARDANDO
            ])) ? $reserva->valor_total : 0;
        });

        // Total estornado reservas ATIVAS ou PENDENTES DE FINALIZAR
        $return['valor_estornado'] = $return['reservas']->sum(function ($reserva) {
            return (in_array($reserva->status, [
                StatusReservaEnum::CANCELADO
            ])) ? $reserva->valor_total : 0;
        });

        return $return;
    }

    /**
     * Detalhes do pedido
     *
     * @param $codigo_pedido
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view($codigo_pedido)
    {
        // Recupera os detalhes do pedido
        $pedido = Pedido::with([
            'cliente',
            'canalVenda',
            'transacaoPedido',
            'reservas.servico',
            'historicoPedido.user',
            'comissaoTerminal.terminal',
            'reservas.agendaDataServico',
            'historicoPedido.reservaPedido',
            'comissaoTerminal.reservaPedido',
        ])->where('codigo', $codigo_pedido)->first();

        return view('paginas.pedidos.detalhes-pedido', compact(
            'pedido'
        ));
    }

    public function pixPendentes(Request $request) {

        $pedidos = Pedido::where('status_pagamento', StatusPagamentoEnum::PENDENTE)
                         ->where('metodo_pagamento', MetodoPagamentoEnum::PIX)
                         ->where('status', StatusPedidoEnum::AGUARDANDO)
                         ->get();

        $pendentes = 0;
        $pagos = 0;
        $expirados = 0;

        foreach($pedidos as $pedido) {

            $status = PixService::getAndUpdateSituacaoPix($pedido);

            if($status == StatusPixEnum::PAGO) {
                $pagos++;

                // Verifica se o pedido ja esta finalizado
                // Caso não esteja, ele não envia os e-mails
                // Caso esteja, ele envia os e-mails para cliente e fornecedor
                // Caso for encontrada uma reserva não finalizada ele marca ela com uma FLAG
                if(FinalizacaoService::isPedidoFinalizado($pedido)) {
                    AdminEcommerceAPI::solicitarEnvioDeEmailAposVendaInterna($pedido);
                }
            }

            if($status == StatusPixEnum::EXPIRADO) {
                $expirados++;
            }

            if($status == StatusPixEnum::PENDENTE) {
                $pendentes++;
            }
        }

        $dados = [
            'pendentes' => $pendentes,
            'pagos' => $pagos,
            'expirados' => $expirados,
        ];

        return response($dados, 200);
    }

    /**
     * Método que retorna as informações de finalização da reserva
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Foundation\Application|\Illuminate\Http\Response
     */
    public function informacaoFinalizacao(Request $request) {

        $dados = FinalizacaoService::informacaoFinalizacao($request->get('reserva_id'));

        return response($dados, 200);
    }

    /**
     * Registra a informação de finalização da reserva e manda os e-mails caso necessário
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Foundation\Application|\Illuminate\Http\Response
     */
    public function informacaoFinalizacaoStore(Request $request) {
        $dados = $request->all();

        // Finaliza a reserva
        $reserva = FinalizacaoService::finalizarReserva($dados)['reserva'];

        // Recupera o pedido da reserva
        $pedido = $reserva->pedido;

        // Verifica se o pedido ja esta finalizado
        // Caso não esteja, ele não envia os e-mails
        // Caso esteja, ele envia os e-mails para cliente e fornecedor
        if(FinalizacaoService::isPedidoFinalizado($pedido)) {
            AdminEcommerceAPI::solicitarEnvioDeEmailAposVendaInterna($pedido);
        }

        return response(['status' => true], 200);
    }
}
