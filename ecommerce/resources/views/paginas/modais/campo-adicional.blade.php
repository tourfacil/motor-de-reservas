<div id="modal-campo-adicional" class="jqmodal modal-lg full-mobile padding-mobile">
    {{-- Formulario para os dados adicionais --}}
    <form action="{{ route('ecommerce.carrinho.form-parse') }}" class="position-relative" id="adicionais">
        <div class="modal-loader d-none align-items-center justify-content-center flex-column">
            <div class="text-center">
                <strong class="d-block">Aguarde...</strong>
                <div class="spinner-border text-primary mt-3" role="status"></div>
            </div>
        </div>
        <h6 class="font-weight-bold text-blue h3 mt-1 mb-1">Informações adicionais</h6>
        <p class="text-muted">Preencha atentamente os campos abaixo</p>
        <div class="fields-input row mb-2"></div>
        <div class="alert alert-danger info-error d-none"><p class="m-0"></p></div>
        <div class="modal-footer-custom row d-flex justify-content-between pb-2">
            <div class="col-12 col-sm-auto text-center text-sm-left">
                <button type="button" rel="jqmodal:close" class="btn text-uppercase pl-0">
                    <i class="iconify mr-1 d-none d-sm-inline" data-icon="jam:chevron-left"></i> <span class="text-btn">Acompanhantes</span>
                </button>
            </div>
            <div class="col-12 col-sm-auto text-center text-sm-right mt-2 mt-sm-0">
                <button type="submit" data-action="saveAdditional" class="btn text-uppercase btn-success btn-rounded">
                    Salvar e continuar <i class="iconify ml-1" data-icon="jam:chevron-right"></i>
                </button>
            </div>
        </div>
        <div class="d-none">@csrf</div>
    </form>
</div>
