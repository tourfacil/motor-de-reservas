@extends('template.header')

@section('title', 'Agenda serviços')

@section('content')

    <div class="row">
        <div class="page-header">
            <div class="d-flex align-items-center">
                <h2 class="page-header-title text-dark">Agenda serviços | <span class="text-gradient-01">{{ $canal_venda->nome }}</span></h2>
                <div>{{ Breadcrumbs::render('app.agenda.index') }}</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="widget has-shadow">
                <div class="widget-header bordered no-actions d-flex align-items-center">
                    <h4 class="mr-auto">Mostrando as agendas do {{ $canal_venda->site }}</h4>
                </div>
                <div class="widget-body">
                    <div class="table-responsive no-overflow">
                        <table class="table table-hover table-border-bottom mb-0 data-table table-mobile">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Serviço</th>
                                <th>Fornecedor</th>
                                <th>Destino</th>
                                <th class="text-center">Status agenda</th>
                                <th class="text-center">Administração</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($servicos as $servico)
                                <tr>
                                    <td data-label="#">{{ $loop->iteration }}</td>
                                    <td data-label="Serviço">
                                        <div class="d-inline text-truncate m-0 is-95">
                                            <a href="{{ route('app.agenda.view', $servico->agenda->id) }}">
                                                <span class="text-truncate">{{ $servico->nome }}</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td data-label="Fornecedor">{{ $servico->fornecedor->nome_fantasia }}</td>
                                    <td data-label="Destino">{{ $servico->destino->nome }}</td>
                                    <td data-label="Status agenda" class="text-center">
                                        @if($servico->agenda->status === $com_disponivel)
                                            <span class="badge-text badge-text-small success">{{ $servico->agenda->status_agenda }}</span>
                                        @elseif($servico->agenda->status === $sem_disponibilidade)
                                            <span class="badge-text badge-text-small danger">{{ $servico->agenda->status_agenda }}</span>
                                        @elseif($servico->agenda->status === $baixa_disponibilidade)
                                            <span class="badge-text badge-text-small info">{{ $servico->agenda->status_agenda }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('app.agenda.view', $servico->agenda->id) }}" class="btn btn-outline-primary">
                                            Adm. agenda <i class="la la-external-link right"></i>
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
