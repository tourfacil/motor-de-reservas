@extends('template.master')

@section('title', "Acessar minha conta")

@section('body')

    {{--  Footer sticky  --}}
    <div class="d-md-flex flex-column h-100" data-controller="LoginCtrl">

        @include('template.navbar-clear')

        <main class="d-flex flex-column align-items-center justify-content-center h-100 bg-light pt-4 pb-5">
            <div class="container">
                <div class="row d-flex align-items-center justify-content-center">
                    <div class="col-12 col-lg-6">
                        <div class="text-md-center">
                            <h1 class="font-weight-bold mb-0">Acessar minha conta</h1>
                            <hr class="blue mb-3">
                            <p class="line-initial">Preencha o formulário abaixo para acessar sua conta e ver seus pedidos.</p>
                        </div>
                        <form data-validate-ajax data-redirect class="mt-3 mt-md-0" action="{{ route('ecommerce.cliente.post-login') }}" method="POST">
                            <label for="email_login">E-mail de acesso</label>
                            <div class="position-relative mb-3">
                                <div class="input-group-prepend-absolute">
                                    <i class="iconify" data-icon="jam:envelope"></i>
                                </div>
                                <input type="email" name="email" class="form-control" id="email_login"
                                       required placeholder="Informe seu e-mail" title="E-mail de acesso" maxlength="100"
                                       data-required data-min="3" value="{{ old('email') }}">
                                <span class="invalid-feedback"></span>
                            </div>
                            <label for="senha_login">Informe sua senha</label>
                            <div class="position-relative mb-3">
                                <div class="input-group-prepend-absolute">
                                    <i class="iconify" data-icon="jam:key"></i>
                                </div>
                                <input type="password" name="password" class="form-control" id="senha_login"
                                       required placeholder="Senha de acesso" title="Informe sua senha" data-required maxlength="100">
                                <span class="invalid-feedback"></span>
                            </div>
                            <div class="d-none">
                                @csrf
                            </div>
                            <div class="text-center modal-footer-custom py-2">
                                <div class="row justify-content-center">
                                    <div class="col-12 col-md-auto order-lg-last mb-3 mb-md-0">
                                        <button class="btn btn-blue btn-rounded text-white text-uppercase">Acessar minha conta</button>
                                    </div>
                                    <div class="col-12 col-md-auto">
                                        <button type="button" data-modal="#modal-recuperar-senha" title="Recuperar senha" class="btn d-block m-auto btn-link">Esqueci minha senha</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>

        @include('template.footer-clear')
    </div>

    {{-- Modal para redefinir a senha --}}
    @include('paginas.modais.modal-recuperar-senha')

@endsection

@section('seo')
    <meta name="description" content="Acesse sua conta e veja seus pedidos realizados em nossos site!" />
    <meta property="og:description" content="Acesse sua conta e veja seus pedidos realizados em nossos site!" />
    <meta name="twitter:description" content="Acesse sua conta e veja seus pedidos realizados em nossos site!" />
@endsection
