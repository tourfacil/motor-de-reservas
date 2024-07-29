@extends('template.header')

@section('title', 'Meus dados')

@section('content')

    <div class="row">
        <div class="page-header">
            <div class="d-flex align-items-center">
                <h2 class="page-header-title text-gradient-01">Meus dados</h2>
                <div>{{ Breadcrumbs::render('app.meus-dados') }}</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-3">
            <div class="widget has-shadow">
                <div class="widget-body">
                    <div class="mt-4">
                        <img src="{{ asset('images/icone_tourfacil.png') }}" width="85" alt="User" class="avatar d-block mx-auto">
                    </div>
                    <h3 class="text-center mt-4 mb-2">{{ $dados->name }}</h3>
                    <p class="text-center text-truncate">{{ $dados->email }}</p>
                    <p class="text-center">Cadastrado em {{ $dados->created_at->format('d/m/Y') }}</p>
                    <div class="em-separator separator-dashed"></div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('app.meus-dados') }}"><i class="la la-briefcase la-2x align-middle pr-3"></i>Meus dados</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('app.alterar-senha') }}"><i class="la la-lock la-2x align-middle pr-3"></i>Alterar senha</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('app.user.configuracoes') }}"><i class="la la-cog la-2x align-middle pr-3"></i>Configurações</a>
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
                    <form data-validate-ajax method="POST" action="{{ route('app.meus-dados.atualizar') }}" class="form-horizontal">
                        <div class="form-group row d-flex align-items-center mb-5">
                            <label for="name" class="col-lg-2 form-control-label d-flex justify-content-lg-end">Nome</label>
                            <div class="col-lg-6">
                                <input id="name" type="text" required class="form-control" placeholder="Nome completo" value="{{ $dados->name }}"
                                       name="name" data-required title="Nome completo" data-min="3" data-auto-capitalize>
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
                            <label for="phone" class="col-lg-2 form-control-label d-flex justify-content-lg-end">Telefone</label>
                            <div class="col-lg-6">
                                <input id="phone" type="tel" required class="form-control vanillaMask" placeholder="(99) 9 9999-9999"
                                       name="phone" data-required title="Telefone" data-min="14" data-mask="phone" value="{{ $dados->phone }}">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="form-group row d-flex align-items-center mb-5">
                            <label for="birthdate" class="col-lg-2 form-control-label d-flex justify-content-lg-end">Nascimento</label>
                            <div class="col-lg-6">
                                <input id="birthdate" type="tel" required class="form-control vanillaMask" placeholder="DD/MM/AAAA"
                                       name="birthdate" data-required title="Nascimento" data-min="10" data-mask="date" value="{{ (is_object($dados->birthdate)) ? $dados->birthdate->format('d/m/Y') : "" }}">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-10 ml-auto">
                            <div class="section-title mt-3 mb-3">
                                <h4>02. Informações de endereço</h4>
                            </div>
                        </div>
                        <div class="form-group row d-flex align-items-center mb-5">
                            <label for="address" class="col-lg-2 form-control-label d-flex justify-content-lg-end">Endereço</label>
                            <div class="col-lg-6">
                                <input id="address" type="text" required class="form-control" placeholder="Rua, avenida, travessa e etc."
                                       name="address" data-required title="Endereço" data-min="3" data-auto-capitalize value="{{ $dados->address }}">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="form-group row d-flex align-items-center mb-5">
                            <label for="city" class="col-lg-2 form-control-label d-flex justify-content-lg-end">Cidade</label>
                            <div class="col-lg-6">
                                <input id="city" type="text" required class="form-control" placeholder="Gramado ou Canela"
                                       name="city" data-required title="Cidade" data-min="3" data-auto-capitalize value="{{ $dados->city }}">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="form-group row d-flex align-items-center mb-5">
                            <label for="state" class="col-lg-2 form-control-label d-flex justify-content-lg-end">Estado</label>
                            <div class="col-lg-6">
                                <input id="state" type="text" required class="form-control" placeholder="Rio Grande do Sul"
                                       name="state" data-required title="Estado" data-min="2" data-auto-capitalize value="{{ $dados->state }}">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="form-group row d-flex align-items-center mb-5">
                            <label for="zip" class="col-lg-2 form-control-label d-flex justify-content-lg-end">CEP</label>
                            <div class="col-lg-6">
                                <input id="zip" type="tel" required class="form-control vanillaMask" placeholder="00000-000"
                                       name="zip" data-required title="CEP" data-min="9" data-mask="cep" value="{{ $dados->zip }}">
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
