@extends('template.header')

@section('title', 'Administração de reservas')

@section('content')

    <div class="row">
        <div class="page-header">
            <div class="d-flex align-items-center">
                <h2 class="page-header-title text-dark">Administração de <span class="text-gradient-01">reservas</span></h2>
                <div>{{ Breadcrumbs::render('app.reservas.index') }}</div>
            </div>
        </div>
    </div>

    <div class="row flex-row" data-controller="VendasCtrl">
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
                                <strong class="text-secondary">{{ $pp_start->format('d/m') }} até {{ $pp_end->format('d/m') }}</strong>
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
                                <p class="m-0 text-primary">Total vendido</p>
                                <strong class="text-success">R$ {{ formataValor($dados_vendas['valor_vendido']) }}</strong>
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
                            <i class="la la-tags la-2x"></i>
                        </div>
                        <div class="col d-flex align-items-center">
                            <div class="content-widget">
                                <p class="m-0 text-primary">Ingressos vendidos</p>
                                <strong class="text-info">{{ $dados_vendas['quantidade'] }} Ingresso(s)</strong>
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
                            <i class="la la-rotate-left la-2x"></i>
                        </div>
                        <div class="col d-flex align-items-center">
                            <div class="content-widget">
                                <p class="m-0 text-primary">R$ Valor estornado</p>
                                <strong class="text-danger">-R$ {{ formataValor($dados_vendas['valor_estornado']) }}</strong>
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
                            <h4 class="pl-2 text-primary">Listagem das reservas</h4>
                        </div>
                        <div class="col-2 pr-0 text-right">
                            <label for="periodo_vendas_filtro" class="m-auto text-primary">Período de vendas</label>
                        </div>
                        <div class="col-3">
                            <select name="periodo_vendas" id="periodo_vendas_filtro" title="Período de vendas"
                                    class="form-control d-inline" data-route="{{ route('app.reservas.index') }}">
                                @foreach ($periodo_pesquisa as $key => $periodo)
                                    @if($filtro_selecionado == $key)
                                        <option value="{{ $key }}" selected>{{ $periodo['nome'] }}</option>
                                    @else
                                        <option value="{{ $key }}">{{ $periodo['nome'] }}</option>
                                    @endif
                                @endforeach
                                @if($filtro_selecionado == "custom")
                                    <option selected>{{ $pp_start->format('d/m/Y') }} até {{ $pp_end->format('d/m/Y') }}</option>
                                @endif
                                <option value="custom">Personalizado</option>
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
                                <th>Dados comprador</th>
                                <th>Informações da venda</th>
                                <th class="text-center">R$ Tarifa NET</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Mais detalhes</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($dados_vendas['reservas'] as $reserva)
                                <tr>
                                    <td width="5" class="text-center">{{ $loop->iteration }}</td>
                                    <td data-label="Dados comprador" class="text-primary">
                                        <p class="text-truncate mt-2 mb-0">
                                            <strong class="pr-1">{{ Str::limit($reserva->pedido->cliente->nome, 30) }}</strong>
                                        </p>
                                        <p class="text-truncate mt-0 mb-2">{{ Str::limit($reserva->pedido->cliente->email, 30) }}</p>
                                    </td>
                                    <td class="text-primary">
                                        <p class="mb-2 mt-0">
                                            <strong>Reserva</strong>
                                            <a href="{{ route('app.reservas.view', $reserva->voucher) }}" target="_blank" class="text-secondary">#{{ $reserva->voucher }}</a>
                                            <strong class="pl-2">Utilização</strong> {{ $reserva->agendaDataServico->data->format('d/m/Y') }}
                                        </p>
                                        <p class="mt-2 mb-0 text-truncate">
                                            <span class="pr-1">({{ $reserva->quantidade }}x)</span>
                                            {{ $reserva->servico->nome }}
                                        </p>
                                    </td>
                                    <td class="text-center">
                                        <strong class="text-{{ $reserva->cor_status }}">
                                            R$ {{ formataValor($reserva->valor_net) }}
                                        </strong>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge-text badge-text-small {{ $reserva->cor_status }}">
                                            {{ $reserva->status_reserva }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('app.reservas.view', $reserva->voucher) }}" target="_blank" class="btn btn-outline-secondary">
                                            Detalhes <i class="la la-external-link right"></i>
                                        </a>
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

    {{-- Modal para procurar a reserva --}}
    <div id="search-reserva" class="modal modal-top fade ">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Procurar reserva</h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">×</span>
                        <span class="sr-only">close</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row mb-0">
                        <div class="col-12">
                            <ul class="nav nav-pills nav-tabs">
                                <li class="nav-item">
                                    <a class="nav-link not-save-state active show" data-toggle="tab" href="#cod_reserva">Código da reserva</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link not-save-state" data-toggle="tab" href="#nome_email">Dados do cliente</a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-xl-12 mb-3 tab-content pt-4">
                            <div class="tab-content">
                                <div class="tab-pane active show" id="cod_reserva">
                                    <div class="input-group">
                                        <input id="find_reserva" type="search" class="form-control" placeholder="Código da reserva"
                                               title="Pesquise pelo código da reserva" name="q" required data-required data-min="2" data-route="{{ route('app.reservas.search') }}">
                                        <span class="input-group-addon addon-secondary right"><i class="la la-search"></i></span>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="nome_email">
                                    <div class="input-group">
                                        <input id="cpf_email" type="search" class="form-control" placeholder="Nome, CPF ou E-mail"
                                               title="Pesquise o cliente aqui" name="q" required data-required data-min="2" data-route="{{ route('app.reservas.search.cliente') }}">
                                        <span class="input-group-addon addon-secondary right"><i class="la la-search"></i></span>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="em-separator separator-dashed mt-2 mb-2"></div>
                    <div class="list-result widget-18">
                        <ul class="list-group w-100 list-results"></ul>
                    </div>
                    <div class="start-result">
                        <p class="text-center mt-3 mb-3">Os resultados aparecerão aqui...</p>
                    </div>
                    <div class="not-result hide">
                        <p class="text-center mt-3 mb-3">Nenhum resultado encontrado!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="float-button">
        <button data-target="#search-reserva" data-toggle="modal" class="btn btn-gradient-01 btn-shadow"><i class="ion ion-search"></i></button>
        <p class="float-tooltip">Procurar reserva</p>
    </div>

@endsection
