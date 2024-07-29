@extends('template.header')

@section('title', 'Relatório de reservas autenticadas ' . $canal_venda->nome)

@section('content')

    <div class="row">
        <div class="page-header">
            <div class="d-flex align-items-center">
                <h2 class="page-header-title text-dark">Relatório de <span class="text-gradient-01">reservas autenticadas</span></h2>
                <div>{{ Breadcrumbs::render('app.relatorios.autenticados.index') }}</div>
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
                                <p class="m-0 text-primary">Período de utilização</p>
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
                                <p class="m-0 text-primary">R$ Total reservas</p>
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
                                <p class="m-0 text-primary">R$ Tarifa NET</p>
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
                                <p class="m-0 text-primary">Ingressos autenticados</p>
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
                        <div class="col-8">
                            <div class="ml-2">
                                <small>Fornecedor</small>
                                <h3>{{ $fornecedor->nome_fantasia }}</h3>
                            </div>
                        </div>
                        <div class="col-4 text-right">
                            <div class="form-group pl-2 m-0">
                                <a href="{{ route('app.relatorios.autenticados.download', ['type' => 'pdf', 'fornecedor' => $fornecedor->id, 'servicos' => $servicos_url, 'inicio' => $pp_start->format('d-m-Y'), 'final' => $pp_end->format('d-m-Y')]) }}"
                                   target="_blank" class="btn btn-secondary mr-2 line-height-inherit">Baixar PDF <i class="la la-download right"></i></a>
                                <a href="{{ route('app.relatorios.autenticados.download', ['type' => 'xls', 'fornecedor' => $fornecedor->id, 'servicos' => $servicos_url, 'inicio' => $pp_start->format('d-m-Y'), 'final' => $pp_end->format('d-m-Y')]) }}"
                                   target="_blank" class="btn btn-secondary line-height-inherit">Baixar XLS <i class="la la-download right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="widget-body pr-2 pl-2">
                    <div class="table-responsive">
                        <table class="table table-hover table-border-bottom mb-0 data-table" data-page-length="1000">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Reserva</th>
                                <th>Cliente</th>
                                <th>Serviço utilizado</th>
                                <th>Agendado</th>
                                <th class="text-center">R$ Valor</th>
                                <th class="text-center">R$ NET</th>
                                <th class="text-center">Data da compra</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($reservas as $reserva)
                                <tr>
                                    <td width="10" class="text-center pt-3 pb-3">{{ $loop->iteration }}</td>
                                    <td>
                                        <a href="{{ route('app.reservas.view', $reserva->voucher) }}" target="_blank" class="text-secondary">#{{ $reserva->voucher }}</a>
                                    </td>
                                    <td><span class="text-primary">{{ $reserva->pedido->cliente->nome }}</span></td>
                                    <td class="text-primary">
                                        <div class="d-inline text-truncate m-0 is-95">
                                            <strong>({{ $reserva->quantidade }}x)</strong>
                                            <span class="text-truncate">{{ $reserva->servico->nome }}</span>
                                        </div>
                                    </td>
                                    <td class="text-primary">{{ $reserva->agendaDataServico->data->format('d/m/Y') }}</td>
                                    <td class="text-success text-center font-weight-bold">R$ {{ formataValor($reserva->valor_total) }}</td>
                                    <td class="text-warning text-center font-weight-bold">R$ {{ formataValor($reserva->valor_net) }}</td>
                                    <td class="text-primary text-center">{{ $reserva->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center pt-3 pb-3">Sem dados para o período</td>
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
        <p class="float-tooltip">Filtrar relatório</p>
    </div>

    @include('paginas.modais.filtro-relatorio-fornecedor')

@endsection
