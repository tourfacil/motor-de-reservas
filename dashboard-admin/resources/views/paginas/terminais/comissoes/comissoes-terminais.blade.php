@extends('template.header')

@section('title', 'Pagamento de comissões Terminais CDI')

@section('content')

    <div class="row" data-controller="ComissoesTerminalCtrl">
        <div class="page-header">
            <div class="d-flex align-items-center">
                <h2 class="page-header-title text-dark">Pagamento de comissões <span class="text-gradient-01">{{ $url_terminais }}</span></h2>
                <div>{{ Breadcrumbs::render('app.terminais.comissoes.index') }}</div>
            </div>
        </div>
    </div>

    <div class="row flex-row">
        <div class="col-xl-3 col-md-6">
            <div class="widget has-shadow">
                <div class="widget-body">
                    <div class="row align-items-center">
                        <div class="col-xl-2 d-flex">
                            <i class="la la-calendar la-2x"></i>
                        </div>
                        <div class="col d-flex align-items-center">
                            <div class="content-widget">
                                <p class="m-0 text-primary">Período das vendas</p>
                                <strong class="text-secondary">{{ mesPT($mes_referencia->month) }} de {{ $mes_referencia->year }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="widget has-shadow">
                <div class="widget-body">
                    <div class="row align-items-center">
                        <div class="col-xl-2 d-flex">
                            <i class="la la-cart-plus la-2x"></i>
                        </div>
                        <div class="col d-flex align-items-center">
                            <div class="content-widget">
                                <p class="m-0 text-primary">R$ Total comissão</p>
                                <strong class="text-success">+R$ {{ formataValor($total_comissao) }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="widget has-shadow">
                <div class="widget-body">
                    <div class="row align-items-center">
                        <div class="col-xl-2 d-flex">
                            <i class="la la-line-chart la-2x"></i>
                        </div>
                        <div class="col d-flex align-items-center">
                            <div class="content-widget">
                                <p class="m-0 text-primary">R$ Total pago</p>
                                <strong class="text-warning">R$ {{ formataValor($total_pago) }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="widget has-shadow">
                <div class="widget-body">
                    <div class="row align-items-center">
                        <div class="col-xl-2 d-flex">
                            <i class="la la-desktop la-2x"></i>
                        </div>
                        <div class="col d-flex align-items-center">
                            <div class="content-widget">
                                <p class="m-0 text-primary">Terminais com venda</p>
                                <strong class="text-info">{{ $relatorio->count() }} Terminais</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="widget has-shadow">
                <div class="widget-header p-3 bordered">
                    <div class="row d-flex align-items-center">
                        <div class="col-7">
                            <h4 class="mr-auto pl-2">Lista dos terminais que tiveram vendas</h4>
                        </div>
                        <div class="col-2 pr-0 text-right">
                            <label for="periodo_vendas_filtro" class="m-auto text-primary">Mês de pagamento</label>
                        </div>
                        <div class="col-3">
                            <select name="periodo_vendas" id="periodo_vendas_filtro" title="Período de vendas"
                                    class="form-control d-inline" data-route="{{ route('app.terminais.comissoes.index') }}">
                                @for($i = $max_month; $i >= 0; $i--)
                                    @php($mes_filtro = $lista_mes->format('m-Y'))
                                    @if($mes_filtro == $mes_pesquisa)
                                        <option value="{{ $mes_filtro }}" selected>{{ mesPT($lista_mes->month) }} de {{ $lista_mes->year }}</option>
                                    @else
                                        <option value="{{ $mes_filtro }}">{{ mesPT($lista_mes->month) }} de {{ $lista_mes->year }}</option>
                                    @endif
                                    @php($lista_mes->addMonths(1))
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>
                <div class="widget-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-border-bottom mb-0 data-table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Terminal</th>
                                <th>Quantidade</th>
                                <th>Comissão</th>
                                <th>R$ Pago</th>
                                <th class="text-center">Administração</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($relatorio as $comissao_terminal)
                                <tr>
                                    <td width="10" class="text-center">{{ $loop->iteration }}</td>
                                    <td>
                                        <a href="{{ route('app.terminais.view', $comissao_terminal->terminal_id) }}" target="_blank" class="text-primary">
                                            <strong>{{ $comissao_terminal->terminal->nome }}</strong>
                                        </a>
                                    </td>
                                    <td class="text-primary">{{ $comissao_terminal->comissoesPagamento->sum('quantidade') }} ingresso(s)</td>
                                    <td class="text-success">
                                        <strong>R$ {{ formataValor($comissao_terminal->total_comissao) }}</strong>
                                    </td>
                                    <td class="text-warning">
                                        <strong>R$ {{ formataValor($comissao_terminal->total_pago) }}</strong>
                                    </td>
                                    <td class="text-center">
                                        @if($comissao_terminal->total_pago > 0)
                                            <a href="{{ route('app.terminais.comissoes.view', $comissao_terminal->id) }}" data-action="detailPayment" class="btn btn-outline-primary">
                                                Detalhes <i class="la la-external-link right"></i>
                                            </a>
                                        @else
                                            <a href="{{ route('app.terminais.comissoes.view', $comissao_terminal->id) }}" data-action="makePayment" class="btn btn-outline-primary">
                                                Pagar agora <i class="la la-angle-right right"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal para detalhes do pagamento --}}
    <div id="view-pagamento" class="modal modal-top fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Comissão terminal</h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">×</span>
                        <span class="sr-only">close</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form data-validate-ajax method="POST" action="{{ route('app.terminais.comissoes.update') }}" class="form-horizontal">
                        <div class="form-group row mb-0">
                            <div class="col-xl-12 mb-3 text-grey-dark">
                                <strong>Terminal</strong>
                                <p id="nome_terminal" class="m-0"></p>
                            </div>
                            <div class="col-xl-6 mb-3 text-grey-dark">
                                <strong>Período de venda</strong>
                                <p id="periodo_venda" class="m-0">{{ mesPT($mes_referencia->month) }} de {{ $mes_referencia->year }}</p>
                            </div>
                            <div class="col-xl-6 mb-3 text-grey-dark">
                                <strong>R$ Total comissão</strong>
                                <p id="valor_comissao" class="m-0"></p>
                            </div>
                            <div class="col-xl-6 mb-3">
                                <label for="valor_pago" class="form-control-label">R$ Valor pago</label>
                                <div class="input-group">
                                    <span class="input-group-addon addon-secondary left"><i class="la la-dollar"></i></span>
                                    <input id="valor_pago" type="tel" class="form-control vanillaMaskMoney" placeholder="0,00"
                                           title="Valor pago" name="valor_pago" required data-required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-xl-6 mb-3">
                                <label for="valor_pago" class="form-control-label">Data do pagamento</label>
                                <div class="input-group">
                                    <span class="input-group-addon addon-secondary left"><i class="la la-calendar"></i></span>
                                    <input id="valor_pago" type="tel" class="form-control vanillaMask" placeholder="DD/MM/AAAA"
                                           title="Data do pagamento" name="data_pagamento" required data-required data-mask="date" value="{{ date('d/m/Y') }}">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="hide">
                            {{ method_field("PUT") }}
                            <input type="hidden" name="pagamento_id">
                        </div>
                        <div class="em-separator separator-dashed mt-1"></div>
                        <div class="mt-3 text-center">
                            <button class="btn btn-success">Efetuar pagamento <i class="la la-save right"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal para detalhes do pagamento --}}
    <div id="detalhe-pagamento" class="modal modal-top fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Comissão terminal</h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">×</span>
                        <span class="sr-only">close</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row mb-0">
                        <div class="col-xl-12 mb-3 text-grey-dark">
                            <strong>Terminal</strong>
                            <p id="nome_terminal_detalhes" class="m-0"></p>
                        </div>
                        <div class="col-xl-6 mb-3 text-grey-dark">
                            <strong>Período de venda</strong>
                            <p id="periodo_venda_detalhes" class="m-0">{{ mesPT($mes_referencia->month) }} de {{ $mes_referencia->year }}</p>
                        </div>
                        <div class="col-xl-6 mb-3 text-grey-dark">
                            <strong>R$ Total comissão</strong>
                            <p id="valor_comissao_detalhes" class="m-0">R$ 0,00</p>
                        </div>
                        <div class="col-xl-6 mb-3 text-grey-dark">
                            <strong>R$ Total pago</strong>
                            <p id="valor_pago_detalhes" class="m-0">R$ 0,00</p>
                        </div>
                        <div class="col-xl-6 mb-3 text-grey-dark">
                            <strong>Data pagamento</strong>
                            <p id="data_pagamento_detalhes" class="m-0"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
