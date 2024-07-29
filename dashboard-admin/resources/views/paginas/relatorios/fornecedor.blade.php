@extends('template.header')

@section('title', 'Relatório de fornecedores ' . $canal_venda->nome)

@section('content')

    <div class="row">
        <div class="page-header">
            <div class="d-flex align-items-center">
                <h2 class="page-header-title text-dark">Fornecedores <span class="text-gradient-01">que {{ ($somente_autenticados == 'true') ? "validaram ingressos" : "possuem vendas" }}</span> </h2>
                <div>{{ Breadcrumbs::render('app.relatorios.fornecedores.index') }}</div>
            </div>
        </div>
    </div>

    <div class="row flex-row" data-controller="RelatorioFornecedorCtrl">
        <div class="col-xl-3 col-md-6">
            <div class="widget has-shadow">
                <div class="widget-body">
                    <div class="row align-items-center">
                        <div class="col-xl-2 d-flex">
                            <i class="la la-calendar la-2x"></i>
                        </div>
                        <div class="col d-flex align-items-center">
                            <div class="content-widget">
                                <p class="m-0 text-primary">Período de {{ ($tipo_data == 'UTILIZACAO') ? "utilização" : "venda" }}</p>
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
                                <p class="m-0 text-primary">R$ Total vendido</p>
                                <strong class="text-success">R$ {{ formataValor($total_vendido) }}</strong>
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
                                <p class="m-0 text-primary">R$ NET</p>
                                <strong class="text-warning">R$ {{ formataValor($total_net) }}</strong>
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
                                <p class="m-0 text-primary">Ingressos {{ ($somente_autenticados == 'true') ? "autenticados" : "vendidos" }}</p>
                                <strong class="text-info">{{ $total_quantidade }} ingresso(s)</strong>
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
                            <div class="form-group pl-2 m-0">
                                <a href="{{ route('app.relatorios.fornecedores.download', ['type' => 'pdf', 'tipo_data' => $tipo_data,'autenticados' => $somente_autenticados, 'inicio' => $pp_start->format('d-m-Y'), 'final' => $pp_end->format('d-m-Y')]) }}" target="_blank"
                                   class="btn btn-secondary mr-2 line-height-inherit">Baixar PDF <i class="la la-download right"></i></a>
                                <a href="{{ route('app.relatorios.fornecedores.download', ['type' => 'xls', 'tipo_data' => $tipo_data, 'autenticados' => $somente_autenticados, 'inicio' => $pp_start->format('d-m-Y'), 'final' => $pp_end->format('d-m-Y')]) }}" target="_blank"
                                   class="btn btn-secondary line-height-inherit">Baixar XLS <i class="la la-download right"></i></a>
                            </div>
                        </div>
                        <div class="col-2 pr-0 text-right">
                            <label for="periodo_vendas_filtro" class="m-auto text-primary">Filtar por período</label>
                        </div>
                        <div class="col-3">
                            <select name="periodo_vendas" id="periodo_vendas_filtro" title="Período de vendas"
                                    class="form-control d-inline" data-route="{{ route('app.relatorios.fornecedores.index') }}?autenticados={{ $somente_autenticados }}">
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
                <div class="widget-body pr-2 pl-2">
                    <div class="table-responsive">
                        <table class="table table-hover table-border-bottom mb-0 data-table" data-page-length="1000">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Fornecedor</th>
                                <th>CNPJ</th>
                                <th class="text-center">R$ Vendido</th>
                                <th class="text-center">R$ NET</th>
                                <th class="text-center">{{ ($somente_autenticados == 'true') ? "Validado" : "Vendido" }}</th>
                                <th class="text-center">Relatório</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($fornecedores as $fornecedor)
                                <tr>
                                    <td width="10" class="text-center pt-3 pb-3">{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="d-inline text-truncate m-0">
                                            <a href="{{ route('app.fornecedores.view', $fornecedor->id) }}" title="Ver fornecedor" class="text-truncate" target="_blank">
                                                {{ $fornecedor->nome_fantasia }}
                                            </a>
                                        </div>
                                    </td>
                                    <td>{{ $fornecedor->cnpj }}</td>
                                    <td data-order="{{ $fornecedor->vendido }}" class="text-success text-center font-weight-bold">R$ {{ formataValor($fornecedor->vendido) }}</td>
                                    <td data-order="{{ $fornecedor->tarifa_net }}" class="text-warning text-center font-weight-bold">R$ {{ formataValor($fornecedor->tarifa_net) }}</td>
                                    <td data-order="{{ $fornecedor->quantidade }}" class="text-info text-center font-weight-bold">{{ $fornecedor->quantidade }} ingresso(s)</td>
                                    <td class="text-center">
                                        @if($somente_autenticados == 'true')
                                            <a href="{{ route('app.relatorios.autenticados.index', ['fornecedor' => $fornecedor->id, 'periodo' => 'custom', 'inicio' => $pp_start->format('d-m-Y'), 'final' => $pp_end->format('d-m-Y')]) }}" target="_blank" title="Ver relatório" class="btn btn-outline-primary">
                                                Detalhes <i class="la la-external-link right"></i>
                                            </a>
                                        @else
                                            
                                            <a href="{{ route('app.relatorios.vendidos.index', ['fornecedor' => $fornecedor->id, 'tipo_data' => $tipo_data,'periodo' => 'custom', 'inicio' => $pp_start->format('d-m-Y'), 'final' => $pp_end->format('d-m-Y')]) }}" target="_blank" title="Ver relatório" class="btn btn-outline-primary">
                                                Detalhes <i class="la la-external-link right"></i>
                                            </a>
                                            
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center pt-3 pb-3">Sem dados para o período</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
