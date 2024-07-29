<?php namespace App\Http\Controllers\Pedidos;

use Illuminate\Http\Request;
use App\Http\Requests\Reserva\UpdateQuantidadeRequest;
use App\Http\Controllers\Controller;
use TourFacil\Core\Models\QuantidadeReservaPedido;
use TourFacil\Core\Services\QuantidadeReservaService;

/**
 * Class QuantidadeReservaController
 * @package App\Http\Controllers\Pedidos
 */
class QuantidadeReservaController extends Controller
{
    /**
     * Detalhes da quantidade reserva
     *
     * @param $quantidade_id
     * @return mixed
     */
    public function view($quantidade_id)
    {
        return QuantidadeReservaPedido::with('variacaoServico')->find($quantidade_id);
    }

    /**
     * Atualiza a quantidade adquirida na reserva
     *
     * @param UpdateQuantidadeRequest $request
     * @return array
     */
    public function update(UpdateQuantidadeRequest $request)
    {
        // Recupera os dados da quantidade reserva
        $quantidade_reserva = QuantidadeReservaPedido::with([
            'reservaPedido',
            'variacaoServico',
            'reservaPedido.pedido',
            'reservaPedido.servico',
            'reservaPedido.comissaoTerminal'
        ])->find($request->get('quantidade_reserva_id'));

        // Nova quantidade na reserva
        $nova_quantidade = (int) $request->get('nova_quantidade');

        // Quantidade removida da reservac
        $qtd_removida = $quantidade_reserva->quantidade - $nova_quantidade;

        // Caso for ZERO remove a opção da reserva
        if($nova_quantidade == 0) {

            // Preve para nao deixar a reserva com quantidade ZERO
            if($quantidade_reserva->quantidade == $quantidade_reserva->reservaPedido->quantidade) {
                return $this->autoResponseJson(false, "Não é possível remover", "A quantidade da reserva não pode ficar zerada!");
            }

            // Remove a quantidade da reserva
            QuantidadeReservaService::removerQuantidadeReserva($quantidade_reserva);

            return $this->autoResponseJson(true, "Variação removida", "A quantidade foi atualizada com sucesso!");
        }

        // Atualiza a quantidade adquirida na reserva
        QuantidadeReservaService::updateQuantidadeReserva($quantidade_reserva, $nova_quantidade, $qtd_removida);

        return $this->autoResponseJson(true, "Quantidade atualizada", "A quantidade foi atualizada com sucesso!");
    }
}
