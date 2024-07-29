<div class="col-xl-3 mb-1 info-terminal">
    <p>
        Canal de venda <br>
        <strong>{{ $pedido->canalVenda->site }}</strong>
    </p>
    <p>
        Meio de pagamento <br>
        @if($pedido->meio_pagamento_interno != null)
            <strong style="text-transform: uppercase;">{{ TourFacil\Core\Enum\MeioPagamentoInternoEnum::MEIOS[$pedido->meio_pagamento_interno] }}</strong>
        @else
            <strong style="text-transform: uppercase;">Não registrado</strong>
        @endif
    </p>
    <p>
        {{-- Nome no cartão <br>
        <strong></strong> --}}
    </p>
</div>
<div class="col-xl-3 mb-1 info-terminal">
    <p>
        Valor total do pedido <br>
        <strong>
            R$ {{ formataValor($valor_total) }}
            <span class="text-secondary"></span>
        </strong>
    </p>
    <p>
        Método de pagamento <br>
        @if($pedido->metodo_pagamento_interno != null)
            <strong style="text-transform: uppercase;">{{ TourFacil\Core\Enum\MetodoPagamentoInternoEnum::METODOS[$pedido->metodo_pagamento_interno] }}</strong>
        @else
            <strong style="text-transform: uppercase;">Não registrado</strong>
        @endif
    </p>
</div>
<div class="col-xl-3 mb-1 info-terminal">
    <p>
        Modo de pagamento <br>
        <strong>INTERNO</strong>
    </p>
    {{-- <p>
        Bandeira do cartão <br>
    </p> --}}
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
                    TRANSACAO
                </code>
            </div>
        </div>
    </div>
</div>
