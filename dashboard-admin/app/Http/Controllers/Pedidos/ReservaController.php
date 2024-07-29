<?php namespace App\Http\Controllers\Pedidos;

use App\Enum\LogoCanalVendaEnum;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PDF;
use TourFacil\Core\Enum\AgendaEnum;
use TourFacil\Core\Enum\CanaisVendaEnum;
use TourFacil\Core\Enum\ComissaoStatus;
use TourFacil\Core\Enum\IntegracaoEnum;
use TourFacil\Core\Enum\MotivosReservaEnum;
use TourFacil\Core\Enum\StatusPagamentoEnum;
use TourFacil\Core\Enum\StatusPedidoEnum;
use TourFacil\Core\Enum\StatusReservaEnum;
use TourFacil\Core\Models\Afiliado;
use TourFacil\Core\Models\AgendaDataServico;
use TourFacil\Core\Models\DebitoComissaoTerminal;
use TourFacil\Core\Models\ExceedReservaPedido;
use TourFacil\Core\Models\HistoricoReservaPedido;
use TourFacil\Core\Models\Pedido;
use TourFacil\Core\Models\ReservaPedido;
use TourFacil\Core\Models\SnowlandReservaPedido;
use TourFacil\Core\Models\Vendedor;
use TourFacil\Core\Services\AgendaService;
use TourFacil\Core\Services\Exceedpark\ExceedService;
use TourFacil\Core\Services\Integracao\PWI\PWIService;
use TourFacil\Core\Services\Snowland\SnowlandService;

/**
 * Class ReservaController
 * @package App\Http\Controllers\Pedidos
 */
class ReservaController extends Controller
{
    /**
     * View para detalhes da reserva
     *
     * @param $voucher_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view($voucher_id)
    {
        // Recupera a reserva
        $reserva = ReservaPedido::with([
            'pedido',
            'validacao',
            'fornecedor',
            'servico.categoria',
            'agendaDataServico',
            'historicoReservaPedido.user',
            'comissaoTerminal.terminal',
            'dadoClienteReservaPedido.variacaoServico',
            'campoAdicionalReservaPedido.campoAdicionalServico',
            'quantidadeReserva' => function ($q) {
                return $q->withTrashed()->with('variacaoServico')->orderBy('deleted_at');
            }
        ])->where('voucher', $voucher_id)->first();

        // variavel para controlar a remocao dos acompanhantes
        $allow_remocao = $reserva->quantidade > 1;

        // Quantidade de campos adicionais do servico
        $qtd_campos_adicionais = $reserva->campoAdicionalReservaPedido->count();

        // Numero de colunas no layout
        $colunas_adicionais = ($qtd_campos_adicionais >= 4) ? 4 : [0 => 0, 1 => 12, 2 => 6, 3 => 4][$qtd_campos_adicionais];

        // Motivos para cancelamento
        $motivos_cancelamento = MotivosReservaEnum::motivosCancelamento();

        // reserva cancelada
        $enum_cancelada = StatusReservaEnum::CANCELADO;

        // reserva aguardando pagamento
        $enum_aguardando = StatusReservaEnum::AGUARDANDO;

        // reserva faltando informacoes acompanhantes ou adicionais
        $enum_finalizar = StatusReservaEnum::FINALIZAR;

        // reserva já utilizada
        $enum_utilizada = StatusReservaEnum::UTILIZADO;

        // Desabilita todos os campos na reserva
        $disable_fields = in_array($reserva->status, [
            $enum_cancelada, $enum_aguardando, $enum_utilizada
        ]);

        $afiliados = Afiliado::all();
        $vendedores = Vendedor::all();

        return view('paginas.pedidos.reserva.detalhes-reserva', compact(
            'reserva',
            'allow_remocao',
            'qtd_campos_adicionais',
            'colunas_adicionais',
            'motivos_cancelamento',
            'enum_cancelada',
            'enum_aguardando',
            'enum_finalizar',
            'enum_utilizada',
            'disable_fields',
            'afiliados',
            'vendedores'
        ));
    }

    /**
     * Imprime o voucher do cliente
     *
     * @param $voucher_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function print($voucher_id)
    {
        // Recupera a reserva
        $reserva = ReservaPedido::with([
            'pedido.canalVenda',
            'pedido.cliente',
            'fornecedor',
            'servico.categoria',
            'agendaDataServico',
            'quantidadeReserva.variacaoServico',
        ])->where('voucher', $voucher_id)->first();

        $inline_pdf = true;

        // Logo do canal de venda
        $logo_canal = LogoCanalVendaEnum::LOGOS[$reserva->pedido->canal_venda_id];

        // Caso seja para mostrar o PDF
        if($inline_pdf) {

            // PDF com o voucher
            $pdf = PDF::loadView('paginas.pedidos.vouchers.voucher', [
                'reserva' => $reserva,
                'inline_pdf' => $inline_pdf,
                'cliente' => $reserva->pedido->cliente,
                'logo_canal' => $logo_canal,
                'canal_venda' => $reserva->pedido->canalVenda
            ]);

            $pdf->setOption('zoom', 1.8);

            return $pdf->inline("{$reserva->servico->nome} #{$reserva->voucher}.pdf");
        }

        return view('paginas.pedidos.vouchers.voucher', [
            'reserva' => $reserva,
            'inline_pdf' => $inline_pdf,
            'cliente' => $reserva->pedido->cliente,
            'logo_canal' => $logo_canal,
            'canal_venda' => $reserva->pedido->canalVenda
        ]);
    }

    /**
     * JSON com as datas disponiveis para trocar a agenda da reserva
     *
     * @param $reserva_id
     * @return array
     */
    public function calendarioReserva($reserva_id)
    {
        return AgendaService::calendarioReservaAdmin($reserva_id);
    }

