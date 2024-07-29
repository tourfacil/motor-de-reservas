@extends('template.header')

@section('title', 'Faturas')

@section('content')

    <div class="row">
        <div class="page-header">
            <div class="d-flex align-items-center">
                <h2 class="page-header-title text-gradient-01">Faturas</h2>
                <div>{{ Breadcrumbs::render('app.faturas.index') }}</div>
            </div>
        </div>
    </div>



    <div class="row">
        <div class="col-12">
            <div class="widget has-shadow">
                <div class="widget-header bordered no-actions d-flex align-items-center">
                    <h4 class="mr-auto">Listagem das faturas do Tour Fácil</h4>
                </div>
                <div class="widget-body">
                    <div class="table-responsive no-overflow">
                        <table class="table table-hover table-border-bottom mb-0 data-table table-mobile">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Fornecedor</th>
                                <th>Data Início</th>
                                <th>Data Final</th>
                                <th>Status</th>
                                <th>Tipo</th>
                                <th>Tipo Período</th>
                                <th class="text-center">Valor (R$)</th>
                                <th>Ações</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($faturas as $fatura)
                                <tr style="color: #5d5386;">
                                    <td>
                                        <a href="{{ Route('app.faturas.show') . '?fatura_id=' . $fatura->id }}" class="text-secondary">#{{ $fatura->id }}</a>
                                    </td>
                                    <td>{{ $fatura->fornecedor->nome_fantasia }}</td>
                                    <td>{{ $fatura->inicio->format('d/m/Y') }}</td>
                                    <td>{{ $fatura->final->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge-text badge-text-small {{ \TourFacil\Core\Enum\Faturas\StatusFaturaEnum::CORES[$fatura->status] }}">
                                            {{ \TourFacil\Core\Enum\Faturas\StatusFaturaEnum::STATUS[$fatura->status]}}</td>
                                        </span> 
                                    <td>{{ $fatura->tipo }}</td>
                                    <td>{{ $fatura->tipo_periodo }}</td>
                                    <td class="text-warning text-center font-weight-bold">R${{ formataValor($fatura->valor) }}</td>
                                    <td>
                                        <a href="{{ Route('app.faturas.show') . '?fatura_id=' . $fatura->id }}" target="_blank" class="btn btn-outline-primary">
                                            Revisar <i class="la la-edit right"></i>
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


