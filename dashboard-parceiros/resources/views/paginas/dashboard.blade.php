@extends('template.header')

@section('title', 'Dashboard')

@section('content')

    <div class="row">
        <div class="page-header {{ (session()->has('status')) ? "pb-3" : "" }}">
            <div class="d-flex align-items-center">
                <h2 class="page-header-title text-gradient-01">Dashboard</h2>
                <div>{{ Breadcrumbs::render('app.dashboard') }}</div>
            </div>
        </div>
    </div>

    @if(session()->has('status'))
        <div class="alert alert-success" role="alert">
            {{ session()->get('status') }}
        </div>
    @endif

    <div class="row flex-row" data-controller="DashboardCtrl">
        <div class="col-xl-3 col-md-6">
            <div class="widget has-shadow">
                <div class="widget-body">
                    <div class="row align-items-center">
                        <div class="col-xl-2 d-flex">
                            <i class="la la-cart-plus la-2x"></i>
                        </div>
                        <div class="col d-flex align-items-center">
                            <div class="content-widget">
                                <p class="m-0 text-primary">Vendas de hoje</p>
                                <strong class="text-secondary">R$ {{ formataValor($dashboard['vendas_hoje']) }}</strong>
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
                            <i class="la la-rocket la-2x"></i>
                        </div>
                        <div class="col d-flex align-items-center">
                            <div class="content-widget">
                                <p class="m-0 text-primary">Vendas do mês</p>
                                <strong class="text-success">+R$ {{ formataValor($dashboard['vendas_mes']) }}</strong>
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
                                <p class="m-0 text-primary">Vendido - 30 dias</p>
                                <strong class="text-info">{{ $dashboard['qtd_vendido_30'] }} Ingresso(s)</strong>
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
                                <p class="m-0 text-primary">Estornados - 30 dias</p>
                                <strong class="text-danger">{{ $dashboard['qtd_estornado_30'] }} Ingresso(s)</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-7">
            <div class="widget has-shadow">
                <div class="widget-header bordered no-actions d-flex align-items-center">
                    <h4>Vendas nos últimos 10 dias</h4>
                    <i class="la la-bar-chart la-lg ml-auto"></i>
                </div>
                <div class="widget-body">
                    <div class="chart-vendas">
                        <canvas id="line-chart-01"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-5 col-md-6">
            <div class="widget has-shadow">
                <div class="widget-header bordered no-actions d-flex align-items-center">
                    <h4>Serviços mais vendidos</h4>
                    <i class="la la-certificate la-lg ml-auto"></i>
                </div>
                <div class="widget-body">
                    <div class="table-responsive only-mobile">
                        <table class="table table-hover table-servicos-mais-vendidos mb-0">
                            <tbody>
                            @forelse($servicos_mais_vendidos as $index => $servico)
                                <tr>
                                    <td>{!! iconeTopServico(($index + 1)) !!}</td>
                                    <td><p class="text-truncate m-0 is-95 text-primary">{{ $servico->nome }}</p></td>
                                </tr>
                            @empty
                                {{-- Ghost table --}}
                                @for($i = 1; $i <= 8; $i++)
                                    <tr>
                                        <td width="10">{!! iconeTopServico($i) !!}</td>
                                        <td><div class="ghost"></div></td>
                                    </tr>
                                @endfor
                            @endforelse
                            {{-- Ghost table --}}
                            @if($qtd_servicos_vendidos >= 1 && $qtd_servicos_vendidos < 8)
                                @for($j = ($qtd_servicos_vendidos + 1); $j <= 8; $j++)
                                    <tr>
                                        <td width="10">{!! iconeTopServico($j) !!}</td>
                                        <td><div class="ghost"></div></td>
                                    </tr>
                                @endfor
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-12">
            <div class="widget has-shadow">
                <div class="widget-header bordered no-actions d-flex align-items-center">
                    <h4>Últimas vendas realizadas</h4>
                    <i class="la la-shopping-cart la-lg ml-auto"></i>
                </div>
                <div class="widget-body w-100">
                    <div class="table-responsive">
                        <table class="table table-hover table-border-bottom mb-0 data-table ultimas-vendas table-mobile">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Reserva</th>
                                <th>Serviço vendido</th>
                                <th>Utilização</th>
                                <th class="text-center">R$ Tarifa NET</th>
                                <th class="text-center">Status reserva</th>
                                <th class="text-center">Data da compra</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($dashboard['ultimas_vendas'] as $reserva)
                                <tr>
                                    <td data-label="#" width="5" class="text-center">{{ $loop->iteration }}</td>
                                    <td data-label="Cód. Reserva">
                                        <a href="{{ route('app.reservas.view', $reserva->voucher) }}" target="_blank" class="text-secondary">#{{ $reserva->voucher }}</a>
                                    </td>
                                    <td data-label="Serviço" class="text-primary">
                                        <div class="d-inline text-truncate m-0 is-95">
                                            <strong>({{ $reserva->quantidade }}x)</strong>
                                            <span class="text-truncate">{{ $reserva->servico->nome }}</span>
                                        </div>
                                    </td>
                                    <td data-label="Utilização" class="text-primary">{{ $reserva->agendaDataServico->data->format('d/m/Y') }}</td>
                                    <td data-label="Tarifa NET" class="text-center">
                                        <strong class="text-{{ $reserva->cor_status }}">
                                            R$ {{ formataValor($reserva->valor_net) }}
                                        </strong>
                                    </td>
                                    <td data-label="Status" class="text-center">
                                        <span class="badge-text badge-text-small {{ $reserva->cor_status }}">
                                            {{ $reserva->status_reserva }}
                                        </span>
                                    </td>
                                    <td data-label="Data compra" class="text-center text-primary pt-3 pb-3">
                                        {{ $reserva->created_at->format('d/m/Y H:i') }}
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

@section('scripts')
    <script>
        window.dados_chart = {!! json_encode($chart) !!}
    </script>
@endsection
