@php($valor_total = $pedido->valor_total + $pedido->juros)
@php($valor_parcelamento = ($valor_total / $pedido->transacaoPedido->transacao->parcelas))

<div class="col-xl-3 mb-1 info-terminal">
    <p>
        Canal de venda <br>
        <strong>{{ $pedido->canalVenda->site }}</strong>
    </p>
    <p>
        Nome no cartão <br>
        <strong>{{ $pedido->transacaoPedido->transacao->nome_cartao }}</strong>
    </p>
</div>
<div class="col-xl-3 mb-1 info-terminal">
    <p>
        Valor total do pedido <br>
        <strong>
            R$ {{ formataValor($valor_total) }}
            <span class="text-secondary">- {{ $pedido->transacaoPedido->transacao->parcelas }}x R$ {{ formataValor($valor_parcelamento) }} </span>
        </strong>
    </p>
    <p>
        Número cartão <br>
        <strong>{{ $pedido->transacaoPedido->transacao->numero_cartao }}</strong>
        <button data-target="#ver-tid-cartao" data-toggle="modal" class="btn btn-sm btn-secondary align-middle ml-1">Ver TID</button>
    </p>
</div>
<div class="col-xl-3 mb-1 info-terminal">
    <p>
        Método de pagamento <br>
        <strong>{{ $pedido->forma_pagamento }}</strong>
    </p>
    <p>
        Bandeira do cartão <br>
        <strong>{{ ucfirst($pedido->transacaoPedido->transacao->bandeira) }}</strong>
    </p>
</div>
<div class="col-xl-3 mb-1 info-terminal">
    <p>
        Status do pagamento <br>
        <strong class="text-{{ $pedido->cor_status_pagamento }} text-uppercase">{{ $pedido->pagamento_status }}</strong>
    </p>
    <p>
        Status pedido <br>
        <strong class="text-{{ $pedido->cor_status_pedido }} text-uppercase">{{ $pedido->status_pedido }}</strong>
    </p>
</div>

{{-- Ver os detalhes do TID cartão --}}
<div id="ver-tid-cartao" class="modal modal-top fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">TID Cartão de crédito</h4>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">×</span>
                    <span class="sr-only">close</span>
                </button>
            </div>
            <div class="modal-body pb-4">
                <p>Detalhes da cobrança no cartão</p>
                <code>
                    {!! makeNestedList($pedido->transacaoPedido->transacao->transacao) !!}
                </code>
            </div>
        </div>
    </div>
</div>
