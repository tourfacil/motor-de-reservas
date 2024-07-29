<?php namespace App\Http\Controllers\Pedidos;

use App\Http\Requests\Reserva\UpdateAcompanhanteRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use TourFacil\Core\Enum\ComissaoStatus;
use TourFacil\Core\Enum\MotivosReservaEnum;
use TourFacil\Core\Enum\VariacaoServicoEnum;
use TourFacil\Core\Models\AgendaDataServico;
use TourFacil\Core\Models\DadoClienteReservaPedido;
use TourFacil\Core\Models\DebitoComissaoTerminal;
use TourFacil\Core\Models\HistoricoReservaPedido;

/**
 * Class AcompanhanteController
 * @package App\Http\Controllers\Pedidos
 */
class AcompanhanteController extends Controller
{
    /**
     * Atualiza os dados do acompanhante
     *
     * @param Request $request
     * @return array
     */
    public function update(UpdateAcompanhanteRequest $request)
    {
        // Recupera os dados do acompanhante
        $acompanhante = DadoClienteReservaPedido::find($request->get('acompanhante_id'));

        // Atualiza os dados do acompanhante
        $update = $acompanhante->update($request->all());

        // Salvar a alteracao na reserva

        if($update) {
            return $this->autoResponseJson(true, 'Acompanhante atualizado', 'Os dados do acompanhante foram salvos com sucesso!');
        }

        return $this->autoResponseJson(false, 'Acompanhante não atualizado', 'Não foi possível atualizar o acompanhante, tente novamente!');
    }

    /**
     * Remove um acompanhante da reserva
     *
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function delete(Request $request)
    {
        // Recupera os dados do acompanhante
        $acompanhante = DadoClienteReservaPedido::with([
            'variacaoServico',
            'reservaPedido.pedido',
            'reservaPedido.servico',
            'reservaPedido.quantidadeReserva',
            'reservaPedido.comissaoTerminal'
        ])->find($request->get('acompanhante_id'));

        // Verifica se o acompanhante já não foi removido
        if(is_object($acompanhante)) {

            // Remove o acompanhante da reserva
            if($acompanhante->delete()) {

                // recupera os dados do acompanhante na quantidade pedido
                $quantidade_reserva = $acompanhante->reservaPedido->quantidadeReserva->first(function ($quantidade_pedido) use ($acompanhante) {
                    return ($quantidade_pedido->variacao_servico_id == $acompanhante->variacao_servico_id);
                });

                // recupera o valor net do acompanhante
                $valor_net = $quantidade_reserva->valor_net / $quantidade_reserva->quantidade;

                // recupera o valor net do acompanhante
                $valor_venda = $quantidade_reserva->valor_total / $quantidade_reserva->quantidade;

                // Nova quantidade da variacao
                $nova_quantidade_variacao = $quantidade_reserva->quantidade - 1;

                // Atualiza a quantidade e valores da quantidade reserva
                $quantidade_reserva->update([
                    'quantidade' => $nova_quantidade_variacao,
                    'valor_net' => ($quantidade_reserva->valor_net - $valor_net),
                    'valor_total' => ($quantidade_reserva->valor_total - $valor_venda),
                ]);

                // Caso seja o ultimo da mesma variacao deleta a quantidade reserva
                if($nova_quantidade_variacao == 0) {
                    $quantidade_reserva->delete();
                }

                // Bloqueio consumido da reserva
                $bloqueio_consumido = $acompanhante->reservaPedido->bloqueio_consumido;

                // Caso a variacao consumuda bloqueio remove o bloqueio consumido na agenda e na reserva
                if($acompanhante->variacaoServico->consome_bloqueio == VariacaoServicoEnum::CONSOME_BLOQUEIO) {
                    // Diminui o bloqueio consumido na reserva
                    $bloqueio_consumido = ($bloqueio_consumido - 1);
                    // Recupera a agenda da reserva
                    $agenda = AgendaDataServico::find($acompanhante->reservaPedido->agenda_data_servico_id);
                    // Diminiu a quantidade consumida e volta a disponibilidade que havia sido consumida
                    $agenda->update(['disponivel' => ($agenda->disponivel + 1), 'consumido' => ($agenda->consumido - 1),]);
                }

                // Atualiza a quantidade e valores da reserva
                $acompanhante->reservaPedido->update([
                    "valor_total" => ($acompanhante->reservaPedido->valor_total - $valor_venda),
                    "valor_net" => ($acompanhante->reservaPedido->valor_net - $valor_net),
                    "quantidade" => ($acompanhante->reservaPedido->quantidade - 1),
                    "bloqueio_consumido" => $bloqueio_consumido
                ]);

                // Atualiza o valor final do pedido
                $acompanhante->reservaPedido->pedido->update([
                    "valor_total" => ($acompanhante->reservaPedido->pedido->valor_total - $valor_venda)
                ]);

                // Caso tenha comissao do terminal de vendas
                if(is_object($acompanhante->reservaPedido->comissaoTerminal)) {

                    // Calcula o novo valor da comissao
                    $comissao = ($acompanhante->reservaPedido->valor_total / 100 * $acompanhante->reservaPedido->servico->comissao_afiliado);

                    // Diferenca da comissao
                    $debito_comissao = $acompanhante->reservaPedido->comissaoTerminal->comissao - $comissao;

                    // Atualiza o valor da comissao e quantidade
                    $acompanhante->reservaPedido->comissaoTerminal->update([
                        'quantidade' => ($acompanhante->reservaPedido->comissaoTerminal->quantidade - 1), 'comissao' => $comissao
                    ]);

                    // Caso a comissao já estiver sido paga lança um debito para o proximo pagamento
                    if($acompanhante->reservaPedido->comissaoTerminal->status == ComissaoStatus::PAGO) {
                        DebitoComissaoTerminal::create([
                            "terminal_id" => $acompanhante->reservaPedido->comissaoTerminal->terminal_id,
                            "comissao_terminal_id" => $acompanhante->reservaPedido->comissaoTerminal->id,
                            "valor" => $debito_comissao,
                            "status" => ComissaoStatus::AGUARDANDO,
                        ]);
                    }
                }

                // Salva o historico de alteracao da reserva
                HistoricoReservaPedido::create([
                    "pedido_id" => $acompanhante->reservaPedido->pedido_id,
                    "reserva_pedido_id" => $acompanhante->reservaPedido->id,
                    "motivo" => MotivosReservaEnum::REMOCAO_ACOMPANHANTE,
                    "user_id" => auth()->user()->id,
                    "valor_fornecedor" => $valor_net,
                    "valor" => $valor_venda,
                ]);

                return $this->autoResponseJson(true, "Acompanhante removido", "O acompanhante {$acompanhante->nome} foi removido da reserva!");
            }

            return $this->autoResponseJson(false, "Acompanhante não removido", "Não foi possível remover o acompanhante da reserva, tente novamente");
        }

        return $this->autoResponseJson(false, "Acompanhante já removido", "Este acompanhante já foi removido da reserva!");
    }
}
