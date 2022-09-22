@extends('template.master')

@section('title', "Meus dados")

@section('body')

    {{-- Navbar --}}
    @include('template.navbar')

    <main class="bg-light" data-controller="MeusDadosCtrl">
        <div class="container pb-5">
            {{-- breadcrumb --}}
            <nav class="custom-bread py-3 mx-lg-5" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('ecommerce.index') }}">Tour Fácil</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Meus dados</li>
                </ol>
            </nav>
            <div class="p-3 p-sm-4 bg-white shadow-sm radius-10 mx-lg-5">
                <h1 class="font-weight-bold h2 mb-1">Meus dados</h1>
                <p class="line-initial text-muted">Gerencie seus dados em nosso site.</p>
                <hr class="blue mt-0 mb-3">
                <form data-validate-ajax class="mt-1 mt-md-2" action="{{ route('ecommerce.cliente.meus-dados.update') }}" method="POST">
                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="nome_cadastro">Nome completo</label>
                            <div class="position-relative mb-3">
                                <div class="input-group-prepend-absolute">
                                    <i class="iconify" data-icon="jam:user-circle"></i>
                                </div>
                                <input type="text" name="nome" class="form-control" id="nome_cadastro"
                                       required placeholder="Nome e sobrenome" title="Nome completo" data-auto-capitalize data-nome-completo="true"
                                       data-required data-min="3" maxlength="100" value="{{ $cliente->nome ?? "" }}">
                                <span class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="email_cadastro">E-mail de acesso</label>
                            <div class="position-relative mb-3">
                                <div class="input-group-prepend-absolute">
                                    <i class="iconify" data-icon="jam:envelope"></i>
                                </div>
                                <input type="email" name="email" class="form-control" id="email_cadastro"
                                       required placeholder="nome.sobrenome@email.com.br" title="E-mail de acesso"
                                       data-required data-min="3" maxlength="100" value="{{ $cliente->email }}">
                                <span class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="cpf_cadastro">CPF</label>
                            <div class="position-relative mb-3">
                                <div class="input-group-prepend-absolute">
                                    <i class="iconify" data-icon="jam:fingerprint"></i>
                                </div>
                                <input type="tel" name="cpf" class="form-control vanillaMask" id="cpf_cadastro" required
                                       placeholder="***.***.***-**" data-required data-min="14" data-mask="cpf"
                                       title="CPF" maxlength="14" value="{{ $cliente->cpf ?? "" }}"/>
                                <span class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="nascimento_cadastro">Data de nascimento</label>
                            <div class="position-relative mb-3">
                                <div class="input-group-prepend-absolute">
                                    <i class="iconify" data-icon="jam:calendar"></i>
                                </div>
                                <input type="tel" name="nascimento" class="form-control vanillaMask" id="nascimento_cadastro" required
                                       placeholder="DD/MM/AAAA" data-required data-min="10" title="Data de nascimento" data-mask="date"
                                       maxlength="10" value="{{ (isset($cliente->nascimento)) ? $cliente->nascimento->format('d/m/Y') : "" }}"/>
                                <span class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="tel_cadastro">Telefone</label>
                            <div class="position-relative mb-3">
                                <div class="input-group-prepend-absolute">
                                    <i class="iconify" data-icon="jam:phone"></i>
                                </div>
                                <input type="tel" name="telefone" class="form-control vanillaMask" id="tel_cadastro" required
                                       placeholder="(00) 0 0000.0000" data-required data-min="14" data-mask="phone" value="{{ $cliente->telefone ?? "" }}"
                                       title="Telefone para contato" maxlength="16"/>
                                <span class="invalid-feedback"></span>
                            </div>
                        </div>
                    </div>
                    <div class="d-none">@csrf {{ method_field("PUT") }}</div>
                    <div class="text-center">
                        <button class="btn btn-blue btn-rounded text-uppercase px-4 pb-2 text-white border-0">Atualizar dados</button>
                    </div>
                </form>
                <div class="col-sm-12 col-md-6"></div>
            </div>
        </div>
    </main>

    @include('template.footer')

@endsection
