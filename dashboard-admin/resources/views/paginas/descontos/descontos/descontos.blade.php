@extends('template.header')

@section('title', 'Cupons')

@section('content')

    <div class="row">
        <div class="page-header">
            <div class="d-flex align-items-center">
                <h2 class="page-header-title text-gradient-01">Descontos</h2>
                <div>{{ Breadcrumbs::render('app.descontos.desconto.index') }}</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="widget has-shadow">
                <div class="widget-header bordered no-actions d-flex align-items-center">
                    <h4 class="mr-auto">Listagem dos descontos</h4>
                </div>
                <div class="widget-body">
                    <div class="table-responsive no-overflow">
                        <table class="table table-hover table-border-bottom mb-0 data-table table-mobile">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>N. interno</th>
                                <th>Desconto (R$ / %)</th>
                                <th>Desconto net (R$ / %)</th>
                                <th>Data inicial</th>
                                <th>Data final</th>
                                <th>Status</th>
                                <th>Administração</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($descontos as $desconto)
                                <tr>
                                    <td>{{ $desconto->id }}</td>
                                    <td>{{ $desconto->nome_interno }}</td>
                                    @if($desconto->tipo_desconto_valor == \TourFacil\Core\Enum\Descontos\TipoDescontoValor::PERCENTUAL)
                                        <td>{{ $desconto->desconto }}%</td>
                                        <td>{{ $desconto->desconto_net }}%</td>
                                    @else
                                        <td>R$ {{ formataValor($desconto->desconto) }}</td>
                                        <td>R$ {{ formataValor($desconto->desconto_net) }}</td>
                                    @endif
                                    <td>{{ $desconto->inicio->format('d/m/Y') }}</td>
                                    <td>{{ $desconto->final->format('d/m/Y') }}</td>
                                    <td>
                                        @if($desconto->status == \TourFacil\Core\Enum\Descontos\StatusDesconto::ATIVO)
                                            <span class="badge-text badge-text-small {{ \TourFacil\Core\Services\DescontoService::getStatusAtual($desconto)['cor'] }} text-center">{{ \TourFacil\Core\Services\DescontoService::getStatusAtual($desconto)['texto'] }}</span>
                                        @elseif($desconto->status == \TourFacil\Core\Enum\Descontos\StatusDesconto::INATIVO)
                                            <span class="badge-text badge-text-small danger text-center">Inativo</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ Route('app.descontos.desconto.edit', ['desconto_id' => $desconto->id]) }}" class="btn btn-outline-primary">
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
        <a href="{{ route('app.descontos.desconto.create') }}" class="btn btn-gradient-01 btn-shadow"><i class="ion ion-plus-round"></i></a>
        <p class="float-tooltip">Cadastrar novo desconto</p>
    </div>

@endsection


