@extends('template.header')

@section('title', 'Fornecedores')

@section('content')

    <div class="row">
        <div class="page-header">
            <div class="d-flex align-items-center">
                <h2 class="page-header-title text-gradient-01">Fornecedores</h2>
                <div>{{ Breadcrumbs::render('app.fornecedores.index') }}</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="widget has-shadow">
                <div class="widget-header bordered no-actions d-flex align-items-center">
                    <h4 class="mr-auto">Listagem dos fornecedores de serviços</h4>
                </div>
                <div class="widget-body">
                    <div class="table-responsive no-overflow">
                        <table class="table table-hover table-border-bottom mb-0 data-table table-mobile">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Nome fantasia</th>
                                <th>CNPJ</th>
                                <th>Status</th>
                                <th class="text-center">Cadastrado</th>
                                <th class="text-center">Administração</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($fornecedores as $fornecedor)
                                <tr>
                                    <td data-label="#">{{ $loop->iteration }}</td>
                                    <td data-label="Nome fantasia"><a href="{{ route('app.fornecedores.view', $fornecedor->id) }}">{{ $fornecedor->nome_fantasia }}</a></td>
                                    <td data-label="CNPJ">{{ $fornecedor->cnpj }}</td>
                                    <td data-label="Status">
                                        @if($fornecedor->status)
                                            <span class="badge-text badge-text-small info">Ativo</span>
                                        @else
                                            <span class="badge-text badge-text-small danger">Inativo</span>
                                        @endif
                                    </td>
                                    <td data-label="Cadastrado" class="text-center">{{ $fornecedor->created_at->format('d/m/Y') }}</td>
                                    <td data-label="" class="text-center">
                                        <a href="{{ route('app.fornecedores.view', $fornecedor->id) }}" class="btn btn-outline-primary">
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
        <a href="{{ route('app.fornecedores.create') }}" class="btn btn-gradient-01 btn-shadow"><i class="ion ion-plus-round"></i></a>
        <p class="float-tooltip">Cadastrar novo fornecedor</p>
    </div>

@endsection