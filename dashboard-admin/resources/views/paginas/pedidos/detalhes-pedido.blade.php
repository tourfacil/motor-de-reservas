@extends('template.header')

@section('title', 'Detalhes pedido #' . $pedido->codigo)

@section('content')

    <div class="row">
        <div class="page-header">
            <div class="d-flex align-items-center">
                <h2 class="page-header-title text-dark">Detalhes pedido <span class="text-gradient-01">#{{ $pedido->codigo }}</span></h2>
                <div>{{ Breadcrumbs::render('app.pedidos.view') }}</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="widget has-shadow info-pedido">
                <div class="widget-body pl-lg-5 pr-lg-5 pb-3">
                    <div class="row">
                        <div class="col-xl-8 mt-3 mb-2">
                            <div class="section-title mr-auto">
                                <i class="la la-file-text la-3x pull-left pr-4"></i>
                                <h3>Informações sobre o pedido</h3>
                                <p class="mt-1">Pedido realizado no dia {{ $pedido->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        <div class="col-xl-4 mt-3 mb-4 d-flex align-items-center">
                            <h2 class="ml-auto mb-0">#{{ $pedido->codigo }}</h2>
                        </div>
                        <div class="col-xl-12 mb-4">
                            <div class="em-separator separator-dashed mt-0 mb-2"></div>
                        </div>
                        @if($pedido->metodo_pagamento == \TourFacil\Core\Enum\MetodoPagamentoEnum::CARTAO_CREDITO)
                            @include("paginas.pedidos._partials.pagamento-cartao", $pedido)
                        @elseif($pedido->metodo_pagamento == \TourFacil\Core\Enum\MetodoPagamentoEnum::BOLETO)
                            @include("paginas.pedidos._partials.pagamento-boleto", $pedido)
                        @elseif($pedido->metodo_pagamento == \TourFacil\Core\Enum\MetodoPagamentoEnum::CARTAO_DEBITO)
                            @include("paginas.pedidos._partials.pagamento-debito", $pedido)
                        @elseif($pedido->metodo_pagamento == "INTERNO")
                            @include("paginas.pedidos._partials.pagamento-interno", $pedido)
                        @elseif($pedido->metodo_pagamento == \TourFacil\Core\Enum\MetodoPagamentoEnum::PIX)
                            @include("paginas.pedidos._partials.pagamento-pix", $pedido)
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="widget has-shadow info-pedido">
                <div class="widget-body pl-lg-5 pr-lg-5 pb-2">
                    <div class="row">
                        <div class="col-xl-8 mt-3 mb-2">
                            <div class="section-title mr-auto">
                                <i class="la la-user la-3x pull-left pr-4"></i>
                                <h3>Dados do comprador</h3>
                                <p class="mt-1">Informações sobre o cliente.</p>
                            </div>
                        </div>
                        <div class="col-xl-4 mt-3 mb-4 d-flex align-items-center">
                            <h2 class="ml-auto mb-0">#{{ $pedido->cliente->id }}</h2>
                        </div>
                        <div class="col-xl-12 mb-4">
                            <div class="em-separator separator-dashed mt-0 mb-2"></div>
                        </div>
                        <div class="col-xl-4 mb-1 info-terminal">
                            <p>
                                <i class="la la-user mr-2"></i> Nome completo <br>
                                <strong>
                                    @if(userIsAfiliado() == false)
                                        <a href="{{ route('app.clientes.view', $pedido->cliente->id) }}" title="Ver cliente" target="_blank" class="text-secondary">{{ $pedido->cliente->nome }}</a>
                                    @else
                                        <strong>{{ $pedido->cliente->nome }}</strong>
                                    @endif
                                </strong>
                            </p>
                            <p>
                                <i class="la la-copyright mr-2"></i> CPF <br>
                                <strong>{{ $pedido->cliente->cpf }}</strong>
                            </p>
                        </div>
                        <div class="col-xl-4 mb-1 info-terminal">
                            <p>
                                <i class="la la-at mr-2"></i> E-mail <br>
                                <strong><a href="mailto:{{ $pedido->cliente->email }}" class="info-terminal">{{ $pedido->cliente->email }}</a></strong>
                            </p>
                            <p>
                                <i class="la la-calendar mr-2"></i> Nascimento <br>
                                <strong>{{ $pedido->cliente->nascimento->format('d/m/Y') }}</strong>
                            </p>
                        </div>
                        <div class="col-xl-4 mb-1 info-terminal">
                            <p>
                                <i class="la la-phone mr-2"></i> Telefone <br>
                                <strong><a  class="info-terminal" href="{{ getUrlWhatsapp($pedido->cliente->telefone) }}" target="_blank">{{ $pedido->cliente->telefone }}</a></strong>
                            </p>
                            <p>
                                <i class="la la-calendar-check-o mr-2"></i> Cadastrado em <br>
                                <strong>{{ $pedido->cliente->created_at->format('d/m/Y H:i') }}</strong>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="widget has-shadow">
                <div class="widget-body pl-lg-5 pr-lg-5">
                    <div class="row">
                        <div class="col-xl-8 mt-3 mb-3">
                            <div class="section-title mr-auto">
                                <i class="la la-shopping-cart la-3x pull-left pr-4"></i>
                                <h3>Serviços adquiridos</h3>
                                <p class="mt-1">Listagem das reservas do pedido.</p>
                            </div>
                        </div>
                        <div class="col-xl-4 mt-4 mb-5 d-flex align-items-center">
                            <h2 class="ml-auto mb-0">{{ $pedido->reservas->count() }} serviço(s)</h2>
                        </div>
                        <div class="col-12 mb-2 mt-1">
                            <div class="table-responsive no-overflow">
                                <table class="table table-divider mb-0 table-mobile">
                                    <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Reserva</th>
                                        <th>Serviço adquirido</th>
                                        <th class="text-center">Utilização</th>
                                        <th class="text-center">Valor total</th>
                                        <th class="text-center">Status da reserva</th>
                                        <th class="text-center">Informações</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($pedido->reservas as $reserva)
                                        <tr>
                                            <td data-label="#" class="text-center">{{ $loop->iteration }}</td>
                                            <td data-label="Reserva" class="text-primary">
                                                <a href="{{ route('app.reservas.view', $reserva->voucher) }}" target="_blank" class="text-secondary">#{{ $reserva->voucher }}</a>
                                            </td>
                                            <td data-label="Serviço" class="text-primary">({{ $reserva->quantidade }}x) {{ $reserva->servico->nome }}</td>
                                            <td data-label="Utilização" class="text-center text-primary">
                                                <strong>{{ $reserva->agendaDataServico->data->format('d/m/Y') }}</strong>
                                            </td>
                                            <td data-label="Valor" class="text-center text-primary">R$ {{ formataValor($reserva->valor_total) }}</td>
                                            <td data-label="Status" class="text-center">
                                                @if($reserva->status == 'ATIVA' && $reserva->finalizada == \TourFacil\Core\Enum\StatusFinalizacaoReservaEnum::NAO_FINALIZADA)
                                                    <strong class="text-warning text-uppercase">
                                                        Finalizar
                                                    </strong>
                                                @else
                                                    <strong class="text-{{ $reserva->cor_status }} text-uppercase">
                                                        {{ $reserva->status_reserva }}
                                                    </strong>
                                                @endif
                                            </td>
                                            <td data-label="Informações" class="text-center">
                                                <a href="{{ route('app.reservas.view', $reserva->voucher) }}" target="_blank" class="btn btn-gradient-01 mt-2 mb-2">Ver reserva</a>
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
        </div>
    </div>

    @if($pedido->comissaoTerminal->count())
        <div class="row">
            <div class="col-12">
                <div class="widget has-shadow">
                    <div class="widget-body pl-lg-5 pr-lg-5">
                        <div class="row">
                            <div class="col-xl-8 mt-3 mb-3">
                                <div class="section-title mr-auto">
                                    <i class="la la la-desktop la-3x pull-left pr-4"></i>
                                    <h3>Comissão terminal de venda</h3>
                                    <p class="mt-1">Detalhes do terminal onde foi realizada a compra.</p>
                                </div>
                            </div>
                            <div class="col-xl-4 mt-4 mb-5 d-flex align-items-center">
                                <h2 class="ml-auto mb-0">R$ {{ formataValor($pedido->comissaoTerminal->sum('comissao')) }}</h2>
                            </div>
                            <div class="col-12 mb-2 mt-1">
                                <div class="table-responsive">
                                    <table class="table table-divider mb-0">
                                        <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th>Terminal de venda</th>
                                            <th class="text-center">Cód. Reserva</th>
                                            <th class="text-center">Prev. Pagamento</th>
                                            <th class="text-center">Valor reserva</th>
                                            <th class="text-center">Valor comissão</th>
                                            <th class="text-center">Status da comissão</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($pedido->comissaoTerminal as $comissao)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td class="text-primary">
                                                    <strong>
                                                        <a href="{{ route('app.terminais.view', $comissao->terminal->id) }}" target="_blank">{{ $comissao->terminal->nome }}</a>
                                                    </strong>
                                                </td>
                                                <td class="text-primary text-center">
                                                    <a href="{{ route('app.reservas.view', $comissao->reservaPedido->voucher) }}" target="_blank" class="text-secondary">#{{ $comissao->reservaPedido->voucher }}</a>
                                                </td>
                                                <td class="text-center text-primary">{{ $comissao->data_previsao->format('d/m/Y') }}</td>
                                                <td class="text-center text-primary">R$ {{ formataValor($comissao->reservaPedido->valor_total) }}</td>
                                                <td class="text-center text-primary"><strong>R$ {{ formataValor($comissao->comissao) }}</strong></td>
                                                <td class="text-center">
                                                    <strong class="text-{{ $comissao->cor_status_comissao }} text-uppercase">
                                                        {{  $comissao->status_comissao }}
                                                    </strong>
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
            </div>
        </div>
    @endif

    @if($pedido->historicoPedido->count() > 0)
        <div class="row">
            <div class="col-12">
                <div class="widget has-shadow">
                    <div class="widget-body pl-lg-5 pr-lg-5">
                        <div class="row">
                            <div class="col-xl-8 mt-3 mb-3">
                                <div class="section-title mr-auto">
                                    <i class="la la-history la-3x pull-left pr-4"></i>
                                    <h3>Histórico de alterações</h3>
                                    <p class="mt-1">Listagem das alterações realizadas no pedido.</p>
                                </div>
                            </div>
                            <div class="col-xl-4 mt-4 mb-5 d-flex align-items-center">
                                <h2 class="ml-auto mb-0">{{ $pedido->historicoPedido->count() }} alterações</h2>
                            </div>
                            <div class="col-12 mb-2 mt-1">
                                <div class="table-responsive">
                                    <table class="table table-divider mb-0">
                                        <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th>Reserva</th>
                                            <th>Motivo da alteração</th>
                                            <th>Usuário</th>
                                            <th class="text-center">Valor total</th>
                                            <th class="text-center">Valor net</th>
                                            <th class="text-center">Data da alteração</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($pedido->historicoPedido as $alteracao)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td class="text-primary">
                                                    <a href="{{ route('app.reservas.view', $alteracao->reservaPedido->voucher) }}" target="_blank" class="text-secondary">#{{ $alteracao->reservaPedido->voucher }}</a>
                                                </td>
                                                <td class="text-primary">{{ $alteracao->motivo_historico }}</td>
                                                <td class="text-primary">{{ $alteracao->user->name }}</td>
                                                <td class="text-center text-primary">R$ {{ formataValor($alteracao->valor) }}</td>
                                                <td class="text-center text-primary">R$ {{ formataValor($alteracao->valor_fornecedor) }}</td>
                                                <td class="text-center text-primary">{{ $alteracao->created_at->format('d/m/Y H:i') }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection
