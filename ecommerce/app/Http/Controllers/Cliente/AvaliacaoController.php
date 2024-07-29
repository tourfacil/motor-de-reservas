<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use TourFacil\Core\Enum\StatusAvaliacaoServicoEnum;
use TourFacil\Core\Models\AvaliacaoServico;
use TourFacil\Core\Models\Pedido;
use TourFacil\Core\Models\PedidoAvaliacaoMailHashLogin;
use TourFacil\Core\Models\ReservaPedido;

class AvaliacaoController extends Controller
{
    public function store(Request $request)
    {
        $reserva = ReservaPedido::find($request->reserva_id);

        if($reserva->pedido->cliente->id != auth()->user()->id) {
            return redirect()->back();
        }

        $dados_avaliacao = [
            'servico_id' => $reserva->servico->id,
            'nota' => $request->get('nota'),
            'avaliacao' => $request->get('avaliacao'),
            'nome' => $reserva->pedido->cliente->nome,
            'status' => StatusAvaliacaoServicoEnum::PENDENTE,
            'reserva_pedido_id' => $reserva->id,
            'cliente_id' => $reserva->pedido->cliente->id,
        ];

        AvaliacaoServico::create($dados_avaliacao);

        return redirect()->route('ecommerce.cliente.pedidos.view', $reserva->pedido->codigo);
    }

    public function avaliarLogin(Request $request, $uuid, $hash)
    {
        $pedido_hash = PedidoAvaliacaoMailHashLogin::where('uuid', $uuid)
                                                   ->where('hash', $hash)
                                                   ->with([
                                                       'pedido',
                                                       'pedido.cliente'
                                                   ])
                                                   ->get()
                                                   ->first();

        if($pedido_hash == null) {
            return redirect()->route('ecommerce.index');
        }

        $pedido = $pedido_hash->pedido;
        $cliente = $pedido->cliente;

        \Auth::loginUsingId($cliente->id);

        return redirect()->route('ecommerce.cliente.pedidos.view', ['codigo_pedido' => $pedido->codigo]);
    }
}
