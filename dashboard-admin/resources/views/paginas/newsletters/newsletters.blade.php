@extends('template.header')

@section('title', 'Newsletters ' . $canal_venda->nome)

@section('content')

    <div class="row">
        <div class="page-header">
            <div class="d-flex align-items-center">
                <h2 class="page-header-title text-dark">Newsletters <span class="text-gradient-01">{{ $canal_venda->nome }}</span></h2>
                <div>{{ Breadcrumbs::render('app.newsletter.index') }}</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="widget has-shadow">
                <div class="widget-header p-3 bordered">
                    <div class="row d-flex align-items-center">
                        <div class="col-8">
                            <div class="ml-2">
                                <h3>Listagem dos emails cadastrados</h3>
                            </div>
                        </div>
                        <div class="col-4 text-right">
                            <div class="form-group pl-2 m-0">
                                <a href="{{ route('app.newsletter.download') }}" target="_blank"
                                   class="btn btn-secondary line-height-inherit">Baixar XLS <i class="la la-download right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="widget-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-border-bottom mb-0 data-table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Email cadastrado</th>
                                <th>Data e hora</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($newsletters as $newsletter)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="text-primary">{{ $newsletter->email }}</td>
                                    <td class="text-primary">{{ $newsletter->created_at->format('d/m/Y H:i') }}</td>
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
