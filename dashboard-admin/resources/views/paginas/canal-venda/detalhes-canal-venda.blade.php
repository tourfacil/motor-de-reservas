@extends('template.header')

@section('title', "Canal {$canal_venda->nome}")

@section('content')

    <div class="row">
        <div class="page-header">
            <div class="d-flex align-items-center">
                <h2 class="page-header-title text-gradient-01">{{ $canal_venda->nome }}</h2>
                <div>{{ Breadcrumbs::render('app.canal-venda.view') }}</div>
            </div>
        </div>
    </div>

    @if($canal_venda->status === false)
        <div class="row">
            <div class="col-12">
                <div class="alert alert-warning alert-lg square" role="alert">
                    <strong>Canal desativado.</strong> Para editá-lo você deve ativar o canal primeiro!
                </div>
            </div>
        </div>
    @endif

    <div class="row" data-controller="CanalVendaCtrl">
        <div class="col-12">
            <div class="widget has-shadow">
                <div class="widget-header bordered no-actions d-flex align-items-center">
                    <h4 class="mr-auto">Cadastrado em {{ $canal_venda->created_at->format('d/m/Y H:i') }}</h4>
                    <span>{{ $canal_venda->uuid }}</span>
                </div>
                <div class="widget-body">
                    <div class="col-lg-8 ml-auto">
                        <div class="section-title mt-3 mb-3">
                            <h4>01. Informações sobre o canal</h4>
                        </div>
                    </div>
                    <form data-validate-ajax method="POST" action="{{ route('app.canal-venda.edit') }}" class="form-horizontal">
                        <div class="form-group row d-flex align-items-center mb-5">
                            <label for="nome" class="col-lg-4 form-control-label d-flex justify-content-lg-end">Nome</label>
                            <div class="col-lg-5">
                                <input id="nome" type="text" required class="form-control" placeholder="Nome do canal de venda" value="{{ $canal_venda->nome }}"
                                       name="nome" data-required title="Nome do canal de venda" data-min="3" data-auto-capitalize {{ ($canal_venda->status) ? "" : "readonly" }}>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="form-group row d-flex align-items-center mb-5">
                            <label for="site" class="col-lg-4 form-control-label d-flex justify-content-lg-end">URL do canal</label>
                            <div class="col-lg-5">
                                <input id="site" type="text" required class="form-control" placeholder="canalvenda.com.br" {{ ($canal_venda->status) ? "" : "readonly" }}
                                       name="site" data-required title="URL de acesso do canal" data-min="3" value="{{ $canal_venda->site }}">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-lg-8 ml-auto">
                            <div class="section-title mt-3 mb-3">
                                <h4>02. Informações sobre o pagamento</h4>
                            </div>
                        </div>
                        <div class="form-group row d-flex align-items-center mb-5">
                            <label for="maximo_parcelas" class="col-lg-4 form-control-label d-flex justify-content-lg-end">Máx. parcelas</label>
                            <div class="col-lg-5">
                                <select id="maximo_parcelas" name="maximo_parcelas" class="custom-select form-control" required
                                        data-required title="Quantidade máxima de parcelas" {{ ($canal_venda->status) ? "" : "readonly" }}>
                                    @for($parcela = 1; $parcela <= 12; $parcela++)
                                        <option value="{{ $parcela }}" {{ ($canal_venda->maximo_parcelas == $parcela) ? "selected" : "" }}>Parcelamento até {{ $parcela }}x</option>
                                    @endfor
                                </select>
                                <div class="invalid-feedback"></div>
                                <small>Quantidade máxima de parcelamento disponível no site.</small>
                            </div>
                        </div>
                        <div class="form-group row d-flex align-items-center mb-5">
                            <label for="parcelas_sem_juros" class="col-lg-4 form-control-label d-flex justify-content-lg-end">Parcelas sem juros</label>
                            <div class="col-lg-5">
                                <select id="parcelas_sem_juros" name="parcelas_sem_juros" class="custom-select form-control" required
                                        data-required title="Quantidade de parcelas sem juros" {{ ($canal_venda->status) ? "" : "readonly" }}>
                                    @for($parcela = 1; $parcela <= 12; $parcela++)
                                        <option value="{{ $parcela }}" {{ ($canal_venda->parcelas_sem_juros == $parcela) ? "selected" : "" }}>Até {{ $parcela }}x sem juros</option>
                                    @endfor
                                </select>
                                <div class="invalid-feedback"></div>
                                <small>Quantidade de parcelas sem juros.</small>
                            </div>
                        </div>
                        <div class="form-group row d-flex align-items-center mb-4">
                            <label for="juros_parcela" class="col-lg-4 form-control-label d-flex justify-content-lg-end">Juros por parcela</label>
                            <div class="col-lg-5">
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon addon-secondary">
                                            <i class="la la-credit-card"></i>
                                        </span>
                                        <input id="juros_parcela" type="tel" required class="form-control vanillaMask" placeholder="0,0%" value="{{ $canal_venda->juros_parcela }}"
                                               name="juros_parcela" data-required title="Juros por parcela" data-min="2" data-mask="porcent" {{ ($canal_venda->status) ? "" : "readonly" }}>
                                    </div>
                                    <div class="invalid-feedback"></div>
                                    <small>Porcentagem de juros somados por parcela.</small>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row d-flex align-items-center mb-4">
                            <label class="col-sm-12 col-lg-4 form-control-label d-flex justify-content-lg-end">Status do canal</label>
                            <div class="col-6 col-md-2">
                                <label>
                                    <input name="disable" class="toggle-checkbox" type="checkbox" {{ ($canal_venda->status) ? "checked" : "" }}>
                                    <span><span></span></span>
                                </label>
                            </div>
                            <div class="col-6 col-md-3 text-right">
                                <button type="button" data-action="reset_cache" data-canal="{{ $canal_venda->id }}" data-url="{{ route('app.canal-venda.reset-cache') }}"
                                        class="btn btn-gradient-02">Limpar cache <i class="la la-refresh right"></i></button>
                            </div>
                        </div>
                        <div class="hidden">
                            <input type="hidden" name="canal_venda_id" value="{{ $canal_venda->id }}">
                            {{ method_field("PUT") }}
                        </div>
                        <div class="em-separator separator-dashed"></div>
                        <div class="text-center">
                            <button class="btn btn-gradient-01" type="submit">Atualizar cadastro <i class="la la-angle-right right"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
