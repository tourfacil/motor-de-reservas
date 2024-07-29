@extends('template.header')

@section('title', 'Pedidos do ' . $canal_venda->nome)

@section('content')

    <div class="row">
        <div class="page-header">
            <div class="d-flex align-items-center">
                <h2 class="page-header-title text-dark">Pedidos do <span class="text-gradient-01">{{ $canal_venda->nome }}</span></h2>
                <div>{{ Breadcrumbs::render('app.pedidos.index') }}</div>
            </div>
        </div>
    </div>

    <div class="row flex-row">
        <div class="col-xl-3 col-md-6">
            <div class="widget has-shadow">
                <div class="widget-body">
                    <div class="row align-items-center">
                        <div class="col-2 d-flex">
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
                        <div class="col-2 d-flex">
                            <i class="la la-cart-plus la-2x"></i>
                        </div>
                        <div class="col d-flex align-items-center">
                            <div class="content-widget">
                                <p class="m-0 text-primary">R$ Total vendido</p>
                                <strong class="text-success">+R$ {{ formataValor($dados_reservas['valor_vendido']) }}</strong>
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
                        <div class="col-2 d-flex">
                            <i class="la la-tags la-2x"></i>
                        </div>
                        <div class="col d-flex align-items-center">
                            <div class="content-widget">
                                <p class="m-0 text-primary">R$ Valor pendente</p>
                                <strong class="text-warning">R$ {{ formataValor($dados_reservas['valor_pendente']) }}</strong>
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
                        <div class="col-2 d-flex">
                            <i class="la la-rotate-left la-2x"></i>
                        </div>
                        <div class="col d-flex align-items-center">
                            <div class="content-widget">
                                <p class="m-0 text-primary">R$ Valor estornado</p>
                                <strong class="text-danger">-R$ {{ formataValor($dados_reservas['valor_estornado']) }}</strong>
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
                        <div class="col-12 col-md-7">
                            <h4 class="pl-2 text-primary">Listagem dos pedidos: {{ $canal_venda->site }}</h4>
                        </div>
                        <div class="col-12 col-md-2 pr-0 text-md-right">
                            <label for="periodo_vendas_filtro" class="m-auto text-primary">Período de vendas</label>
                        </div>
                        <div class="col-12 col-md-3">
                            <select name="periodo_vendas" id="periodo_vendas_filtro" title="Período de vendas"
                                    class="form-control d-inline" data-route="{{ route('app.pedidos.index') }}">
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
                    <div class="table-responsive no-overflow">
                        <table class="table table-hover table-border-bottom mb-0 data-table table-mobile">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Dados comprador</th>
                                <th>Reserva - Pedido</th>
                                <th>Valor reserva</th>
                                <th>Status reserva</th>
                                <th>Pagamento</th>
                                {{-- <th class="text-center">Comprado em</th>--}}
                                <th class="text-center">Administração</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($dados_reservas['reservas'] as $reserva)
                                <tr>
                                    <td data-label="#" width="10" class="text-center">{{ $loop->iteration }}</td>
                                    <td data-label="Dados comprador" class="text-primary">
                                        <p class="text-truncate mt-2 mb-0">
                                            <strong class="pr-1">{{ str_limit($reserva->pedido->cliente->nome, 30) }}</strong>
                                        </p>
                                        <p class="text-truncate mt-0 mb-2">{{ str_limit($reserva->pedido->cliente->email, 30) }}</p>
                                    </td>
                                    <td data-label="Reserva - Pedido" class="text-primary">
                                        <p class="mt-2 mb-0">
                                            <strong class="pr-1">Reserva</strong>
                                            <a href="{{ route('app.reservas.view', $reserva->voucher) }}" target="_blank" class="text-secondary">#{{ $reserva->voucher }}</a>
                                        </p>
                                        <p class="mb-2 mt-0">
                                            <strong class="pr-1">Pedido</strong>
                                            <a href="{{ route('app.pedidos.view', $reserva->pedido->codigo) }}" target="_blank" class="text-secondary">#{{ $reserva->pedido->codigo }}</a>
                                        </p>
                                    </td>
                                    <td data-label="Valor reserva" class="text-primary">
                                        <p class="m-0">R$ {{ formataValor($reserva->valor_total) }}</p>
                                        <small>({{ $reserva->pedido->forma_pagamento }})</small>
                                    </td>
                                    <td data-label="Status reserva">
                                        @if($reserva->status == 'ATIVA' && $reserva->finalizada == \TourFacil\Core\Enum\StatusFinalizacaoReservaEnum::NAO_FINALIZADA)
                                            <strong class="text-warning">Finalizar</strong>
                                        @else
                                            <strong class="text-{{ $reserva->cor_status }}">{{ $reserva->status_reserva }}</strong>
                                        @endif
                                    </td>
                                    <td data-label="Pagamento">
                                        <span class="badge-text badge-text-small {{ $reserva->pedido->cor_status_pagamento }}">
                                            {{ $reserva->pedido->pagamento_status }}
                                        </span>
                                    </td>
                                    {{-- <td class="text-center text-primary">{{ $reserva->created_at->format('d/m/Y H:i') }}</td>--}}
                                    <td class="text-center">
                                        <a href="{{ route('app.pedidos.view', $reserva->pedido->codigo) }}" target="_blank" class="btn btn-outline-primary">
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

@endsection
