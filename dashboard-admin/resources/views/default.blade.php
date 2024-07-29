@extends('template.header')

@section('title', 'Default ')

@section('content')

    <div class="row">
        <div class="page-header">
            <div class="d-flex align-items-center">
                <h2 class="page-header-title text-dark">Página <span class="text-gradient-01">default</span></h2>
                <div>{{ Breadcrumbs::render('app.categorias.index') }}</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="widget has-shadow">
                <div class="widget-header bordered no-actions d-flex align-items-center">
                    <h4 class="mr-auto">Mostrando as categorias: </h4>
                </div>
                <div class="widget-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-border-bottom mb-0 data-table">
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
