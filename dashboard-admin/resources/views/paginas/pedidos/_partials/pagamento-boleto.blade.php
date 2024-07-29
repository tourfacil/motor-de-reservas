@php($valor_total = $pedido->valor_total + $pedido->juros)
@php($valor_parcelamento = ($valor_total / $pedido->transacaoPedido->transacao->parcelas))

<div class="col-xl-3 mb-1 text-dark info-terminal">
    <p>
        Canal de venda <br>
        <strong>{{ $pedido->canalVenda->site }}</strong>
    </p>
    <p>
        Data de emissão <br>
        <strong>{{ $pedido->created_at->format('d/m/Y H:i') }}</strong>
    </p>
</div>
<div class="col-xl-3 mb-1 info-terminal">
    <p>
        Valor do boleto <br>
        <strong>
            R$ {{ formataValor($valor_total) }}
            <span class="text-secondary">- À vista</span>
        </strong>
    </p>
    <p>
        Valor total pago <br>
        <strong class="text-success">
            R$ 0,00
        </strong>
    </p>
</div>
<div class="col-xl-3 mb-1 info-terminal">
    <p>
        Método de pagamento <br>
        <strong>{{ $pedido->forma_pagamento }}</strong>
    </p>
    <p>
        Vencimento do boleto <br>
        <strong>20/04/2018</strong>
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
<div class="col-xl-12 mb-1 info-terminal p-0 mb-3">
    <div class="bg-grey p-4">
        <a href="" class="btn btn-secondary pl-3 pull-right btn-boleto">Imprimir boleto</a>
        <span class="d-inline">
            <strong class="pr-2">Código de barras</strong>
            00190.00009 02973.970003 00000.879171 6 78600000050000
        </span>
    </div>
</div>