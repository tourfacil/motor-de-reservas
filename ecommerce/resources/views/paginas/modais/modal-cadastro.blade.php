<div id="modal-cadastro" class="jqmodal">
    {{-- Formulario de cadastro --}}
    <form data-validate-ajax action="{{ route('ecommerce.cliente.store') }}" method="POST">
        <h6 class="font-weight-bold text-blue h3 mb-0">Criar nova conta</h6>
        <p class="text-muted">Preencha os campos abaixo, é rápido e fácil</p>
        <hr>
        <label for="nome_cadastro">Seu nome completo</label>
        <div class="position-relative mb-3">
            <div class="input-group-prepend-absolute">
                <i class="iconify" data-icon="jam:user-circle"></i>
            </div>
            <input type="text" name="nome" class="form-control" id="nome_cadastro"
                   required placeholder="Nome e sobrenome" title="Seu nome completo" maxlength="100"
                   data-required data-min="3" value="{{ old('nome') }}" data-nome-completo="true">
            <span class="invalid-feedback"></span>
        </div>
        <label for="email_cadastro">Seu e-mail</label>
        <div class="position-relative mb-3">
            <div class="input-group-prepend-absolute">
                <i class="iconify" data-icon="jam:envelope"></i>
            </div>
            <input type="email" name="email" class="form-control" id="email_cadastro"
                   required placeholder="Informe seu e-mail" title="Seu e-mail" maxlength="100"
                   data-required data-min="3" value="{{ old('email') }}">
            <span class="invalid-feedback"></span>
        </div>
        <label for="senha_cadastro">Informe uma senha</label>
        <div class="position-relative mb-3">
            <div class="input-group-prepend-absolute">
                <i class="iconify" data-icon="jam:key"></i>
            </div>
            <input type="password" name="password" class="form-control" id="senha_cadastro"
                   required placeholder="Informe uma senha" title="Informe uma senha" maxlength="100"
                   data-required data-min="3">
            <span class="invalid-feedback"></span>
            <small class="form-text text-muted">* Escolha uma senha segura para acessar sua conta</small>
        </div>
        <div class="d-none">@csrf</div>
        <div class="modal-footer-custom text-center py-2">
            <button type="submit" class="btn btn-blue btn-rounded text-white text-uppercase">
                Criar minha conta <i class="iconify ml-1" data-icon="jam:chevron-right"></i>
            </button>
        </div>
    </form>
</div>
