<div id="comprar" class="jqmodal modal-lg py-4 full-mobile padding-mobile extra-top">
    <div class="row align-items-stretch">
        <div class="col-12 col-lg-6">
            <div class="d-flex flex-column">
                <h4 class="h3 font-weight-medium text-blue text-center mb-3 line-initial">1. Escolha a data desejada</h4>
                <div id="calendar-container" class="datepicker-wrapper alternative mb-3"></div>
            </div>
        </div>
        <div class="col-12 col-lg-6 d-flex flex-column pl-lg-0 pr-lg-4 mb-5">
            <h4 class="h3 font-weight-medium text-blue text-center mt-2 mt-lg-0 mb-3 line-initial">2. Informe a quantidade</h4>
            <div class="alert alert-danger info-error d-none"><p class="m-0"></p></div>
            <div class="list-variacoes pt-1"></div>
            <div class="modal-footer-custom row mt-auto mb-2 d-flex align-items-end align-items-lg-center justify-content-between">
                <div class="col-12 col-sm-auto text-center text-sm-left d-none d-lg-block">
                    <span>Total à pagar</span>
                    <p id="total-modal" class="h4">R$ 0,00</p>
                </div>
                <div class="col-12 col-sm-auto text-center text-sm-right mt-2 mt-sm-0 d-none d-lg-block">
                    <button data-action="sendToCart" class="btn btn-blue btn-rounded text-uppercase font-weight-medium text-white mt-1 mb-5">Adicionar ao carrinho</button>
                </div>
            </div>
        </div>
    </div>
    {{-- Barra fixa no mobile --}}
    <div class="fixed-bottom-mobile d-lg-none px-3 py-2">
        <div class="row flex-nowrap justify-content-between align-items-center">
            <div class="col-auto">
                <small class="text-muted mb-1">Total à pagar</small>
                <div class="d-flex align-items-start">
                    <p id="total-modal-mobile" class="h4">R$ 0,00</strong>
                </div>
            </div>
            <div class="col-auto pl-0">
                <button data-action="sendToCart" class="btn btn-blue btn-not-focus btn-rounded border-0 px-md-4 pb-2 text-uppercase font-weight-medium">Adicionar ao carrinho</button>
            </div>
        </div>
    </div>
</div>
