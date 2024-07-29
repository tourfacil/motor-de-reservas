<div id="modal-campo-adicional" class="jqmodal modal-lg full-mobile padding-mobile">
    {{-- Formulario para os dados adicionais --}}
    <div class="modal-loader d-none align-items-center justify-content-center flex-column">
        <div class="text-center">
            <strong class="d-block">Aguarde...</strong>
            <div class="spinner-border text-primary mt-3" role="status"></div>
        </div>
    </div>
    <h6 class="font-weight-bold text-blue h3 mt-1 mb-1"  style="background: linear-gradient(135deg, #fd584c 0%, #f84271 80%);-webkit-background-clip: text;-webkit-text-fill-color: transparent;">Informações adicionais</h6>
    <p class="text-muted">Preencha atentamente os campos abaixo</p>
    <div class="fields-input row mb-2"></div>
    <div class="alert alert-danger info-error d-none"><p class="m-0"></p></div>
    <div class="modal-footer-custom row d-flex justify-content-between pb-2">
        <div class="col-12 col-sm-auto text-center text-sm-left">
        </div>
        <div class="col-12 col-sm-auto text-center text-sm-right mt-2 mt-sm-0">
            <button id="salvar-campos" type="submit" class="btn text-uppercase btn-success btn-rounded">
                Salvar e continuar
            </button>
        </div>
    </div>
    <div class="d-none">@csrf</div>
</div>
