@extends('template.master')

@section('title', "Redefinição de senha")

@section('body')

    {{--  Footer sticky  --}}
    <div class="d-md-flex flex-column h-100">

        @include('template.navbar-clear', ['back_button' => false])

        <main class="d-flex flex-column align-items-center justify-content-center h-100 bg-light pt-4 pb-5">
            <div class="container">
                <div class="row d-flex align-items-center justify-content-center">
                    <div class="col-12 col-lg-6">
                        <div class="text-md-center">
                            <h1 class="font-weight-bold mb-0">Redefinição de senha</h1>
                            <hr class="blue mb-3">
                            <p class="line-initial">Preencha o formulário abaixo para cadastrar uma nova senha de acesso.</p>
                        </div>
                        <form data-validate-ajax class="mt-3 mt-md-0" action="{{ route('ecommerce.cliente.cadastrar-senha') }}" method="POST">
                            <label for="email">E-mail de acesso</label>
                            <div class="position-relative mb-3">
                                <div class="input-group-prepend-absolute">
                                    <i class="iconify" data-icon="jam:envelope"></i>
                                </div>
                                <input type="email" name="email" class="form-control" id="email"
                                       required placeholder="Informe seu e-mail" title="E-mail de acesso" maxlength="100"
                                       data-required data-min="3" value="{{ $email }}" readonly>
                                <span class="invalid-feedback"></span>
                            </div>
                            <label for="nova_senha">Nova senha: &nbsp;(Mín 8 caracteres)</label>
                            <div class="position-relative mb-3">
                                <div class="input-group-prepend-absolute">
                                    <i class="iconify" data-icon="jam:key"></i>
                                </div>
                                <input type="password" name="password" class="form-control" id="nova_senha" data-min="3"
                                       required placeholder="Digite uma nova senha" title="Digite uma nova senha" data-required maxlength="50">
                                <span class="invalid-feedback"></span>
                                <small class="form-text text-muted">* Escolha uma senha segura para acessar sua conta</small>
                            </div>
                            <label for="confirma_senha">Redigite sua nova senha:</label>
                            <div class="position-relative mb-3">
                                <div class="input-group-prepend-absolute">
                                    <i class="iconify" data-icon="jam:key"></i>
                                </div>
                                <input type="password" name="password_confirmation" class="form-control" id="confirma_senha" data-min="3"
                                       required placeholder="Redigite a nova senha" title="Redigite a nova senha" data-required maxlength="50">
                                <span class="invalid-feedback"></span>
                            </div>
                            <div class="d-none">
                                @csrf
                                <input type="hidden" name="token" value="{{ $token }}">
                            </div>
                            <div class="text-center modal-footer-custom pt-2">
                                <button class="btn btn-blue btn-rounded text-white text-uppercase">Atualizar senha</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>

        @include('template.footer-clear')
    </div>

@endsection
