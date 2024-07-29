@extends('template.header')

@section('title', 'Relatório de fornecedores')

@section('content')

    <div class="row">
        <div class="page-header">
            <div class="d-flex align-items-center">
                <h2 class="page-header-title text-dark">Afiliados <span class="text-gradient-01">que possuem vendas</span> </h2>
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
                                <strong class="text-secondary">{{ $inicio->format('d/m') }} até {{ $final->format('d/m') }}</strong>
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
                                <p class="m-0 text-primary">R$ Comissão</p>
                                <strong class="text-warning">R$ {{ formataValor($total_comissionado) }}</strong>
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
                                <a href="{{ Route('app.relatorios.afiliados.index2-pdf') }}?tipo_operacao={{ $tipo_data }}&periodo=custom&inicio={{ $inicio }}&final={{ $final }}" target="_blank"
                                   class="btn btn-secondary mr-2 line-height-inherit">Baixar PDF <i class="la la-download right"></i></a>
                                <a href="{{ Route('app.relatorios.afiliados.index2-xls') }}?tipo_operacao={{ $tipo_data }}&periodo=custom&inicio={{ $inicio }}&final={{ $final }}" target="_blank"
                                   class="btn btn-secondary line-height-inherit">Baixar XLS <i class="la la-download right"></i></a>
                            </div>
                        </div>
                        <div class="col-2 pr-0 text-right">

                        </div>
                    </div>
                </div>
                <div class="widget-body pr-2 pl-2">
                    <div class="table-responsive">
                        <table class="table table-hover table-border-bottom mb-0 data-table" data-page-length="1000">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Afiliado</th>
                                <th>CNPJ</th>
                                <th class="text-center">R$ Vendido</th>
                                <th class="text-center">R$ Comissão</th>
                                <th class="text-center">Vendido</th>
                                <th class="text-center">Relatório</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($afiliados as $nome => $afiliado)
                                <tr>
                                    <td width="10" class="text-center pt-3 pb-3">{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="d-inline text-truncate m-0">
                                            <a href="{{ Route('app.relatorios.afiliados.index') }}?afiliado_id={{ $afiliado['afiliado']['id'] }}&tipo_operacao={{ $tipo_data }}&periodo=custom&inicio={{ $inicio }}&final={{ $final }}" title="Ver fornecedor" class="text-truncate" target="_blank">
                                                {{ $nome }}
                                            </a>
                                        </div>
                                    </td>
                                    <td>{{ $afiliado['afiliado']['cnpj'] }}</td>
                                    <td data-order="{{ $afiliado['valor_venda'] }}" class="text-success text-center font-weight-bold">R$ {{ formataValor($afiliado['valor_venda']) }}</td>
                                    <td data-order="{{ $afiliado['valor_comissao'] }}" class="text-warning text-center font-weight-bold">R$ {{ formataValor($afiliado['valor_comissao']) }}</td>
                                    <td data-order="{{ $afiliado['quantidade'] }}" class="text-info text-center font-weight-bold">{{ $afiliado['quantidade'] }} ingresso(s)</td>
                                    <td class="text-center">
                                        <a href="{{ Route('app.relatorios.afiliados.index') }}?afiliado_id={{ $afiliado['afiliado']['id'] }}&tipo_operacao={{ $tipo_data }}&periodo=custom&inicio={{ $inicio }}&final={{ $final }}" target="_blank" title="Ver relatório" class="btn btn-outline-primary">
                                            Detalhes <i class="la la-external-link right"></i>
                                        </a>
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

    <div class="float-button">
        <a href="#" data-toggle="modal" data-target="#filtro-relatorio-fornecedor" class="btn btn-gradient-02 btn-shadow"><i class="ion ion-funnel"></i></a>
        <p class="float-tooltip">Filtrar Reservas</p>
    </div>

    @include('paginas.modais.filtro-relatorio-afiliado')


@endsection
