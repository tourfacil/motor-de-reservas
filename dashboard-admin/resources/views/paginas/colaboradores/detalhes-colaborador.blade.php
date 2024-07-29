@extends('template.header')

@section('title', "Colaborador " . $colaborador->name)

@section('content')

    <div class="row">
        <div class="page-header">
            <div class="d-flex align-items-center">
                <h2 class="page-header-title text-gradient-01">{{ $colaborador->name }}</h2>
                <div>{{ Breadcrumbs::render('app.colaboradores.view') }}</div>
            </div>
        </div>
    </div>

    @if($colaborador->status === false)
        <div class="row">
            <div class="col-12">
                <div class="alert alert-warning alert-lg square" role="alert">
                    <strong>Colaborador desativado.</strong> Para editá-lo você deve ativar o colaborador primeiro!
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="widget has-shadow">
                <div class="widget-body pl-lg-5 pr-lg-5 pb-2">
                    <div class="row">
                        <div class="col-xl-8 mt-4 mb-3">
                            <div class="section-title mr-auto">
                                <i class="la la-user la-3x pull-left pr-4"></i>
                                <h3>Informações pessoais</h3>
                                <p class="mt-1">Cadastrado no dia {{ $colaborador->created_at->format('d/m/Y H:i') }}.</p>
                            </div>
                        </div>
                        <div class="col-xl-4 mt-3 mb-4 d-flex align-items-center">
                            <h2 class="ml-auto mb-0">#{{ $colaborador->id }}</h2>
                        </div>
                    </div>
                    <form data-validate-ajax method="POST" action="{{ route('app.meus-dados.atualizar') }}" class="form-horizontal">
                        <div class="form-group row mb-0">
                            <div class="col-xl-6 mb-4">
                                <label for="name" class="form-control-label">Nome completo</label>
                                <input id="name" type="text" class="form-control" placeholder="Nome e sobrenome" value="{{ $colaborador->name }}"
                                       title="Nome do colaborador" name="name" required data-required data-min="4" data-auto-capitalize {{ ($colaborador->status) ? "" : "readonly" }}>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-xl-6 mb-4">
                                <label for="email" class="form-control-label">Email de acesso <small>&nbsp; (O e-mail deve ser único)</small></label>
                                <input id="email" type="email" class="form-control" placeholder="nome.sobrenome@email.com.br" {{ ($colaborador->status) ? "" : "readonly" }}
                                       title="Email de acesso" name="email" required data-required data-min="4" value="{{ $colaborador->email }}">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-xl-4 mb-4">
                                <label for="level" class="form-control-label">Nível de acesso</label>
                                <select id="level" name="level" class="form-control boostrap-select-custom" required
                                        data-required title="Selecione um nível">
                                    @foreach($nivels_acesso as $key => $nivel)
                                        @if($colaborador->level === $key)
                                            <option value="{{ $key }}" selected>{{ $nivel }}</option>
                                        @else
                                            <option value="{{ $key }}">{{ $nivel }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-xl-4 mb-4">
                                <label for="phone" class="form-control-label">Telefone para contato</label>
                                <input id="phone" type="tel" class="form-control vanillaMask" placeholder="(99) 9 9999-9999" {{ ($colaborador->status) ? "" : "readonly" }}
                                       name="phone" title="Telefone" data-min="14" data-mask="phone" value="{{ $colaborador->phone }}">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-xl-4 mb-4">
                                <label for="birthdate" class="form-control-label">Data de nascimento</label>
                                <input id="birthdate" type="tel" class="form-control vanillaMask" placeholder="DD/MM/AAAA" {{ ($colaborador->status) ? "" : "readonly" }}
                                       name="birthdate" title="Nascimento" data-min="10" data-mask="date" value="{{ (is_object($colaborador->birthdate)) ? $colaborador->birthdate->format('d/m/Y') : "" }}">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="em-separator separator-dashed"></div>
                        <div class="row">
                            <div class="col-xl-12 mt-4 mb-3">
                                <div class="section-title mr-auto">
                                    <i class="la la-map la-3x pull-left pr-4"></i>
                                    <h3>Informações de endereço</h3>
                                    <p class="mt-1">Dados onde o colaborador reside.</p>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-xl-12 mb-4">
                                <label for="address" class="form-control-label">Endereço</label>
                                <input id="address" type="text" class="form-control" placeholder="Rua, avenida, travessa e etc."
                                       name="address" title="Endereço" data-min="3" data-auto-capitalize value="{{ $colaborador->address }}">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-xl-4 mb-4">
                                <label for="city" class="form-control-label">Cidade</label>
                                <input id="city" type="text" class="form-control" placeholder="Gramado ou Canela"
                                       name="city" title="Cidade" data-min="3" data-auto-capitalize value="{{ $colaborador->city }}">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-xl-4 mb-4">
                                <label for="state" class="form-control-label">Estado</label>
                                <input id="state" type="text" class="form-control" placeholder="Rio Grande do Sul"
                                       name="state" title="Estado" data-min="2" data-auto-capitalize value="{{ $colaborador->state }}">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-xl-4 mb-4">
                                <label for="zip" class="form-control-label">CEP</label>
                                <input id="zip" type="tel" class="form-control vanillaMask" placeholder="00000-000"
                                       name="zip" title="CEP" data-min="9" data-mask="cep" value="{{ $colaborador->zip }}">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="hide">
                            {{ method_field("PUT") }}
                        </div>
                        <div class="em-separator separator-dashed"></div>
                        <div class="text-center mb-3">
                            <button class="btn btn-gradient-01 btn-shadow" type="submit">Salvar alterações</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
