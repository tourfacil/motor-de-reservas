@extends('template.header')

@section('title', 'Dashboard')

@section('content')

    @php($count = 1)

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
                            <i class="la la-cube la-2x"></i>
                        </div>
                        <div class="col d-flex align-items-center">
                            <div class="content-widget">
                                <p class="m-0 text-primary">Novos serviços</p>
                                <strong class="text-success">+{{ $chart['quantidade_total'] }} cadastrado(s)</strong>
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
                                <p class="m-0 text-primary">Disponíveis para venda</p>
                                <strong class="text-warning">{{ $cadastrados }} serviço(s)</strong>
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
                            <i class="la la-clock-o la-2x"></i>
                        </div>
                        <div class="col d-flex align-items-center">
                            <div class="content-widget">
                                <p class="m-0 text-primary">Pendentes para análise</p>
                                <strong class="text-info">{{ $pendentes }} serviço(s)</strong>
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
                            <i class="la la-ban la-2x"></i>
                        </div>
                        <div class="col d-flex align-items-center">
                            <div class="content-widget">
                                <p class="m-0 text-primary">Inativos ou desativados</p>
                                <strong class="text-danger">{{ $desativados }} Serviço(s)</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-7">
            <div class="widget has-shadow">
                <div class="widget-header bordered no-actions d-flex align-items-center">
                    <h4>Cadastrados nos últimos 10 dias</h4>
                    <i class="la la-bar-chart la-lg ml-auto"></i>
                </div>
                <div class="widget-body">
                    <div class="chart-vendas">
                        <canvas id="line-chart-01"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-5 col-md-12">
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
    </div>

    <div class="row no-margin">
        <div class="col-xl-12">
            <div class="row widget has-shadow">
                <div class="widget-header bordered no-actions d-flex align-items-center">
                    <h4>Últimos serviços cadastrados</h4>
                    <i class="la la-shopping-cart la-lg ml-auto"></i>
                </div>
                <div class="widget-body w-100">
                    <div class="table-responsive">
                        <table class="table table-hover table-border-bottom mb-0 data-table ultimas-vendas">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Nome do serviço</th>
                                <th>Fornecedor</th>
                                <th>R$ Venda</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Cadastrado em</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($novos_servicos as $servico)
                                <tr>
                                    <td width="5" class="text-center">{{ $count++ }}</td>
                                    <td>
                                        <div class="d-inline text-truncate m-0">
                                            <a href="{{ route('app.servicos.view', $servico->id) }}" class="text-truncate" target="_blank">
                                                <strong>{{ $servico->nome }}</strong>
                                            </a>
                                        </div>
                                    </td>
                                    <td class="text-primary">{{ $servico->fornecedor->nome_fantasia }}</td>
                                    <td class="text-primary">R$ {{ formataValor($servico->valor_venda) }}</td>
                                    <td class="text-center">
                                        <span class="badge-text badge-text-small {{ $servico->cor_status_servico }}">
                                            {{ $servico->status_servico }}
                                        </span>
                                    </td>
                                    <td class="text-center text-primary">
                                        <a href="{{ route('app.servicos.view', $servico->id) }}" target="_blank" class="btn btn-outline-primary">
                                            Editar <i class="la la-edit right"></i>
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


@section('scripts')
    <script>
        window.dados_chart = {!! json_encode($chart) !!};
        window.label_chart = "Novos serviços";
    </script>
@endsection
