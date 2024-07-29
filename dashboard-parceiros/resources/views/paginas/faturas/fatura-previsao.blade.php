@extends('template.header')

@section('title', 'Previsão de fatura')

@section('content')

    <div class="row">
        <div class="page-header">
            <div class="d-flex align-items-center">
                <h2 class="page-header-title text-gradient-01">Previsão de Fatura</h2>
                <div>{{ Breadcrumbs::render('app.faturas.fatura-prevista-individual') }}</div>
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
                        <div class="col d-flex align-items-center" style="justify-content: space-between;">
                            <div class="content-widget">
                                <p class="m-0 text-primary">Período da previsão</p>
                                <strong class="text-secondary">De {{ $inicio->format('d/m/Y') }} até {{ $final->format('d/m/Y') }}</strong>
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
                                <p class="m-0 text-primary">Status</p>
                                <strong class="text-success">Previsto</strong>
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
                                <p class="m-0 text-primary">R$ Valor</p>
                                <strong class="text-warning">R$ {{ formataValor($reservas->sum('valor_net')) }}</strong>
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
                                <p class="m-0 text-primary">Quantidade</p>
                                <strong class="text-info">{{ $reservas->sum('quantidade') }} ingresso(s) em {{ $reservas->count() }} reserva(s)</strong>
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
                <div class="widget-header bordered no-actions d-flex align-items-center">
                    <h4 class="mr-auto">Conferência da previsão de fatura de <b>{{ $fornecedor->nome_fantasia }}</b></h4>
                    <div>
                        <a href="{{ Route('app.faturas.fatura-prevista-individual') . '?fornecedor=' . $fornecedor->id . '&inicio=' . $inicio->format('Y-m-d') . '&final=' . $final->format('Y-m-d') . '&tipo=XLSX'}} " target="_blank" class="btn btn-secondary line-height-inherit">Baixar XLS <i class="la la-download right"></i></a>
                        {{-- {{-- <a href="{{ Route('app.faturas.show') . '?fatura_id=' . $fatura->id . '&tipo_relatorio=XLSX' }}" target="_blank" class="btn btn-secondary line-height-inherit">Baixar XLS <i class="la la-download right"></i></a> --}}
                        <a href="{{ Route('app.faturas.fatura-prevista-individual') . '?fornecedor=' . $fornecedor->id . '&inicio=' . $inicio->format('Y-m-d') . '&final=' . $final->format('Y-m-d') . '&tipo=PDF'}} " target="_blank" class="btn btn-secondary line-height-inherit">Baixar PDF <i class="la la-download right"></i></a>
                    </div>
                </div>
                <div class="widget-body">
                    <div class="table-responsive no-overflow">
                        <table class="table table-hover table-border-bottom mb-0 data-table table-mobile">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Cliente</th>
                                <th>Serviço</th>
                                <th>Categorias</th>
                                <th>Data Venda</th>
                                <th>Data Utilização</th>
                                <th class="text-center">Valor (R$)</th>
                            </tr>
                            </thead>
                            <tbody>

                            @php

                                function getTextoCategorias($reserva) {
                                    $texto = "";

                                    foreach($reserva->quantidadeReserva as $quantidade) {
                                        $variacao = $quantidade->variacaoServico;

                                        $texto .= "{$quantidade->quantidade}x {$variacao->nome} ";
                                    }

                                    return $texto;
                                }


                            @endphp



                            @foreach($reservas as $reserva)
                                <tr style="color: #5d5386;">
                                    <td>
                                        <a href="{{ Route('app.reservas.view', $reserva->voucher) }}" class="text-secondary">#{{ $reserva->voucher }}</a>
                                    </td>
                                    <td>{{ $reserva->pedido->cliente->nome }}</td>
                                    <td>{{ $reserva->servico->nome }}</td>
                                    <td>{{ getTextoCategorias($reserva) }}</td>
                                    <td>{{ $reserva->created_at->format('d/m/Y - H:m') }}</td>
                                    <td>{{ $reserva->agendaDataServico->data->format('d/m/Y') }}</td>
                                    <td class="text-warning text-center font-weight-bold">R${{ formataValor($reserva->valor_net) }}</td>
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


