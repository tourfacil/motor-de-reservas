@extends('template.master')

@section('title', "Cálculo de porcentagem")

@section('body')

    <div class="container rounded-widget" data-controller="CalculadoraCtrl">
        <div class="row">
            <div class="col-12">
                <div class="widget has-shadow mt-3">
                    <div class="widget-header bordered no-actions d-flex align-items-center">
                        <h1 class="m-auto">Cálculo de porcentagem</h1>
                    </div>
                    <div class="widget-body">
                        <form name="calcular" method="POST" action="{{ route('app.servicos.store.descricao') }}" class="form-horizontal">
                            <div class="form-group row mb-0">
                                <div class="col-12">
                                    <div class="alert alert-secondary mb-4" role="alert">
                                        <i class="la la-info-circle mr-2"></i>
                                        Informe os valores nos campos abaixo
                                    </div>
                                </div>
                                <div class="col-12 mb-4">
                                    <label for="net_servico" class="form-control-label">R$ net base do serviço &nbsp;<small>(Net adulto ou o maior valor net)</small></label>
                                    <div class="input-group">
                                        <span class="input-group-addon addon-secondary left">R$</span>
                                        <input id="net_servico" type="tel" class="form-control vanillaMaskMoney" placeholder="0,00" required
                                               data-required data-min="5" title="Valor net do serviço" name="net_servico">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="alert alert-outline-secondary mb-3" role="alert">
                                        <i class="la la-balance-scale mr-2"></i>
                                        O cálculo do valor markup é (100 ÷ (100 - comissao))
                                    </div>
                                </div>
                                <div class="col-12 mb-4">
                                    <label for="net_variacao" class="form-control-label">Valor net da VARIAÇÃO</label>
                                    <div class="input-group">
                                        <span class="input-group-addon addon-secondary left">R$</span>
                                        <input id="net_variacao" type="tel" class="form-control vanillaMaskMoney" placeholder="0,00" required
                                               data-required data-min="5" title="Valor net da variação" name="net_variacao">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="comissao" class="form-control-label">Porcentagem da comissão para ESSA VARIAÇÃO</label>
                                    <div class="input-group">
                                        <span class="input-group-addon addon-secondary left">%</span>
                                        <input id="comissao" type="tel" class="form-control vanillaPorcentage" placeholder="000.00" required
                                               data-required data-min="5" title="Comissão" name="comissao" maxlength="6">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="hide">
                                    <input type="hidden" name="to" value="{{ $to_input }}">
                                </div>
                                <div class="col-12">
                                    <div class="text-center">
                                        <button class="btn btn-gradient-01" type="submit">Calcular porcentagem</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection