<div id="modal-acompanhantes" class="jqmodal modal-lg full-mobile padding-mobile position-relative">
    {{-- Formulario para os dados dos acompanhantes --}}
    <form action="{{ route('ecommerce.carrinho.form-parse') }}" class="position-relative" autocomplete="off" id="acompanhantes">
        <div class="modal-loader d-none align-items-center justify-content-center flex-column">
            <div class="text-center">
                <strong class="d-block">Aguarde...</strong>
                <div class="spinner-border text-primary mt-3" role="status"></div>
            </div>
        </div>
        <h6 class="font-weight-bold text-blue h3 mt-1 mb-1">Quem irá utilizar?</h6>
        <p class="text-muted">Preencha com os dados de quem irá utilizar o voucher</p>
        <div class="fields-input mt-1"></div>
        <div class="alert alert-danger info-error d-none"><p class="m-0"></p></div>
        <div class="modal-footer-custom row d-flex justify-content-between pb-2">
            <div class="col-12 col-sm-auto text-center text-sm-left">
                <button type="button" rel="jqmodal:close" class="btn text-uppercase pl-0">
                    <i class="iconify mr-1 d-none d-sm-inline" data-icon="jam:chevron-left"></i> Ver datas
                </button>
            </div>
            <div class="col-12 col-sm-auto text-center text-sm-right mt-2 mt-sm-0">
                <button type="submit" data-action="savePersons" class="btn text-uppercase btn-success btn-rounded">
                    Salvar e continuar <i class="iconify ml-1" data-icon="jam:chevron-right"></i>
                </button>
            </div>
        </div>
        <div class="d-none">@csrf</div>
    </form>
</div>
