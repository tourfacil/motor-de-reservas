@extends('template.header')

@section('title', 'Relatório de vendas Terminais CDI')

@section('content')

    <div class="row">
        <div class="page-header">
            <div class="d-flex align-items-center">
                <h2 class="page-header-title text-dark">Relatório de vendas <span class="text-gradient-01">{{ $url_terminais }}</span></h2>
                <div>{{ Breadcrumbs::render('app.terminais.relatorios.index') }}</div>
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
                                <strong class="text-success">+R$ {{ formataValor($total_vendido) }}</strong>
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
                                <strong class="text-warning">R$ {{ formataValor($total_comissao) }}</strong>
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
                                <strong class="text-info">{{ $total_ingressos }} ingresso(s)</strong>
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
                                <a href="{{ route('app.terminais.relatorios.download', ['type' => 'pdf', 'periodo' => $mes_pesquisa]) }}" target="_blank"
                                   class="btn btn-secondary mr-2 line-height-inherit">Baixar PDF <i class="la la-download right"></i></a>
                                <a href="{{ route('app.terminais.relatorios.download', ['type' => 'xls', 'periodo' => $mes_pesquisa]) }}" target="_blank"
                                   class="btn btn-secondary line-height-inherit">Baixar XLS <i class="la la-download right"></i></a>
                            </div>
                        </div>
                        <div class="col-2 pr-0 text-right">
                            <label for="periodo_vendas_filtro" class="m-auto text-primary">Período de vendas</label>
                        </div>
                        <div class="col-3">
                            <select name="periodo_vendas" id="periodo_vendas_filtro" title="Período de vendas"
                                    class="form-control d-inline" data-route="{{ route('app.terminais.relatorios.index') }}">
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
                                <th>Total vendido</th>
                                <th>Comissão</th>
                                <th class="text-center">Administração</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($relatorio as $venda_terminal)
                                <tr>
                                    <td width="10" class="text-center">{{ $loop->iteration }}</td>
                                    <td>
                                        <a href="{{ route('app.terminais.view', $venda_terminal->terminal_id) }}" target="_blank" class="text-primary">
                                            <strong>{{ $venda_terminal->terminal->nome }}</strong>
                                        </a>
                                    </td>
                                    <td class="text-primary">{{ $venda_terminal->ingressos }} ingresso(s)</td>
                                    <td class="text-success">
                                        <strong>+R$ {{ formataValor($venda_terminal->vendido) }}</strong>
                                    </td>
                                    <td class="text-warning">
                                        <strong>R$ {{ formataValor($venda_terminal->comissao) }}</strong>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('app.terminais.relatorios.view', [$venda_terminal->terminal_id, 'periodo' => $mes_pesquisa]) }}" class="btn btn-outline-primary">
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
