@extends('template.header')

@section('title', 'Canais de venda')

@section('content')

    <div class="row">
        <div class="page-header">
            <div class="d-flex align-items-center">
                <h2 class="page-header-title text-gradient-01">Canais de venda</h2>
                <div>{{ Breadcrumbs::render('app.canal-venda') }}</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="widget has-shadow">
                <div class="widget-header bordered no-actions d-flex align-items-center">
                    <h4 class="mr-auto">Listagem dos canais de venda</h4>
                </div>
                <div class="widget-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-border-bottom mb-0 table-mobile">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Nome do canal</th>
                                <th>Link do site</th>
                                <th>Status</th>
                                <th class="text-center">Cadastrado</th>
                                <th class="text-center">Administração</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($canais_venda as $canal)
                                <tr>
                                    <td data-label="#">{{ $loop->iteration }}</td>
                                    <td data-label="Canal"><a href="{{ route('app.canal-venda.view', $canal->id) }}">{{ $canal->nome }}</a></td>
                                    <td data-label="Link"><a href="//{{ $canal->site }}" target="_blank">{{ $canal->site }} <i class="ti ti-new-window ml-1"></i></a></td>
                                    <td data-label="Status">
                                        @if($canal->status)
                                            <span class="badge-text badge-text-small info">Ativo</span>
                                        @else
                                            <span class="badge-text badge-text-small danger">Inativo</span>
                                        @endif
                                    </td>
                                    <td data-label="Cadastrado" class="text-center">{{ $canal->created_at->format('d/m/Y H:i') }}</td>
                                    <td data-label="" class="text-center">
                                        <a href="{{ route('app.canal-venda.view', $canal->id) }}" class="btn btn-outline-primary">
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
        <a href="{{ route('app.canal-venda.create') }}" class="btn btn-gradient-01 btn-shadow"><i class="ion ion-plus-round"></i></a>
        <p class="float-tooltip">Cadastrar novo canal</p>
    </div>

@endsection