    /**
     * Altera a data de utilização de uma reserva
     *
     * @param Request $request
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateDataAgenda(Request $request)
    {
        // Valida o request
        $this->validate($request, ['data_agenda_id' => 'required|integer', 'reserva_id' => 'required|integer']);

        // Recupera os dados da reserva
        $reserva = ReservaPedido::with('agendaDataServico')->find($request->get('reserva_id'));

        // Recupera a nova agenda
        $nova_agenda = AgendaDataServico::where('status', AgendaEnum::ATIVO)->find($request->get('data_agenda_id'));

        // Verifica se tem disponibilidade para o dia
        if($nova_agenda->disponivel >= $reserva->bloqueio_consumido) {

            // Retorna a disponibilidade para a agenda anterior
            $reserva->agendaDataServico->update([
                'disponivel' => ($reserva->agendaDataServico->disponivel + $reserva->bloqueio_consumido),
                'consumido' => ($reserva->agendaDataServico->consumido - $reserva->bloqueio_consumido),
            ]);

            // Atualiza a data de utilização na reserva
            $reserva->update(['agenda_data_servico_id' => $nova_agenda->id]);

            // Disponibilidade restante da nova data
            $restante_nova_agenda = ($nova_agenda->disponivel - $reserva->bloqueio_consumido);

            // Consome o bloqueio na nova data
            $nova_agenda->update([
                'disponivel' => $restante_nova_agenda,
                'consumido' => ($nova_agenda->consumido + $reserva->bloqueio_consumido),
                'status' => ($restante_nova_agenda >= 1) ? $nova_agenda->status : AgendaEnum::INDISPONIVEL
            ]);

            // Salva o historico de alteracao da reserva
            HistoricoReservaPedido::create([
                "pedido_id" => $reserva->pedido_id,
                "reserva_pedido_id" => $reserva->id,
                "motivo" => MotivosReservaEnum::ALTERACAO_DATA,
                "user_id" => auth()->user()->id
            ]);

            return $this->autoResponseJson(true, "Data de utilização alterada", "A data de utilização da reserva foi alterada com sucesso!");
        }

        return $this->autoResponseJson(false, "A nova data não possui disponibilidade", "A nova data selecionada não possui disponibilidade suficiente!");
    }

    /**
     * Cancela a reserva
     *
     * @param Request $request
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    public function delete(Request $request)
    {
        // Valida o request
        $this->validate($request, ['reserva_id' => 'required|integer', 'motivo' => 'required']);

        // Array com as reservas canceladas e ativas
        $canceladas = ['quantidade' => 0, 'valor' => 0]; $ativas = ['quantidade' => 0, 'valor' => 0];

        // Recupera os dados da reserva
        $reserva = ReservaPedido::with([
            'servico',
            'comissaoTerminal',
            'agendaDataServico',
        ])->find($request->get('reserva_id'));

        // Faz o cancelamento na PWI caso seja necessário
        if($reserva->integracaoPWI != null) {
            if(!PWIService::cancelarIntegracao($reserva)) {
                return $this->autoResponseJson(false, 'Erro PWI', "A reserva não foi cancelada devido a erro na resposta da PWI.");
            }
        }

        // Verifica se a reserva já não está cancelada
        if($reserva->status != StatusReservaEnum::CANCELADO) {

            // Retorna a disponibilidade para a agenda
            $reserva->agendaDataServico->update([
                'disponivel' => ($reserva->agendaDataServico->disponivel + $reserva->bloqueio_consumido),
                'consumido' => ($reserva->agendaDataServico->consumido - $reserva->bloqueio_consumido),
            ]);

            // Cancela a reserva
            $reserva->update(['status' => StatusReservaEnum::CANCELADO]);

            // Recupera o pedido com as reservas
            $pedido = Pedido::with('reservas')->find($reserva->pedido_id);

            // Separa as reservas ativas e canceladas
            foreach ($pedido->reservas as $reserva_pedido) {
                if($reserva_pedido->status === StatusReservaEnum::CANCELADO) {
                    $canceladas['quantidade']++;
                    $canceladas['valor'] += $reserva_pedido->valor_total;
                } else {
                    $ativas['quantidade']++;
                    $ativas['valor'] += $reserva_pedido->valor_total;
                }
            }

            // Caso ainda tenha reservas ativas
            if($ativas['quantidade'] > 0) {
                // Retira o valor da reserva atual do valor do pedido
                $pedido->update(['valor_total' => ($pedido->valor_total - $reserva->valor_total)]);
            } else {
                // Cancela o pedido e atualiza o valor total
                $pedido->update([
                    'valor_total' => $canceladas['valor'],
                    'status' => StatusPedidoEnum::CANCELADO,
                    'status_pagamento' => StatusPagamentoEnum::ESTORNADO,
                ]);
            }

            // Verifica se a reserva tinha integracao
//            if($reserva->servico->integracao == IntegracaoEnum::SNOWLAND) {
//                // Cancela o voucher snowland
//                $voucher = SnowlandReservaPedido::where('reserva_pedido_id', $reserva->id)->first();
//                if(is_object($voucher)) {
//                    (new SnowlandService($reserva))->cancelarVoucher($voucher);
//                }
//            }

            // Verifica se a reserva tinha integracao
//            if($reserva->servico->integracao == IntegracaoEnum::EXCEED_PARK) {
//                // Cancela o voucher do parque Exceed
//                $voucher = ExceedReservaPedido::where('reserva_pedido_id', $reserva->id)->first();
//                if(is_object($voucher)) {
//                    (new ExceedService($reserva))->cancelarVoucher($voucher);
//                }
//            }

            // Caso tenha comissao do terminal
            if(is_object($reserva->comissaoTerminal)) {

                // Calcula o novo valor da comissao
                $comissao = ($reserva->valor_total / 100 * $reserva->servico->comissao_afiliado);

                // Caso a comissao ainda nao foi paga
                if($reserva->comissaoTerminal->status == ComissaoStatus::AGUARDANDO) {
                    // Cancela a comissao do terminal
                    $reserva->comissaoTerminal->update(['status' => ComissaoStatus::CANCELADO]);
                } else {
                    // Lanca um debito para o terminal
                    DebitoComissaoTerminal::create([
                        "terminal_id" => $reserva->comissaoTerminal->terminal_id,
                        "comissao_terminal_id" => $reserva->comissaoTerminal->id,
                        "valor" => $comissao,
                        "status" => ComissaoStatus::AGUARDANDO,
                    ]);
                }
            }

            // Salva o historico de alteracao da reserva
            HistoricoReservaPedido::create([
                "pedido_id" => $pedido->id,
                "reserva_pedido_id" => $reserva->id,
                "motivo" => $request->get('motivo'),
                "user_id" => auth()->user()->id,
                "valor_fornecedor" => $reserva->valor_net,
                "valor" => $reserva->valor_total,
            ]);

            return $this->autoResponseJson(true, 'Reserva cancelada', "A reserva #{$reserva->id} foi cancelada com sucesso!");
        }

        return $this->autoResponseJson(false, 'Reserva já cancelada', "A reserva #{$reserva->id} já está cancelada!");
    }
}
