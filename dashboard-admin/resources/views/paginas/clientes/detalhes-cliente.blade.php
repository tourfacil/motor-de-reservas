@extends('template.header')

@section('title', 'Detalhes do cliente ' . $cliente->nome)

@section('content')

    @php($index = 1)

    <div class="row">
        <div class="page-header pb-4">
            <div class="d-flex align-items-center">
                <h2 class="page-header-title text-dark">Cliente <span class="text-gradient-01">{{ $cliente->nome }}</span></h2>
                <div>{{ Breadcrumbs::render('app.clientes.view') }}</div>
            </div>
        </div>
    </div>

    {{-- Informativo sobre o status da reserva --}}
    <div class="row">
        <div class="col-12">
            @if($cliente->status == false)
                <div class="alert alert-danger mb-4" role="alert">
                    <i class="la la-ban mr-2"></i>
                    Essa cliente foi <strong>desativado</strong> no dia {{ $cliente->deleted_at->format('d/m/Y H:i') }}
                </div>
            @endif
        </div>
    </div>

    <div class="row" data-controller="ClientesCtrl">
        <div class="col-12">
            <div class="widget has-shadow info-pedido">
                <div class="widget-body pl-lg-5 pr-lg-5 pb-2">
                    <div class="row">
                        <div class="col-xl-8 mt-3 mb-2">
                            <div class="section-title mr-auto">
                                <i class="la la-user la-3x pull-left pr-4"></i>
                                <h3>Informações do cliente</h3>
                                <p class="mt-1">Cadastro feito no site <strong class="text-primary">{{ $cliente->canalVenda->site }}</strong></p>
                            </div>
                        </div>
                        <div class="col-xl-4 mt-3 mb-4 d-flex align-items-center">
                            <h2 class="ml-auto mb-0">#{{ $cliente->id }}</h2>
                        </div>
                        <div class="col-xl-12 mb-4">
                            <div class="em-separator separator-dashed mt-0 mb-2"></div>
                        </div>
                        <div class="col-xl-4 mb-1 info-terminal">
                            <p>
                                <i class="la la-user mr-2"></i> Nome completo <br>
                                <strong class="text-secondary">
                                    {{ $cliente->nome }}
                                </strong>
                            </p>
                            <p>
                                <i class="la la-at mr-2"></i> E-mail <br>
                                <strong><a href="mailto:{{ $cliente->email }}" class="info-terminal">{{ $cliente->email }}</a></strong>
                            </p>
                        </div>
                        <div class="col-xl-3 mb-1 info-terminal">
                            <p>
                                <i class="la la-copyright mr-2"></i> CPF <br>
                                <strong>{{ $cliente->cpf ?? 'Sem dados' }}</strong>
                            </p>
                            <p>
                                <i class="la la-calendar mr-2"></i> Nascimento <br>
                                <strong>{{ (is_object($cliente->nascimento)) ? $cliente->nascimento->format('d/m/Y') : "Sem dados" }}</strong>
                            </p>
                        </div>
                        <div class="col-xl-2 mb-1 info-terminal">
                            <p>
                                <i class="la la-phone mr-2"></i> Telefone <br>
                                <strong>{{ $cliente->telefone ?? 'Sem dados' }}</strong>
                            </p>
                            <p>
                                <i class="la la-calendar-check-o mr-2"></i> Cadastrado em <br>
                                <strong>{{ $cliente->created_at->format('d/m/Y H:i') }}</strong>
                            </p>
                        </div>
                        <div class="col-xl-3 d-flex flex-column align-self-center pl-xl-5">
                            @if($cliente->status)
                                <button type="button" data-toggle="modal" class="btn btn-gradient-02 btn-block mb-3 disable-action"
                                        data-target="#edit_cliente" {{ userIsAdmin() ? "" : "disabled" }}>Editar dados do cliente</button>

                                <button type="button" class="btn btn-gradient-03 btn-block mb-3 disable-action" data-action="resetar_senha"
                                        data-route="{{ route('app.clientes.resetar-senha') }}" data-id="{{ $cliente->id }}" {{ userIsAdmin() ? "" : "disabled" }}>Gerar nova senha</button>

                                <button type="button" class="btn btn-gradient-01 btn-block mb-3" data-action="desativar_cliente"
                                        data-route="{{ route('app.clientes.delete') }}" data-id="{{ $cliente->id }}" {{ userIsAdmin() ? "" : "disabled" }}>Desativar cliente</button>
                            @else
                                <button type="button" class="btn btn-primary btn-block mb-3" data-action="reativar_cliente"
                                        data-route="{{ route('app.clientes.restore') }}" data-id="{{ $cliente->id }}" {{ userIsAdmin() ? "" : "disabled" }}>Reativar cliente <i class="la la-level-up right"></i></button>
                            @endif
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
                                <h3>Pedidos realizados</h3>
                                <p class="mt-1">Listagem dos pedidos realizados pelo cliente.</p>
                            </div>
                        </div>
                        <div class="col-xl-4 mt-4 mb-5 d-flex align-items-center">
                            <h2 class="ml-auto mb-0">{{ $cliente->reservas->count() }} reserva(s)</h2>
                        </div>
                        <div class="col-12 mb-2 mt-1">
                            <div class="table-responsive">
                                <table class="table table-divider mb-0 table-mobile">
                                    <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Reserva</th>
                                        <th>Serviço adquirido</th>
                                        <th class="text-center">Utilização</th>
                                        <th class="text-center">Valor total</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Data da compra</th>
                                        <th class="text-center">Informações</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($cliente->reservas as $reserva)
                                        <tr>
                                            <td data-label="#" class="text-center">{{ $index++ }}</td>
                                            <td data-label="Reserva" class="text-primary">
                                                <a href="{{ route('app.reservas.view', $reserva->voucher) }}" target="_blank" class="text-secondary">#{{ $reserva->voucher }}</a>
                                            </td>
                                            <td data-label="Serviço" class="text-primary">
                                                <div class="d-inline text-truncate m-0">
                                                    <strong>({{ $reserva->quantidade }}x)</strong>
                                                    <span class="text-truncate">{{ $reserva->servico->nome }}</span>
                                                </div>
                                            </td>
                                            <td data-label="Utilização" class="text-center text-primary">
                                                <strong>{{ $reserva->agendaDataServico->data->format('d/m/Y') }}</strong>
                                            </td>
                                            <td data-label="Total" class="text-center text-primary">R$ {{ formataValor($reserva->valor_total) }}</td>
                                            <td data-label="Status" class="text-center">
                                                <strong class="text-{{ $reserva->cor_status }} text-uppercase">
                                                    {{ $reserva->status_reserva }}
                                                </strong>
                                            </td>
                                            <td data-label="Data da compra" class="text-primary text-center">{{ $reserva->created_at->format('d/m/Y H:i') }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('app.reservas.view', $reserva->voucher) }}" target="_blank" class="btn btn-gradient-01 mt-2 mb-2">Ver reserva</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8">
                                                <p class="text-center text-primary mt-2 mb-2">O cliente não realizou nenhum pedido ainda!</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="edit_cliente" class="modal modal-top fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Editar cliente</h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">×</span>
                        <span class="sr-only">close</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form data-validate-ajax method="POST" action="{{ route('app.clientes.update') }}" class="form-horizontal">
                        <div class="form-group row">
                            <div class="col-xl-12 mb-3 mt-1">
                                <label for="nome" class="form-control-label">Nome completo</label>
                                <div class="input-group">
                                    <span class="input-group-addon addon-secondary left"><i class="la la-user"></i></span>
                                    <input id="nome" type="text" class="form-control" placeholder="Nome e sobrenome" required
                                           data-required data-min="2" title="Nome completo" data-auto-capitalize name="nome" value="{{ $cliente->nome }}">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-xl-12 mb-3 mt-1">
                                <label for="email" class="form-control-label">E-mail</label>
                                <div class="input-group">
                                    <span class="input-group-addon addon-secondary left"><i class="la la-at"></i></span>
                                    <input id="email" type="email" class="form-control" placeholder="nome.sobrenome@email.com.br" required
                                           data-required data-min="2" title="Email do cliente" name="email" value="{{ $cliente->email }}">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-xl-12 mb-3 mt-1">
                                <label for="cpf" class="form-control-label">Documento CPF</label>
                                <div class="input-group">
                                    <span class="input-group-addon addon-secondary left"><i class="la la-copyright"></i></span>
                                    <input id="cpf" type="tel" class="form-control vanillaMask" placeholder="000.000.000-00" required
                                           data-required data-min="14" title="Documento CPF" data-mask="cpf" name="cpf" value="{{ $cliente->cpf }}">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-xl-6 mb-1 mt-1">
                                <label for="nascimento" class="form-control-label">Nascimento</label>
                                <div class="input-group">
                                    <span class="input-group-addon addon-secondary left"><i class="la la-calendar"></i></span>
                                    <input id="nascimento" type="tel" class="form-control vanillaMask" placeholder="DD/MM/AAAA" required
                                           data-required data-min="10" title="Nascimento" data-mask="date" name="nascimento" value="{{ (is_object($cliente->nascimento)) ? $cliente->nascimento->format('d/m/Y') : "" }}">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-xl-6 mb-1 mt-1">
                                <label for="telefone" class="form-control-label">Telefone</label>
                                <div class="input-group">
                                    <span class="input-group-addon addon-secondary left"><i class="la la-phone"></i></span>
                                    <input id="telefone" type="tel" class="form-control vanillaMask" placeholder="(00) 0 0000-0000" required
                                           data-required data-min="14" title="Telefone" data-mask="phone" name="telefone" value="{{ $cliente->telefone }}">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="hide">
                            <input type="hidden" name="cliente_id" value="{{ $cliente->id }}">
                        </div>
                        <div class="em-separator separator-dashed"></div>
                        <div class="text-center mt-3">
                            <button class="btn btn-success">Atualizar dados <i class="la la-angle-right right"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
