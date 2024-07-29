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
        <strong>R$ {{ formataValor($pedido->valor_total + $pedido->juros) }} <small>Débito</small></strong>
    </p>
    <p>
        Número cartão <br>
        <strong>{{ $pedido->transacaoPedido->transacao->numero_cartao }}</strong>
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
