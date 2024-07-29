@extends('template.header')

@section('title', 'Meus dados')

@section('content')

    <div class="row">
        <div class="page-header">
            <div class="d-flex align-items-center">
                <h2 class="page-header-title text-dark">Meus <span class="text-gradient-01">dados</span></h2>
                <div>{{ Breadcrumbs::render('app.meus-dados') }}</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-3">
            <div class="widget has-shadow">
                <div class="widget-body">
                    <div class="mt-5">
                        <img src="{{ asset('images/letter-p.svg') }}" width="75" alt="User" class="avatar d-block mx-auto">
                    </div>
                    <h3 class="text-center mt-5 mb-2">{{ $dados->nome }}</h3>
                    <p class="text-center text-truncate">{{ $dados->email }}</p>
                    <p class="text-center">Cadastrado em {{ $dados->created_at->format('d/m/Y') }}</p>
                    <div class="em-separator separator-dashed"></div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('app.meus-dados.index') }}"><i class="la la-briefcase la-2x align-middle pr-3"></i>Meus dados</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('app.meus-dados.senha.index') }}"><i class="la la-lock la-2x align-middle pr-3"></i>Alterar senha</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-xl-9">
            <div class="widget has-shadow">
                <div class="widget-header bordered no-actions d-flex align-items-center">
                    <h4>Atualização do perfil</h4>
                </div>
                <div class="widget-body">
                    <div class="col-10 ml-auto">
                        <div class="section-title mt-3 mb-3">
                            <h4>01. Informações pessoais</h4>
                        </div>
                    </div>
                    <form data-validate-ajax method="POST" action="{{ route('app.meus-dados.update') }}" class="form-horizontal">
                        <div class="form-group row d-flex align-items-center mb-5">
                            <label for="nome" class="col-lg-2 form-control-label d-flex justify-content-lg-end">Nome</label>
                            <div class="col-lg-6">
                                <input id="nome" type="text" required class="form-control" placeholder="Nome completo" value="{{ $dados->nome }}"
                                       name="nome" data-required title="Nome completo" data-min="3" data-auto-capitalize>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="form-group row d-flex align-items-center mb-5">
                            <label for="email" class="col-lg-2 form-control-label d-flex justify-content-lg-end">E-mail</label>
                            <div class="col-lg-6">
                                <input id="email" type="email" required class="form-control" placeholder="nome.sobrenome@email.com" value="{{ $dados->email }}"
                                       name="email" data-required title="Email" data-min="3">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="form-group row d-flex align-items-center mb-5">
                            <label for="terminal" class="col-lg-2 form-control-label d-flex justify-content-lg-end">Fornecedor</label>
                            <div class="col-lg-6">
                                <input id="terminal" type="text" class="form-control" placeholder="Terminal de venda" value="{{ $fornecedor->nome_fantasia }} - {{ $fornecedor->cnpj }}" readonly>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="hide">
                            {{ method_field("PUT") }}
                        </div>
                        <div class="em-separator separator-dashed"></div>
                        <div class="text-center">
                            <button class="btn btn-gradient-01 btn-shadow" type="submit">Salvar alterações</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
