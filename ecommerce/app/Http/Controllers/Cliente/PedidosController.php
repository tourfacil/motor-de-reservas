<?php namespace App\Http\Controllers\Cliente;

use App\Jobs\NovaVendaJob;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PDF;
use TourFacil\Core\Enum\MetodoPagamentoEnum;
use TourFacil\Core\Enum\StatusFinalizacaoReservaEnum;
use TourFacil\Core\Enum\StatusReservaEnum;
use TourFacil\Core\Models\Pedido;
use TourFacil\Core\Models\ReservaPedido;
use TourFacil\Core\Models\DadoClienteReservaPedido;
use TourFacil\Core\Models\CampoAdicionalReservaPedido;
use TourFacil\Core\Services\FinalizacaoService;

/**
 * Class PedidosController
 * @package App\Http\Controllers\Cliente
 */
class PedidosController extends Controller
{
    /**
     * Listagem dos pedidos realizados pelo cliente
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        // Dados do cliente logado
        $cliente = auth()->user();

        // Destino na sessao
        $destino = $this->getDestinoSession()['destino'];

        // Recupera os pedidos do cliente
        $pedidos = Pedido::where('cliente_id', $cliente->id)->latest()->get();

        return view('paginas.cliente.pedidos', compact(
            'destino',
            'pedidos'
        ));
    }

    /**
     * Detalhes do pedido realizado pelo cliente
     *
     * @param $codigo_pedido
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view($codigo_pedido)
    {

        // Dados do cliente logado
        $cliente = auth()->user();

        // Destino na sessao
        $destino = $this->getDestinoSession()['destino'];

        // Recupera os detalhes do pedido
        $pedido = Pedido::with([
            'transacaoPedido',
            'reservas.servico',
            'reservas.agendaDataServico',
            'reservas.quantidadeReserva',
            'reservas.quantidadeReserva.variacaoServico'
        ])->where([
            'cliente_id' => $cliente->id,
            'codigo' => $codigo_pedido
        ])->first();

        // Caso nao encontre o pedido
        if(is_null($pedido)) {
            return redirect()->route('ecommerce.cliente.pedidos.index');
        }

        // Enum cartao de credito
        $e_cartao = MetodoPagamentoEnum::CARTAO_CREDITO;

        // Enum reserva ativa
        $e_reserva_ativa = StatusReservaEnum::ATIVA;

        return view('paginas.cliente.detalhe-pedido', compact(
            'destino',
            'pedido',
            'cliente',
            'e_cartao',
            'e_reserva_ativa'
        ));
    }

    /**
     * Impressao do voucher
     *
     * @param $voucher
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function print($voucher)
    {
        // Dados do cliente logado
        $cliente = auth()->user();

        // Recupera a reserva
        $reserva = ReservaPedido::with([
            'pedido.cliente',
            'fornecedor',
            'servico',
            'agendaDataServico',
            'quantidadeReserva.variacaoServico',
        ])->whereHas('pedido', function ($q) use ($cliente) {
            return $q->where('cliente_id', $cliente->id);
        })->where('voucher', $voucher)->first();

        // Caso nao encontre a reserva ou nao esteja ativa
        if(is_null($reserva) || $reserva->status != StatusReservaEnum::ATIVA) {
            return redirect()->route('ecommerce.cliente.pedidos.index');
        }

        $inline_pdf = true;

        // Caso seja para mostrar o PDF
        if($inline_pdf) {

            // PDF com o voucher
            $pdf = PDF::loadView('voucher.voucher', [
                'reserva' => $reserva,
                'inline_pdf' => $inline_pdf,
                'cliente' => $reserva->pedido->cliente,
            ]);

            $pdf->setOption('zoom', 1.7);

            return $pdf->inline("{$reserva->servico->nome} #{$reserva->voucher}.pdf");
        }

        return view('voucher.voucher', [
            'reserva' => $reserva,
            'inline_pdf' => $inline_pdf,
            'cliente' => $reserva->pedido->cliente,
        ]);
    }

    public function informacaoFinalizacao(Request $request) {
        $reserva_id = $request->get('reserva_id');

        $reserva = ReservaPedido::where('id', $reserva_id)
                                ->with(['servico', 'servico.camposAdicionaisAtivos', 'quantidadeReserva', 'quantidadeReserva.variacaoServico'])
                                ->get()
                                ->first();
        $dados = [
            'reserva' => $reserva,
            'quantidades' => $reserva->quantidadeReserva,
            'servico' => $reserva->servico,
        ];

        return response($dados, 200);
    }

    // Guarda as informações vindas do painel do cliente
    public function informacaoFinalizacaoStore(Request $request) {

        // Recupera os dados
        $dados = $request->all();

        // Busca os acompanhantes
        $acompanhantes = $dados['acompanhantes'];
        $campos_adicionais = $dados['campos_adicionais'];

        // Lista de acompanhantes e campos criados
        $acompanhantes_criados = [];
        $campos_adicionais_criados = [];

        // Guarda o reserva id para verificar as questões de finalização
        $reserva_id = null;

        // Percorre os acompanhantes e cria eles no banco de dados
        foreach($acompanhantes as $acompanhante) {
            $acompanhantes_criados[] = DadoClienteReservaPedido::create($acompanhante);
            $reserva_id = $acompanhante['reserva_pedido_id'];
        }

        // Percorre os campos adicionais e cria eles no banco de dados
        foreach($campos_adicionais as $campo_adicional) {
            $campos_adicionais_criados[] = CampoAdicionalReservaPedido::create($campo_adicional);
            $reserva_id = $campo_adicional['reserva_pedido_id'];
        }

        // Busca a reserva e o pedido para verificar a questão do envio de e-mails na finalização
        $reserva = ReservaPedido::find($reserva_id);

        $reserva->update(['finalizada' => StatusFinalizacaoReservaEnum::FINALIZADA]);

        $pedido = $reserva->pedido;

        // Verifica se o pedido ja esta finalizado
        // Caso não esteja, ele não envia os e-mails
        // Caso esteja, ele envia os e-mails para cliente e fornecedor
        if(FinalizacaoService::isPedidoFinalizado($pedido)) {
            // Dispara o job de nova compra
            NovaVendaJob::dispatch($pedido);
        }

        // Monta um array de respostas para a requisição
        $response = [
            'acompanhantes' => $acompanhantes_criados,
            'campos_adicionais_criados' => $campos_adicionais_criados,
        ];

        // Responde a requisição
        return response($response, 200);
    }
}
