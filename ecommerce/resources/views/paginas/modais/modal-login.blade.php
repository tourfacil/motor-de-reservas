<div id="modal-login" class="jqmodal">
    {{-- Formulario de login --}}
    <form data-validate-ajax data-redirect action="{{ route('ecommerce.cliente.post-login') }}" method="POST">
        <h6 class="font-weight-bold text-blue h3 mb-0">Fazer login</h6>
        <p class="text-muted">Preencha os dados abaixo</p>
        <hr>
        <label for="email_login">E-mail de acesso:</label>
        <div class="position-relative mb-3">
            <div class="input-group-prepend-absolute">
                <i class="iconify" data-icon="jam:envelope"></i>
            </div>
            <input type="email" name="email" class="form-control" id="email_login"
                   required placeholder="Informe seu e-mail" title="E-mail de acesso" maxlength="100"
                   data-required data-min="3" value="{{ old('email') }}">
            <span class="invalid-feedback"></span>
        </div>
        <label for="senha_login">Informe sua senha:</label>
        <div class="position-relative mb-3">
            <div class="input-group-prepend-absolute">
                <i class="iconify" data-icon="jam:key"></i>
            </div>
            <input type="password" name="password" class="form-control" id="senha_login"
                   required placeholder="Senha de acesso" title="Informe sua senha" data-required maxlength="100">
            <span class="invalid-feedback"></span>
        </div>
        <div class="d-none">@csrf</div>
        <div class="modal-footer-custom text-center pt-2">
            <button type="submit" class="btn btn-blue btn-rounded text-white text-uppercase mb-2">
                Acessar minha conta <i class="iconify ml-1" data-icon="jam:chevron-right"></i>
            </button>
            <button type="button" data-modal="#modal-recuperar-senha" title="Recuperar senha" class="btn d-block m-auto btn-link">Esqueci minha senha</button>
        </div>
    </form>
</div>

{{-- Redefinicao de senha --}}
@include('paginas.modais.modal-recuperar-senha')
