@extends('template.header')

@section('title', 'Terminais CDI')

@section('content')

    <div class="row">
        <div class="page-header">
            <div class="d-flex align-items-center">
                <h2 class="page-header-title text-dark">
                    <span class="text-gradient-01">Terminais CDI </span>
                    <small> - {{ $url_terminais }}</small>
                </h2>
                <div>{{ Breadcrumbs::render('app.terminais.index') }}</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="widget has-shadow">
                <div class="widget-header bordered no-actions d-flex align-items-center">
                    <h4 class="mr-auto">Pontos de vendas dos ingressos</h4>
                </div>
                <div class="widget-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-border-bottom mb-0 data-table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Nome</th>
                                <th>Identificação</th>
                                <th>Fabricante</th>
                                <th>Localização</th>
                                <th>Status</th>
                                <th class="text-center">Administração</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($terminais as $terminal)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><a href="{{ route('app.terminais.view', $terminal->id) }}">{{ $terminal->nome }}</a></td>
                                    <td>{{ $terminal->identificacao }}</td>
                                    <td>{{ $terminal->nome_fabricante }}</td>
                                    <td>{{ $terminal->cidade }}-{{ $terminal->estado }}</td>
                                    <td>
                                        @if($terminal->status)
                                            <span class="badge-text badge-text-small info">Ativo</span>
                                        @else
                                            <span class="badge-text badge-text-small danger">Inativo</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('app.terminais.view', $terminal->id) }}" class="btn btn-outline-primary">
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
        <a href="{{ route('app.terminais.create') }}" class="btn btn-gradient-01 btn-shadow"><i class="ion ion-plus-round"></i></a>
        <p class="float-tooltip">Cadastrar novo terminal</p>
    </div>

@endsection