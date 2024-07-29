@extends('template.header')

@section('title', 'Serviços')

@section('content')

    <div class="row">
        <div class="page-header">
            <div class="d-flex align-items-center">
                <h2 class="page-header-title text-dark">Meus <span class="text-gradient-01">serviços</span></h2>
                <div>{{ Breadcrumbs::render('app.servicos.index') }}</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="widget has-shadow">
                <div class="widget-header bordered no-actions d-flex align-items-center">
                    <h4 class="mr-auto">Mostrando seus serviços anunciados</h4>
                </div>
                <div class="widget-body">
                    <div class="table-responsive no-overflow">
                        <table class="table table-hover table-border-bottom mb-0 data-table table-mobile">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Nome</th>
                                <th>Destino</th>
                                <th>Canal de venda</th>
                                <th>R$ Venda</th>
                                <th class="text-center">Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($servicos as $servico)
                                <tr>
                                    <td data-label="#">{{ $loop->iteration }}</td>
                                    <td data-label="Nome">
                                        <div class="d-inline text-truncate m-0">
                                            <strong class="text-truncate text-primary">{{ $servico->nome }}</strong>
                                        </div>
                                    </td>
                                    <td data-label="Destino" class="text-primary">{{ $servico->destino->nome }}</td>
                                    <td data-label="Fornecedor" class="text-primary">{{ $servico->canalVenda->site }}</td>
                                    <td data-label="R$ venda" class="text-primary">R$ {{ formataValor($servico->valor_venda) }}</td>
                                    <td data-table="Status" class="text-center">
                                        <span class="badge-text badge-text-small {{ $servico->cor_status_servico }}">
                                            {{ $servico->status_servico }}
                                        </span>
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
