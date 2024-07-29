@extends('template.header')

@section('title', 'Relatório de cupons')

@section('content')

    <div class="row">
        <div class="page-header">
            <div class="d-flex align-items-center">
                <h2 class="page-header-title text-gradient-01" >Cupom de desconto</h2>
                <div>{{ Breadcrumbs::render('app.descontos.cupom.relatorio') }}</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="widget has-shadow">
                <div class="widget-header bordered no-actions d-flex align-items-center">
                    @if(isset($cupom))
                        <h4 class="mr-auto">Pedidos com o cupom <b>{{ $cupom->nome_interno }}</b> ({{ $cupom->codigo }})</h4>
                    @else
                        <h4 tool class="mr-auto">Pedidos com cupom de desconto</h4>
                    @endif
                </div>
                <div class="widget-body">
                    <div class="table-responsive no-overflow">
                        <table class="table table-hover table-border-bottom mb-0 data-table table-mobile">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Data de venda</th>
                                    <th>Cupom</th>
                                    <th>Código</th>
                                    <th>Valor de venda</th>
                                    <th>Serviço</th>
                                    <th class="text-center">Status</th> 
                                    <th class="text-center">Administração</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pedidos as $pedido)
                                    <tr style="color: #5D5386">
                                        <td>{{ $pedido->codigo }}</td>
                                        <td>{{ $pedido->created_at->format('d/m/Y') }}</td>
                                        <td data-toggle="tooltip" data-placement="left" title="Clique para ver somente cupom {{ $pedido->cupom->nome_interno }}"><a href="{{ Route('app.descontos.cupom.relatorio', ['cupom' => $pedido->cupom->id]) }}">{{ $pedido->cupom->nome_interno }}</a></td>
                                        <td data-toggle="tooltip" data-placement="left" title="Clique para ver somente cupom {{ $pedido->cupom->nome_interno }}"><a href="{{ Route('app.descontos.cupom.relatorio', ['cupom' => $pedido->cupom->id]) }}">{{ $pedido->cupom->codigo }}</a></td>
                                        <td>R$ {{ formataValor($pedido->valor_total + $pedido->juros) }}</td>
                                        <td>{{ $pedido->cupom->servico->nome ?? 'Todos' }}</td>
                                        <td class="text-center"><span class="badge-text badge-text-small {{ $pedido->status == 'PAGO' ? 'success' : 'warning' }}">{{ $pedido->status }}</span></td>
                                        <td class="text-center">
                                            <a href="{{ Route('app.pedidos.view', $pedido->codigo) }}" class="btn btn-outline-primary">
                                                Ver <i class="la la-edit right"></i>
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

    <script>
        window.addEventListener('load', function() {
            $('[data-toggle="tooltip"]').tooltip()
        });
    </script>

@endsection


