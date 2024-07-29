@php($transacao_cartao = $pedido->transacaoPedido->transacao)
@php($total_pedido = $pedido->valor_total + $pedido->juros)
<div class="row">
    <div class="col-12 d-md-none">
        <h4 class="h5 font-weight-medium mb-0">Código pedido</h4>
        <p>#{{ $pedido->codigo }}</p>
    </div>
    <div class="col-12 col-md-4">
        <h4 class="h5 font-weight-medium mb-1">Forma de pagamento</h4>
        <p class="text-muted">{{ $pedido->forma_pagamento }}</p>
    </div>
    <div class="col-12 col-md-4">
        <h4 class="h5 font-weight-medium mb-1">Cartão utilizado</h4>
        <p class="text-muted">Cartão {{ ucfirst($transacao_cartao->bandeira) }}***{{ lastDigits($transacao_cartao->numero_cartao) }}</p>
    </div>
    <div class="col-12 col-md-4">
        <h4 class="h5 font-weight-medium mb-1">Status do pagamento</h4>
        <p class="text-{{ $pedido->cor_status_pagamento }} text-uppercase">
            <strong class="font-weight-medium">{{ $pedido->pagamento_status }}</strong>
        </p>
    </div>
</div>
<div class="row">
    <div class="col-12 col-md-4">
        <h4 class="h5 font-weight-medium mb-1">Valor total do pedido</h4>
        <p class="text-muted mb-md-0">
            <span>R$ {{ formataValor($total_pedido) }}</span>&nbsp;
            ({{ $transacao_cartao->parcelas }}x R$ {{ formataValor($total_pedido / $transacao_cartao->parcelas) }})
        </p>
    </div>
    <div class="col-12 col-md-4">
        <h4 class="h5 font-weight-medium mb-1">Nome cartão</h4>
        <p class="text-muted mb-md-0">{{ $transacao_cartao->nome_cartao }}</p>
    </div>
    <div class="col-12 col-md-4">
        <h4 class="h5 font-weight-medium mb-1">Status do pedido</h4>
        <p class="text-{{ $pedido->cor_status_pedido }} text-uppercase mb-md-0">
            <strong class="font-weight-medium">{{ $pedido->status_pedido }}</strong>
        </p>
    </div>
</div>
