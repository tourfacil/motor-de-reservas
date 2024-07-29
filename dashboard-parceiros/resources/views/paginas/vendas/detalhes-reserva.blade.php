@extends('template.header')

@section('title', 'Detalhes reserva #' . $reserva->voucher)

@section('content')

    @php($count_quantidade = 1)

    <div class="row">
        <div class="page-header pb-4">
            <div class="d-flex align-items-center">
                <h2 class="page-header-title text-dark">Detalhes reserva <span class="text-gradient-01">#{{ $reserva->voucher }}</span></h2>
                <div>{{ Breadcrumbs::render('app.reservas.view') }}</div>
            </div>
        </div>
    </div>

    {{-- Informativo sobre o status da reserva --}}
    <div class="row">
        <div class="col-12">
            @if($reserva->status === $enum_cancelada)
                <div class="alert alert-danger mb-4" role="alert">
                    <i class="la la-ban mr-2"></i>
                    Essa reserva foi <strong>cancelada</strong> no dia {{ $reserva->updated_at->format('d/m/Y H:i') }}
                </div>
            @elseif($reserva->status === $enum_utilizado)
                <div class="alert alert-primary mb-4" role="alert">
                    <i class="la la-check-circle mr-2"></i>
                    Essa reserva foi <strong>autenticada</strong> no dia {{ $reserva->validacao->validado->format('d/m/Y H:i') }}
                </div>
            @endif
        </div>
    </div>

    <div class="row" data-controller="VendasCtrl">
        <div class="col-12">
            <div class="widget has-shadow info-pedido">
                <div class="widget-body pl-lg-5 pr-lg-5 pb-2">
                    <div class="row">
                        <div class="col-xl-8 mt-3 mb-1">
                            <div class="section-title mr-auto">
                                <i class="la la-cart-arrow-down la-3x pull-left pr-4"></i>
                                <h3>Informações da reserva</h3>
                                <p class="mt-1">Reserva realizada no dia {{ $reserva->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        <div class="col-xl-4 mt-2 mb-4 d-flex align-items-center">
                            <h2 class="ml-auto mb-0">#{{ $reserva->voucher }}</h2>
                        </div>
                        <div class="col-xl-12 mb-2">
                            <div class="em-separator separator-dashed mt-0 mb-2"></div>
                        </div>
                        <div class="col-xl-8">
                            <div class="row">
                                <div class="col-xl-12 mb-1 mt-2 info-terminal">
                                    <p>
                                        Serviço vendido: <br>
                                        <strong>
                                            ({{ $reserva->quantidade }}x) {{ $reserva->servico->nome }}
                                        </strong>
                                    </p>
                                    <p>
                                        Variações adquiridas pelo cliente <br>
                                        @foreach($reserva->quantidadeReserva as $quantidade_reserva)
                                            @if($quantidade_reserva->status)
                                                <strong>{{ $quantidade_reserva->quantidade }}x {{ $quantidade_reserva->variacaoServico->nome }}</strong><span class="divider-quantidade pr-1">,</span>
                                            @endif
                                        @endforeach
                                    </p>
                                </div>
                                <div class="col-xl-4 mb-1 info-terminal">
                                    <p class="m-0">Agendado para</p>
                                    <div class="mt-2">
                                        <p>
                                            <strong>{{ dataExtenso($reserva->agendaDataServico->data, true) }}</strong>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-xl-4 mb-1 info-terminal">
                                    <p class="m-0">R$ Tarifa NET</p>
                                    <div class="mt-2">
                                        <p>
                                            <i class="la la-money pull-left"></i>
                                            <strong class="">R$ {{ formataValor($reserva->valor_net) }}</strong>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-xl-4 mb-1 info-terminal">
                                    <p class="m-0">Status da reserva</p>
                                    <div class="mt-2">
                                        <p>
                                            <strong class="text-{{ $reserva->cor_status }} text-uppercase mt-2">
                                                {{  $reserva->status_reserva }}
                                            </strong>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-xl-12 mb-2 info-terminal">
                                    @if($reserva->status === $enum_ativa)
                                        <button type="button" data-action="validar" class="btn btn-success mb-3 disable-action"
                                                data-url="{{ route('app.reservas.validar') }}" data-reserva="{{ $reserva->id }}">Autenticar voucher</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 mt-2 mb-3 d-flex flex-column align-self-center">
                            <img src="{{ $reserva->servico->fotoPrincipal->foto_large }}" alt="Foto do serviço" class="imagem-servico-reserva">
                        </div>
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
                            <h2 class="ml-auto mb-0">#{{ $reserva->pedido->cliente->id }}</h2>
                        </div>
                        <div class="col-xl-12 mb-4">
                            <div class="em-separator separator-dashed mt-0 mb-2"></div>
                        </div>
                        <div class="col-xl-4 mb-1 info-terminal">
                            <p class="text-truncate">
                                <i class="la la-user mr-2"></i> Nome completo <br>
                                <strong>{{ $reserva->pedido->cliente->nome }}</strong>
                            </p>
                            <p>
                                <i class="la la-copyright mr-2"></i> CPF <br>
                                <strong>{{ $reserva->pedido->cliente->cpf }}</strong>
                            </p>
                        </div>
                        <div class="col-xl-4 mb-1 info-terminal">
                            <p>
                                <i class="la la-at mr-2"></i> E-mail <br>
                                <strong><a href="mailto:{{ $reserva->pedido->cliente->email }}" class="info-terminal">{{ $reserva->pedido->cliente->email }}</a></strong>
                            </p>
                            <p>
                                <i class="la la-calendar mr-2"></i> Nascimento <br>
                                <strong>{{ $reserva->pedido->cliente->nascimento->format('d/m/Y') }}</strong>
                            </p>
                        </div>
                        <div class="col-xl-4 mb-1 info-terminal">
                            <p>
                                <i class="la la-phone mr-2"></i> Telefone <br>
                                <strong>{{ $reserva->pedido->cliente->telefone }}</strong>
                            </p>
                            <p>
                                <i class="la la-calendar-check-o mr-2"></i> Cadastrado em <br>
                                <strong>{{ $reserva->pedido->cliente->created_at->format('d/m/Y H:i') }}</strong>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($reserva->dadoClienteReservaPedido->count() > 0)
        <div class="row">
            <div class="col-12">
                <div class="widget has-shadow">
                    <div class="widget-body pl-lg-5 pr-lg-5">
                        <div class="row">
                            <div class="col-xl-8 mt-4 mb-3">
                                <div class="section-title mr-auto">
                                    <i class="la la la-users la-3x pull-left pr-4"></i>
                                    <h3>Acompanhantes da reserva</h3>
                                    <p class="mt-1">Informações dos acompanhantes da reserva.</p>
                                </div>
                            </div>
                            <div class="col-xl-4 mt-4 mb-5 d-flex align-items-center">
                                <h2 class="ml-auto mb-0">{{ $reserva->quantidade }} acompanhantes</h2>
                            </div>
                            <div class="col-12 mb-0 mt-2">
                                @foreach($reserva->dadoClienteReservaPedido as $index => $dado_cliente)
                                    @if($dado_cliente->status)
                                        <div class="row {{ ($loop->remaining) ?: "mb-0" }}">
                                            <div class="col-xl-12 mb-2">
                                                <strong class="info-terminal">{{ $index + 1 }}° Acompanhante - {{ $dado_cliente->variacaoServico->nome }}</strong>
                                            </div>
                                            <div class="col-xl-6 mb-3">
                                                <label for="nome_acop_{{ $index }}" class="form-control-label">Nome completo</label>
                                                <div class="input-group">
                                                <span class="input-group-addon addon-secondary">
                                                    <i class="la la-user"></i>
                                                </span>
                                                    <input id="nome_acop_{{ $index }}" type="text" class="form-control" placeholder="Nome completo do acompanhante" disabled
                                                           title="Nome do acompanhante" readonly value="{{ $dado_cliente->nome }}">
                                                </div>
                                            </div>
                                            <div class="col-xl-3 mb-3">
                                                <label for="documento_acop_{{ $index }}" class="form-control-label">Núm. documento </label>
                                                <div class="input-group">
                                                <span class="input-group-addon addon-secondary">
                                                    <i class="la la-file-text"></i>
                                                </span>
                                                    <input id="documento_acop_{{ $index }}" type="text" class="form-control" placeholder="CPF ou RG do acompanhante"
                                                           title="Documento do acompanhante" readonly value="{{ $dado_cliente->documento }}">
                                                </div>
                                            </div>
                                            <div class="col-xl-3 mb-4">
                                                <label for="nascimento_acop_{{ $index }}" class="form-control-label">Nascimento</label>
                                                <div class="input-group">
                                                <span class="input-group-addon addon-secondary">
                                                    <i class="la la-calendar"></i>
                                                </span>
                                                    <input id="nascimento_acop_{{ $index }}" type="tel" class="form-control" placeholder="DD/MM/AAAA" disabled
                                                           title="Nascimento do acompanhante" readonly value="{{ $dado_cliente->nascimento->format('d/m/Y') }}">
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-12">
                <div class="widget has-shadow">
                    <div class="widget-body pl-lg-5 pr-lg-5">
                        <div class="row">
                            <div class="col-xl-8 mt-4 mb-3">
                                <div class="section-title mr-auto">
                                    <i class="la la la-users la-3x pull-left pr-4"></i>
                                    <h3>Quantidade adquirida</h3>
                                    <p class="mt-1">Listagem das opções compradas pelo cliente.</p>
                                </div>
                            </div>
                            <div class="col-xl-4 mt-4 mb-5 d-flex align-items-center">
                                <h2 class="ml-auto mb-0">{{ $reserva->quantidade }} pessoas</h2>
                            </div>
                            <div class="col-12 mb-2 mt-1">
                                <div class="table-responsive">
                                    <table class="table table-divider mb-0">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Variação</th>
                                            <th>Descrição</th>
                                            <th>Valor net</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($reserva->quantidadeReserva as $quantidade_reserva)
                                            @php($status = $quantidade_reserva->status)
                                            <tr class="{{ ($status) ? : "disabled" }}">
                                                <td>{{ $count_quantidade++ }}</td>
                                                <td class="text-primary"><strong>({{ $quantidade_reserva->quantidade }}x) {{ $quantidade_reserva->variacaoServico->nome }}</strong></td>
                                                <td class="text-primary">{{ $quantidade_reserva->variacaoServico->descricao }}</td>
                                                <td class="text-primary">R$ {{ formataValor($quantidade_reserva->valor_net) }}</td>
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

    {{-- Caso a reserva tenha campos adicionais --}}
    @if($qtd_campos_adicionais > 0)
        <div class="row">
            <div class="col-12">
                <div class="widget has-shadow">
                    <div class="widget-body pl-lg-5 pr-lg-5">
                        <div class="row">
                            <div class="col-xl-8 mt-4 mb-3">
                                <div class="section-title mr-auto">
                                    <i class="la la la-file-text la-3x pull-left pr-4"></i>
                                    <h3>Dados adicionais da reserva</h3>
                                    <p class="mt-1">Informações adicionais sobre a reserva.</p>
                                </div>
                            </div>
                            <div class="col-xl-4 mt-4 mb-5 d-flex align-items-center">
                                <h2 class="ml-auto mb-0">{{ $qtd_campos_adicionais }} campos</h2>
                            </div>
                            <div class="col-12 mb-2 mt-1">
                                <div class="form-group row mb-3">
                                    @foreach($reserva->campoAdicionalReservaPedido as $index => $campo_adicional)
                                        <div class="col-xl-{{ $colunas_adicionais }} mb-3">
                                            <label for="adicional_{{ $campo_adicional->id }}" class="form-control-label">{{ $campo_adicional->campoAdicionalServico->campo }}</label>
                                            <input id="adicional_{{ $campo_adicional->id }}" type="text" class="form-control disable-action" placeholder="{{ $campo_adicional->campoAdicionalServico->placeholder }}"
                                                   title="{{ $campo_adicional->campoAdicionalServico->campo }}" value="{{ $campo_adicional->informacao }}" disabled readonly>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($reserva->historicoReservaPedido->count() > 0)
        <div class="row">
            <div class="col-12">
                <div class="widget has-shadow">
                    <div class="widget-body pl-lg-5 pr-lg-5">
                        <div class="row">
                            <div class="col-xl-8 mt-3 mb-3">
                                <div class="section-title mr-auto">
                                    <i class="la la-history la-3x pull-left pr-4"></i>
                                    <h3>Histórico de alterações</h3>
                                    <p class="mt-1">Listagem das alterações realizadas na reserva.</p>
                                </div>
                            </div>
                            <div class="col-xl-4 mt-4 mb-5 d-flex align-items-center">
                                <h2 class="ml-auto mb-0">{{ $reserva->historicoReservaPedido->count() }} alterações</h2>
                            </div>
                            <div class="col-12 mb-2 mt-1">
                                <div class="table-responsive">
                                    <table class="table table-divider mb-0">
                                        <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th>Motivo da alteração</th>
                                            <th class="text-center">Valor total</th>
                                            <th class="text-center">Data da alteração</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($reserva->historicoReservaPedido as $alteracao)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td class="text-primary">{{ $alteracao->motivo_historico }}</td>
                                                <td class="text-center text-primary">R$ {{ formataValor($alteracao->valor) }}</td>
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
