@extends('template.header')

@section('title', 'Alterar senha')

@section('content')

    <div class="row">
        <div class="page-header">
            <div class="d-flex align-items-center">
                <h2 class="page-header-title text-dark">Alterar <span class="text-gradient-01">senha de acesso</span></h2>
                <div>{{ Breadcrumbs::render('app.meus-dados.senha.index') }}</div>
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
                    <h4>Alteração de senha</h4>
                </div>
                <div class="widget-body">
                    <div class="col-10 ml-auto">
                        <div class="section-title mt-3 mb-3">
                            <h4>01. Informe uma nova senha de acesso.</h4>
                        </div>
                    </div>
                    <form data-validate-ajax method="POST" action="{{ route('app.meus-dados.senha.update') }}" class="form-horizontal">
                        <div class="form-group row d-flex align-items-center mb-5">
                            <label for="email" class="col-lg-2 form-control-label d-flex justify-content-lg-end">E-mail</label>
                            <div class="col-lg-6">
                                <input id="email" type="email" required class="form-control" value="{{ $dados->email }}" name="email" title="Email" readonly>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="form-group row d-flex align-items-center mb-5">
                            <label for="password" class="col-lg-2 form-control-label d-flex justify-content-lg-end">Nova senha</label>
                            <div class="col-lg-6">
                                <input id="password" type="password" required class="form-control" placeholder="Sua nova senha (Min. 8 caracteres)"
                                       name="password" data-required title="Nova senha" data-min="6" autocomplete="nope">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="form-group row d-flex align-items-center mb-5">
                            <label for="password_confirmation" class="col-lg-2 form-control-label d-flex justify-content-lg-end">Confirmação</label>
                            <div class="col-lg-6">
                                <input id="password_confirmation" type="password" required class="form-control" placeholder="Redigite sua nova senha (Min. 8 caracteres)"
                                       name="password_confirmation" data-required title="Confirme sua senha" data-min="6">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="hide">{{ method_field("PUT") }}</div>
                        <div class="em-separator separator-dashed"></div>
                        <div class="text-center">
                            <button class="btn btn-gradient-01 btn-shadow" type="submit">Alterar minha senha</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
