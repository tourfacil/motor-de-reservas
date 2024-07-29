@extends('template.header')

@section('title', 'Configurações do usuário')

@section('content')

    <div class="row">
        <div class="page-header">
            <div class="d-flex align-items-center">
                <h2 class="page-header-title text-gradient-01">Configurações do usuário</h2>
                <div>{{ Breadcrumbs::render('app.user.configuracoes') }}</div>
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
                    <h4>Dados de acesso</h4>
                </div>
                <div class="widget-body">
                    <div class="alert alert-secondary square mb-4" role="alert">Não é permitido alterar o nível de acesso.</div>
                    <form data-validate-ajax method="POST" action="{{ route('app.user.configuracoes.atualizar') }}" class="form-horizontal mt-5">
                        <div class="form-group row d-flex align-items-center mb-5">
                            <label for="nivel_acesso" class="col-lg-2 form-control-label d-flex justify-content-lg-end">Nível de acesso</label>
                            <div class="col-lg-6">
                                <input id="nivel_acesso" type="text" class="form-control" value="{{ $dados->nivel_acesso }}" title="Nível de acesso" disabled>
                                <small>O nível pode ser alterado na área de colaboradores.</small>
                            </div>
                        </div>
                        <div class="form-group row d-flex align-items-center mb-5">
                            <label for="canal_venda_id" class="col-lg-2 form-control-label d-flex justify-content-lg-end">Canal padrão</label>
                            <div class="col-lg-6">
                                <select id="canal_venda_id" name="canal_venda_id" class="custom-select form-control" required
                                        data-required title="Canal venda padrão">
                                    <option value="" disabled selected>Selecione um canal</option>
                                    @foreach($canais_composer as $canal)
                                        <option value="{{ $canal->id }}" {{ ($canal->id == $dados->canal_venda_id) ? "selected" : "" }}>{{ $canal->site }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback"></div>
                                <small>Informe um canal para ser o seu padrão ao logar no sistema.</small>
                            </div>
                        </div>
                        <div class="form-group row d-flex align-items-center mb-5">
                            <label for="pagina_padrao" class="col-lg-2 form-control-label d-flex justify-content-lg-end">Após login</label>
                            <div class="col-lg-6">
                                <select id="pagina_padrao" name="pagina_padrao" class="custom-select form-control" required
                                        data-required title="Página padrão">
                                    @foreach($pages as $page => $name_page)
                                        <option value="{{ $page }}" {{ ($page == $dados->pagina_padrao) ? "selected" : "" }}>Ir para {{ $name_page }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback"></div>
                                <small>Informe uma página de destino para ser redirecionado após o login.</small>
                            </div>
                        </div>
                        <div class="hide">{{ method_field("PUT") }}</div>
                        <div class="em-separator separator-dashed"></div>
                        <div class="text-center">
                            <button class="btn btn-gradient-01 btn-shadow" type="submit">Salvar configurações</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
