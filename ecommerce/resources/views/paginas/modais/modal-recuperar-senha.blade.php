<div id="modal-recuperar-senha" class="jqmodal">
    {{-- Formulario de recuperar senha --}}
    <form data-validate-ajax action="{{ route('ecommerce.cliente.recuperar-senha') }}" method="POST">
        <h6 class="font-weight-bold text-blue h3 mb-1">Recuperar senha</h6>
        <p class="text-muted line-initial">Identifique-se para receber um e-mail para criar uma nova senha.</p>
        <hr>
        <label for="email_recuperar">E-mail de acesso:</label>
        <div class="position-relative mb-3">
            <div class="input-group-prepend-absolute">
                <i class="iconify" data-icon="jam:envelope"></i>
            </div>
            <input type="email" name="email" class="form-control" id="email_recuperar"
                   required placeholder="Informe seu e-mail" title="E-mail de acesso" maxlength="100"
                   data-required data-min="3" value="{{ old('email') }}">
            <span class="invalid-feedback"></span>
        </div>
        <div class="d-none">@csrf</div>
        <div class="modal-footer-custom text-center py-2">
            <button type="submit" class="btn btn-blue btn-rounded text-white text-uppercase">
                Enviar senha <i class="iconify ml-1" data-icon="jam:chevron-right"></i>
            </button>
        </div>
    </form>
</div>
