@extends('template.header')

@section('title', 'Categorias do ' . $canal_venda->nome)

@section('content')

    <div class="row">
        <div class="page-header">
            <div class="d-flex align-items-center">
                <h2 class="page-header-title text-dark">Categorias do <span class="text-gradient-01">{{ $canal_venda->nome }}</span></h2>
                <div>{{ Breadcrumbs::render('app.categorias.index') }}</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="widget has-shadow">
                <div class="widget-header bordered no-actions d-flex align-items-center">
                    <h4 class="mr-auto">Mostrando as categorias: {{ $canal_venda->site }}</h4>
                </div>
                <div class="widget-body">
                    <div class="table-responsive no-overflow">
                        <table class="table table-hover table-border-bottom mb-0 data-table table-mobile">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Categoria</th>
                                <th>Destino</th>
                                <th>Status</th>
                                <th class="text-center">Cadastrado</th>
                                <th class="text-center">Administração</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($destino_categorias as $destino)
                                @foreach($destino->categorias as $categoria)
                                    <tr>
                                        <td data-label="#">{{ $loop->iteration }}</td>
                                        <td data-label="Categoria"><a href="{{ route('app.categorias.view', $categoria->id) }}">{{ $categoria->nome }}</a></td>
                                        <td data-label="Destino">{{ $destino->nome }}</td>
                                        <td data-label="Status">
                                            @if($categoria->status)
                                                <span class="badge-text badge-text-small info">Ativo</span>
                                            @else
                                                <span class="badge-text badge-text-small danger">Inativo</span>
                                            @endif
                                        </td>
                                        <td data-label="Cadastrado" class="text-center">{{ $categoria->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('app.categorias.view', $categoria->id) }}" class="btn btn-outline-primary">
                                                Editar <i class="la la-edit right"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="float-button">
        <a href="{{ route('app.categorias.create') }}" class="btn btn-gradient-01 btn-shadow"><i class="ion ion-plus-round"></i></a>
        <p class="float-tooltip">Cadastrar nova categoria</p>
    </div>

@endsection
