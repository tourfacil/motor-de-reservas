<div id="modal-formas-pagamento" class="jqmodal">
    <div class="pb-2">
        <h6 class="font-weight-bold text-blue h3 mb-3">Formas de pagamento</h6>
        <hr class="mb-2">
        <h6 class="h4 m-0">Cartão de crédito</h6>
        <p class="text-muted">Em até {{ $canal_venda->parcelas_sem_juros }}x sem juros</p>
        <img src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" class="lazyload img-fluid mr-1 mb-1" data-src="{{ asset('images/icons/pagamento/visa.svg') }}" alt="Pague com cartão Visa!">
        <img src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" class="lazyload img-fluid mr-1 mb-1" data-src="{{ asset('images/icons/pagamento/elo.svg') }}" alt="Pague com cartão Elo!">
        <img src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" class="lazyload img-fluid mr-1 mb-1" data-src="{{ asset('images/icons/pagamento/amex.svg') }}" alt="Pague com cartão Amex!">
        <img src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" class="lazyload img-fluid mr-1 mb-1" data-src="{{ asset('images/icons/pagamento/mastercard.svg') }}" alt="Pague com cartão Mastercard!">
        <img src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" class="lazyload img-fluid mr-1 mb-1" data-src="{{ asset('images/icons/pagamento/hipercard.svg') }}" alt="Pague com cartão Hipercard!">
    </div>
</div>
