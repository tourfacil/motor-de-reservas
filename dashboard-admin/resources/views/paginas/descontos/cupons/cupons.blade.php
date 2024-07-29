@extends('template.header')

@section('title', 'Cupons')

@section('content')

    <div class="row">
        <div class="page-header">
            <div class="d-flex align-items-center">
                <h2 class="page-header-title text-gradient-01">Cupons de desconto</h2>
                <div>{{ Breadcrumbs::render('app.descontos.cupom.index') }}</div>
            </div>
        </div>
    </div>



    <div class="row">
        <div class="col-12">
            <div class="widget has-shadow">
                <div class="widget-header bordered no-actions d-flex align-items-center">
                    <h4 class="mr-auto">Listagem dos cupons de desconto</h4>
                </div>
                <div class="widget-body">
                    <div class="table-responsive no-overflow">
                        <table class="table table-hover table-border-bottom mb-0 data-table table-mobile">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>N. interno</th>
                                <th>N. público</th>
                                <th>Código</th>
                                <th class="text-center">Desconto</th>
                                <th class="text-center">Status</th>
                                <th>Serviço</th>
                                <th class="text-center">Administração</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($cupons as $cupom)
                                <tr>
                                    <td data-label="#">{{ $cupom->id }}</td>
                                    <td data-label="Nome Interno">
                                        <a href="{{ Route('app.descontos.cupom.edit') }}?cupom_id={{ $cupom->id }}">{{ $cupom->nome_interno }}</a>
                                    </td>
                                    <td data-label="Nome Público">
                                        <a href="{{ Route('app.descontos.cupom.edit') }}?cupom_id={{ $cupom->id }}">{{ $cupom->nome_publico }}</a>
                                    </td>
                                    <td data-label="Codigo">{{ $cupom->codigo }}</td>
                                    @if($cupom->tipo_desconto_valor == \TourFacil\Core\Enum\Descontos\TipoDescontoValor::PERCENTUAL)
                                        <td data-label="Cadastrado" class="text-center">{{ $cupom->desconto }}%</td>
                                    @elseif($cupom->tipo_desconto_valor == \TourFacil\Core\Enum\Descontos\TipoDescontoValor::FIXO)
                                        <td data-label="Cadastrado" class="text-center">R$ {{ number_format($cupom->desconto, 2, ',', '.') }}</td>
                                    @endif

                                    <td data-label="Cadastrado" class="text-center">
                                        @if($cupom->status == \TourFacil\Core\Enum\Descontos\StatusDesconto::ATIVO)
                                            <span class="badge-text badge-text-small info text-center">Ativo</span>
                                        @elseif($cupom->status == \TourFacil\Core\Enum\Descontos\StatusDesconto::INATIVO)
                                            <span class="badge-text badge-text-small danger text-center">Inativo</span>
                                        @endif
                                    </td>
                                    @if($cupom->servico_id != null)
                                        <td data-label="Servico">{{ $cupom->servico->nome }}</td>
                                    @else
                                        <td data-label="Servico">Todos</td>
                                    @endif
                                    <td data-label="" class="text-center">
                                        <a href="{{ route('app.descontos.cupom.edit') }}?cupom_id={{ $cupom->id }}" class="btn btn-outline-primary">
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
        <a href="{{ route('app.descontos.cupom.create') }}" class="btn btn-gradient-01 btn-shadow"><i class="ion ion-plus-round"></i></a>
        <p class="float-tooltip">Cadastrar novo cupom de desconto</p>
    </div>

@endsection


