@extends('template.header')

@section('title', 'Vendas Terminal ' . $terminal->nome)

@section('content')

    <div class="row">
        <div class="page-header">
            <div class="d-flex align-items-center">
                <h2 class="page-header-title text-dark">Vendas Terminal <span class="text-gradient-01">{{ $terminal->nome }}</span></h2>
                <div>{{ Breadcrumbs::render('app.terminais.relatorios.view') }}</div>
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
                                <strong class="text-secondary">{{ mesPT($data_inicio->month) }} de {{ $data_final->year }}</strong>
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
                                <strong class="text-success">+R$ {{ formataValor($valores_venda->vendido ?? 0) }}</strong>
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
                                <p class="m-0 text-primary">R$ Total comissão</p>
                                <strong class="text-warning">R$ {{ formataValor($valores_venda->comissao ?? 0) }}</strong>
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
                                <strong class="text-info">{{ $valores_venda->ingressos ?? "0" }} ingresso(s)</strong>
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
                                <a href="{{ route('app.terminais.relatorios.view.download', [$terminal->id, 'type' => 'pdf', 'periodo' => $mes_pesquisa]) }}" target="_blank"
                                   class="btn btn-secondary mr-2 line-height-inherit">Baixar PDF <i class="la la-download right"></i></a>
                                <a href="{{ route('app.terminais.relatorios.view.download', [$terminal->id, 'type' => 'xls', 'periodo' => $mes_pesquisa]) }}" target="_blank"
                                   class="btn btn-secondary line-height-inherit">Baixar XLS <i class="la la-download right"></i></a>
                            </div>
                        </div>
                        <div class="col-2 pr-0 text-right">
                            <label for="periodo_vendas_filtro" class="m-auto text-primary">Período de vendas</label>
                        </div>
                        <div class="col-3">
                            <select name="periodo_vendas" id="periodo_vendas_filtro" title="Período de vendas"
                                    class="form-control d-inline" data-route="{{ route('app.terminais.relatorios.view', $terminal->id) }}">
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
                <div class="widget-body pr-3 pl-3">
                    <div class="table-responsive">
                        <table class="table table-hover table-border-bottom mb-0 data-table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Reserva</th>
                                <th>Serviço vendido</th>
                                <th class="text-center">R$ Venda</th>
                                <th class="text-center">Comissão</th>
                                <th class="text-center">Utilização</th>
                                <th class="text-center">Status reserva</th>
                                <th class="text-center">Data da venda</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($terminal->comissaoTerminal as $comissao)
                                <tr>
                                    <td width="10" class="text-center">{{ $loop->iteration }}</td>
                                    <td>
                                        <a href="{{ route('app.reservas.view', $comissao->reservaPedido->voucher) }}" target="_blank" class="text-secondary">#{{ $comissao->reservaPedido->voucher }}</a>
                                    </td>
                                    <td class="text-primary">
                                        <div class="d-inline text-truncate m-0 is-95">
                                            <strong>({{ $comissao->reservaPedido->quantidade }}x)</strong>
                                            <span class="text-truncate">{{ $comissao->reservaPedido->servico->nome }}</span>
                                        </div>
                                    </td>
                                    <td class="text-success text-center font-weight-bold">R$ {{ formataValor($comissao->reservaPedido->valor_total) }}</td>
                                    <td class="text-primary text-center font-weight-bold">R$ {{ formataValor($comissao->comissao) }}</td>
                                    <td class="text-primary text-center">{{ $comissao->reservaPedido->agendaDataServico->data->format('d/m/Y') }}</td>
                                    <td class="text-center">
                                        <span class="badge-text badge-text-small {{ $comissao->reservaPedido->cor_status }} mt-2 mb-2">
                                            {{ $comissao->reservaPedido->status_reserva }}
                                        </span>
                                    </td>
                                    <td class="text-primary text-center">{{ $comissao->reservaPedido->created_at->format('d/m/Y H:i') }}</td>
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
