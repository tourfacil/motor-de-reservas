@extends('template.header')

@section('title', 'Clientes')

@section('content')

    <div class="row">
        <div class="page-header">
            <div class="d-flex align-items-center">
                <h2 class="page-header-title text-dark">Gerenciador de <span class="text-gradient-01">clientes</span></h2>
                <div>{{ Breadcrumbs::render('app.clientes.index') }}</div>
            </div>
        </div>
    </div>

    <div class="row" data-controller="ClientesCtrl">
        <div class="col-12">
            <div class="widget has-shadow">
                <div class="widget-header bordered no-actions d-flex align-items-center">
                    <h4 class="mr-auto">Mostrando os últimos 100 clientes cadastrados</h4>
                </div>
                <div class="widget-body">
                    <div class="table-responsive no-overflow">
                        <table class="table table-hover table-border-bottom mb-0 data-table table-mobile">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Nome completo</th>
                                <th>E-mail de cadastro</th>
                                <th>CPF - Documento</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Administração</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($clientes as $cliente)
                                <tr>
                                    <td data-label="#">{{ $loop->iteration }}</td>
                                    <td data-label="Nome">
                                        <div class="d-inline text-truncate m-0 is-95">
                                            <a class="text-truncate text-secondary" href="{{ route('app.clientes.view', $cliente->id) }}">
                                                <strong class="text-truncate text-primary">{{ $cliente->nome }}</strong>
                                            </a>
                                        </div>
                                    </td>
                                    <td data-label="E-mail" class="text-primary">
                                        <div class="d-inline text-truncate m-0 is-95">
                                            <span class="text-truncate">{{ $cliente->email }}</span>
                                        </div>
                                    </td>
                                    <td data-label="CPF" class="text-primary">{{ $cliente->cpf }}</td>
                                    <td data-label="Status" class="text-center">
                                        @if($cliente->status)
                                            <span class="badge-text badge-text-small success">Ativo</span>
                                        @else
                                            <span class="badge-text badge-text-small danger">Inativo</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('app.clientes.view', $cliente->id) }}" class="btn btn-outline-primary">
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

    {{-- Modal para procurar o cliente --}}
    <div id="search-cliente" class="modal modal-top fade ">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Procurar cliente</h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">×</span>
                        <span class="sr-only">close</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row mb-0">
                        <div class="col-12">
                            <ul class="nav nav-justified nav-tabs">
                                <li class="nav-item">
                                    <a class="nav-link not-save-state active show" data-toggle="tab" href="#nome_email">Dados cliente</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link not-save-state" data-toggle="tab" href="#cod_pedido">Código pedido</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link not-save-state" data-toggle="tab" href="#tid_cartao">TID ou Cartão</a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-xl-12 mb-3 tab-content pt-4">
                            <div class="tab-content">
                                <div class="tab-pane active show" id="nome_email">
                                    <div class="input-group">
                                        <input id="cpf_email" type="search" class="form-control" placeholder="Nome, CPF ou E-mail"
                                               title="Pesquise o cliente aqui" name="q" required data-required data-min="2" data-route="{{ route('app.clientes.search') }}">
                                        <span class="input-group-addon addon-secondary right"><i class="la la-search"></i></span>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="cod_pedido">
                                    <div class="input-group">
                                        <input id="find_pedido" type="search" class="form-control" placeholder="Código do pedido ou reserva"
                                               title="Pesquise pelo código do pedido ou reserva" name="q" required data-required data-min="2" data-route="{{ route('app.clientes.search-pedido') }}">
                                        <span class="input-group-addon addon-secondary right"><i class="la la-search"></i></span>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tid_cartao">
                                    <div class="input-group">
                                        <input id="tid_cartao" type="search" class="form-control" placeholder="TID ou Número do cartão"
                                               title="Pesquise pelo TID ou Cartão" name="q" required data-required data-min="2" data-route="{{ route('app.clientes.search-tid') }}">
                                        <span class="input-group-addon addon-secondary right"><i class="la la-search"></i></span>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="em-separator separator-dashed mt-2 mb-2"></div>
                    <div class="list-result widget-18">
                        <ul class="list-group w-100 list-results"></ul>
                    </div>
                    <div class="start-result">
                        <p class="text-center mt-3 mb-3">Os resultados aparecerão aqui...</p>
                    </div>
                    <div class="not-result hide">
                        <p class="text-center mt-3 mb-3">Nenhum resultado encontrado!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="float-button">
        <button data-target="#search-cliente" data-toggle="modal" class="btn btn-gradient-01 btn-shadow"><i class="ion ion-search"></i></button>
        <p class="float-tooltip">Procurar cliente</p>
    </div>

@endsection
