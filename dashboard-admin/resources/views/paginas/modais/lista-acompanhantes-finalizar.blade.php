<div id="modal-acompanhantes" class="jqmodal modal-lg full-mobile padding-mobile position-relative">
    {{-- Formulario para os dados dos acompanhantes --}}
    <div class="modal-loader d-none align-items-center justify-content-center flex-column">
        <div class="text-center">
            <strong class="d-block">Aguarde...</strong>
            <div class="spinner-border text-primary mt-3" role="status"></div>
        </div>
    </div>
    <h6 class="font-weight-bold text-blue h3 mt-1 mb-1" style="background: linear-gradient(135deg, #fd584c 0%, #f84271 80%);-webkit-background-clip: text;-webkit-text-fill-color: transparent;">
        Quem irá utilizar?
    </h6>
    <p class="text-muted">Preencha com os dados de quem irá utilizar o voucher</p>
    <div class="fields-input mt-1"></div>
    <div class="alert alert-danger info-error d-none"><p class="m-0"></p></div>
    <div class="modal-footer-custom row d-flex justify-content-between pb-2">
        <div class="col-12 col-sm-auto text-center text-sm-left">

        </div>
        <div class="col-12 col-sm-auto text-center text-sm-right mt-2 mt-sm-0">
            <button id="salvar-acompanhantes" type="submit" data-action="savePersons" class="btn text-uppercase btn-success btn-rounded">
                Salvar e continuar
            </button>
        </div>
    </div>
    <div class="d-none">@csrf</div>
</div>
