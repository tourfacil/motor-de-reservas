@extends('template.header')

@section('title', 'Serviços do ' . $canal_venda->nome)

@section('content')

    <div class="row">
        <div class="page-header">
            <div class="d-flex align-items-center">
                <h2 class="page-header-title text-dark">Serviços do <span class="text-gradient-01">{{ $canal_venda->nome }}</span></h2>
                <div>{{ Breadcrumbs::render('app.servicos.index') }}</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="widget has-shadow">
                <div class="widget-header bordered no-actions d-flex align-items-center">
                    <h4 class="mr-auto">Mostrando os serviços: {{ $canal_venda->site }}</h4>
                </div>
                <div class="widget-body">
                    <div class="table-responsive no-overflow">
                        <table class="table table-hover table-border-bottom mb-0 data-table table-mobile">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Nome</th>
                                <th>Destino</th>
                                <th>Fornecedor</th>
                                <th>R$ Venda</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Administração</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($servicos as $servico)
                                <tr>
                                    <td data-label="#">{{ $loop->iteration }}</td>
                                    <td data-label="Nome">
                                        <div class="d-inline text-truncate m-0">
                                            <a href="{{ route('app.servicos.view', $servico->id) }}" class="text-truncate" target="_blank">
                                                <strong>{{ $servico->nome }}</strong>
                                            </a>
                                        </div>
                                    </td>
                                    <td data-label="Destino" class="text-primary">{{ $servico->destino->nome }}</td>
                                    <td data-label="Fornecedor" class="text-primary">{{ $servico->fornecedor->nome_fantasia }}</td>
                                    <td data-label="R$ venda" class="text-primary">R$ {{ formataValor($servico->valor_venda) }}</td>
                                    <td data-table="Status" class="text-center">
                                        <span class="badge-text badge-text-small {{ $servico->cor_status_servico }}">
                                            {{ $servico->status_servico }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('app.servicos.view', $servico->id) }}" class="btn btn-outline-primary">
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

    <div class="float-button">
        <a href="{{ route('app.servicos.create') }}" class="btn btn-gradient-01 btn-shadow"><i class="ion ion-plus-round"></i></a>
        <p class="float-tooltip">Cadastrar novo serviço</p>
    </div>

@endsection
