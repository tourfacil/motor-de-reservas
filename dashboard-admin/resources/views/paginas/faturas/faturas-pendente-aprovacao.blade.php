@extends('template.header')

@section('title', 'Faturas')

@section('content')

    <div class="row">
        <div class="page-header">
            <div class="d-flex align-items-center">
                <h2 class="page-header-title text-gradient-01">Faturas para aprovação</h2>
                <div>{{ Breadcrumbs::render('app.descontos.cupom.index') }}</div>
            </div>
        </div>
    </div>



    <div class="row">
        <div class="col-12">
            <div class="widget has-shadow">
                <div class="widget-header bordered no-actions d-flex align-items-center">
                    <h4 class="mr-auto">Listagem das faturas pendentes de aprovação do Tour Fácil</h4>
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
                                <th>Apr. Interna</th>
                                <th>Apr. Externa</th>
                                <th>Tipo</th>
                                <th>Tipo Período</th>
                                <th>Valor (R$)</th>
                                <th>Ações</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($faturas as $fatura)
                                <tr style="color: #5d5386;">
                                    <td>{{ $fatura->id }}</td>
                                    <td>{{ $fatura->fornecedor->nome_fantasia }}</td>
                                    <td>{{ $fatura->inicio->format('d/m/Y') }}</td>
                                    <td>{{ $fatura->final->format('d/m/Y') }}</td>
                                    <td>{{ $fatura->aprovacao_interna ? 'SIM' : 'NÃO' }}</td>
                                    <td>{{ $fatura->aprovacao_externa ? 'SIM' : 'NÃO' }}</td>
                                    <td>{{ $fatura->tipo }}</td>
                                    <td>{{ $fatura->tipo_periodo }}</td>
                                    <td>R${{ formataValor($fatura->valor) }}</td>
                                    <td>
                                        <a href="#" class="btn btn-outline-primary">
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


