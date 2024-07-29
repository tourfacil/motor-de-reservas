@extends('template.header')

@section('title', 'Novo canal de venda')

@section('content')

    <div class="row">
        <div class="page-header">
            <div class="d-flex align-items-center">
                <h2 class="page-header-title text-gradient-01">Novo canal de vendas</h2>
                <div>{{ Breadcrumbs::render('app.canal-venda.create') }}</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="widget has-shadow">
                <div class="widget-header bordered no-actions d-flex align-items-center">
                    <h4 class="mr-auto">Preencha o formulário abaixo.</h4>
                </div>
                <div class="widget-body">
                    <div class="col-lg-8 ml-auto">
                        <div class="section-title mt-3 mb-3">
                            <h4>01. Informações sobre o canal</h4>
                        </div>
                    </div>
                    <form data-validate-ajax method="POST" action="{{ route('app.canal-venda.store') }}" class="form-horizontal">
                        <div class="form-group row d-flex align-items-center mb-5">
                            <label for="nome" class="col-lg-4 form-control-label d-flex justify-content-lg-end">Nome</label>
                            <div class="col-lg-5">
                                <input id="nome" type="text" required class="form-control" placeholder="Nome do canal de venda"
                                       name="nome" data-required title="Nome do canal de venda" data-min="3" data-auto-capitalize>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="form-group row d-flex align-items-center mb-5">
                            <label for="site" class="col-lg-4 form-control-label d-flex justify-content-lg-end">URL do canal</label>
                            <div class="col-lg-5">
                                <input id="site" type="text" required class="form-control" placeholder="canalvenda.com.br"
                                       name="site" data-required title="URL de acesso do canal" data-min="3">
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
                                        data-required title="Quantidade máxima de parcelas">
                                    <option value="" disabled selected>Selecione a quantidade</option>
                                    @for($parcela = 1; $parcela <= 12; $parcela++)
                                        <option value="{{ $parcela }}">Parcelamento até {{ $parcela }}x</option>
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
                                        data-required title="Quantidade de parcelas sem juros">
                                    <option value="" disabled selected>Selecione a quantidade</option>
                                    @for($parcela = 1; $parcela <= 12; $parcela++)
                                        <option value="{{ $parcela }}">Até {{ $parcela }}x sem juros</option>
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
                                        <input id="juros_parcela" type="tel" required class="form-control vanillaMask" placeholder="0,0%"
                                               name="juros_parcela" data-required title="Juros por parcela" data-min="2" data-mask="porcent">
                                    </div>
                                    <div class="invalid-feedback"></div>
                                    <small>Porcentagem de juros somados por parcela.</small>
                                </div>
                            </div>
                        </div>
                        <div class="em-separator separator-dashed"></div>
                        <div class="text-center">
                            <button class="btn btn-gradient-01" type="submit">Finalizar cadastro <i class="la la-angle-right right"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection