@extends('template.header')

@section('title', 'Serviços pendentes')

@section('content')

    <div class="row">
        <div class="page-header">
            <div class="d-flex align-items-center">
                <h2 class="page-header-title text-gradient-01">Serviços pendentes nos canais de venda</h2>
                <div>{{ Breadcrumbs::render('app.servicos.pendentes.index') }}</div>
            </div>
        </div>
    </div>

    {{-- Informativo sobre a pagina --}}
    <div class="row">
        <div class="col-12">
            <div class="alert alert-secondary mb-4" role="alert">
                <i class="la la-info-circle mr-2"></i>
                O serviços <strong>pendentes</strong> são aqueles que ainda faltam alguma informação para ser cadastrada!
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="widget has-shadow">
                <div class="widget-header bordered no-actions d-flex align-items-center">
                    <h4 class="mr-auto">Mostrando TODOS os serviços pendentes.</h4>
                </div>
                <div class="widget-body">
                    <div class="table-responsive no-overflow">
                        <table class="table table-hover table-border-bottom mb-0 data-table table-mobile">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Nome</th>
                                <th>Fornecedor</th>
                                <th>R$ Valor venda</th>
                                <th>Canal de venda</th>
                                <th class="text-center">Administração</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($servicos as $servico)
                                <tr>
                                    <td data-label="#">{{ $loop->iteration }}</td>
                                    <td data-label="Nome"><a href="{{ route('app.servicos.view', $servico->id) }}" target="_blank">{{ $servico->nome }}</a></td>
                                    <td data-label="Fornecedor">{{ $servico->fornecedor->nome_fantasia }}</td>
                                    <td data-label="Valor de venda">R$ {{ $servico->valor_venda }}</td>
                                    <td data-label="Canal de venda">{{ $servico->canalVenda->site }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('app.servicos.view', $servico->id) }}" target="_blank" class="btn btn-outline-primary">
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

@endsection