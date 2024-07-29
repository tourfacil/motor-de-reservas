@extends('template.master')

@section('title', "Pedido #" . $pedido->codigo)

@section('body')

    {{-- Navbar --}}
    @include('template.navbar')

    <main class="bg-light" data-controller="PedidoCtrl">
        <div class="container pb-5">
            {{-- breadcrumb --}}
            <nav class="custom-bread py-3" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item d-none d-md-flex"><a href="{{ route('ecommerce.index') }}">Tour Fácil</a></li>
                    <li class="breadcrumb-item first-mobile"><a href="{{ route('ecommerce.cliente.pedidos.index') }}">Pedidos realizados</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Pedido: #{{ $pedido->codigo }}</li>
                </ol>
            </nav>
            <div class="p-3 p-sm-4 bg-white shadow-sm radius-10">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h1 class="font-weight-bold h2 mb-1">Resumo do pedido</h1>
                        <p class="line-initial text-muted">Pedido realizado no dia: {{ $pedido->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="d-none d-md-block"><h3>#{{ $pedido->codigo }}</h3></div>
                </div>
                <hr class="blue mt-0 mb-3">
                {{-- Detalhe do pedido realizado no cartao --}}
                @if($pedido->metodo_pagamento == $e_cartao)
                    @include('paginas.cliente.partials.detalhe-cartao')
                @elseif($pedido->metodo_pagamento == \TourFacil\Core\Enum\MetodoPagamentoEnum::PIX)
                    @include('paginas.cliente.partials.detalhe-pix')
                @endif
            </div>

            <div id="imprimir" class="p-3 p-sm-4 bg-white shadow-sm radius-10 mt-3">
                <h2 class="font-weight-bold h2 mb-1">Serviços adquiridos</h2>
                <p class="line-initial text-muted">Baixe ou imprima seus vouchers</p>
                <hr class="blue mt-0 mb-3">
                <div class="table-responsive">
                    <table class="table table-striped table-borderless mb-0">
                        <thead>
                        <tr>
                            <th class="text-center font-weight-medium">Reserva</th>
                            <th class="font-weight-medium">Serviço adquirido</th>
                            <th class="text-center font-weight-medium">Utilização</th>
                            <th class="text-center font-weight-medium">Status</th>
                            <th class="text-center font-weight-medium">Informações</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($pedido->reservas as $reserva)
                            @php
                                //dd($reserva);
                            @endphp
                            <tr>
                                <td data-label="#" class="text-center text-primary font-weight-medium">#{{ $reserva->voucher }}</td>
                                <td data-label="Serviço" class="text-center text-xl-left">({{ $reserva->quantidade }}x) {{ $reserva->servico->nome }}</td>
                                <td data-label="Utilização" class="text-center">{{ $reserva->agendaDataServico->data->format('d/m/Y') }}</td>
                                <td data-label="Status" class="text-center text-{{ $reserva->cor_status }} text-uppercase">
                                    <strong class="font-weight-medium">{{ $reserva->status_reserva }}</strong>
                                </td>
                                @if($reserva->agendaDataServico->data->isAfter(Carbon\Carbon::now()->subDays(1)))
                                    @if(isReservaFinalizada($reserva))
                                        @if($reserva->status == $e_reserva_ativa || $reserva->status == $e_reserva_utilizada)
                                            <td class="text-center pl-0">
                                                {{-- Verifica se é Snowland --}}
                                                @if($reserva->servico->integracao == \TourFacil\Core\Enum\IntegracaoEnum::SNOWLAND)
                                                    <button type="button" data-action="print_voucher" class="btn btn-success btn-rounded text-uppercase pb-2 px-4"
                                                            data-url="{{ $reserva->snowlandVoucher->url_voucher ?? route('ecommerce.cliente.pedidos.print', $reserva->voucher) }}">Imprimir voucher <span class="iconify ml-1" data-icon="jam:printer"></span></button>
                                                @elseif($reserva->servico->integracao == \TourFacil\Core\Enum\IntegracaoEnum::EXCEED_PARK)
                                                    <button type="button" data-action="print_voucher" class="btn btn-success btn-rounded text-uppercase pb-2 px-4"
                                                            data-url="{{ $reserva->exceedVoucher->url_voucher ?? route('ecommerce.cliente.pedidos.print', $reserva->voucher) }}">Imprimir voucher <span class="iconify ml-1" data-icon="jam:printer"></span></button>
                                                @elseif($reserva->servico->integracao == \TourFacil\Core\Enum\IntegracaoEnum::OLIVAS)
                                                    <button type="button" data-action="print_voucher" class="btn btn-success btn-rounded text-uppercase pb-2 px-4"
                                                            data-url="{{ $reserva->olivasVoucher->url_voucher ?? route('ecommerce.cliente.pedidos.print', $reserva->voucher) }}">Imprimir voucher <span class="iconify ml-1" data-icon="jam:printer"></span></button>
                                                @elseif($reserva->servico->integracao == \TourFacil\Core\Enum\IntegracaoEnum::MINI_MUNDO)
                                                    <button type="button" data-action="print_voucher" class="btn btn-success btn-rounded text-uppercase pb-2 px-4"
                                                        data-url="{{ $reserva->miniMundoVoucher->url_voucher ?? route('ecommerce.cliente.pedidos.print', $reserva->voucher) }}">Imprimir voucher <span class="iconify ml-1" data-icon="jam:printer"></span></button>
                                                @elseif($reserva->servico->integracao == \TourFacil\Core\Enum\IntegracaoEnum::DREAMS)
                                                    <button type="button" data-action="print_voucher" class="btn btn-success btn-rounded text-uppercase pb-2 px-4"
                                                        data-url="{{ $reserva->dreamsVoucher->url_voucher ?? route('ecommerce.cliente.pedidos.print', $reserva->voucher) }}">Imprimir voucher <span class="iconify ml-1" data-icon="jam:printer"></span></button>
                                                @elseif($reserva->servico->integracao == \TourFacil\Core\Enum\IntegracaoEnum::ALPEN)
                                                    <button type="button" data-action="print_voucher" class="btn btn-success btn-rounded text-uppercase pb-2 px-4"
                                                        data-url="{{ $reserva->alpenVoucher->url_voucher ?? route('ecommerce.cliente.pedidos.print', $reserva->voucher) }}">Imprimir voucher <span class="iconify ml-1" data-icon="jam:printer"></span></button>
                                                @elseif($reserva->servico->integracao == \TourFacil\Core\Enum\IntegracaoEnum::FANTASTIC_HOUSE)
                                                    <button type="button" data-action="print_voucher" class="btn btn-success btn-rounded text-uppercase pb-2 px-4"
                                                            data-url="{{ $reserva->fantasticHouseVoucher->url_voucher ?? route('ecommerce.cliente.pedidos.print', $reserva->voucher) }}">Imprimir voucher <span class="iconify ml-1" data-icon="jam:printer"></span></button>
                                                @elseif($reserva->servico->integracao == \TourFacil\Core\Enum\IntegracaoEnum::MATRIA)
                                                    <button type="button" data-action="print_voucher" class="btn btn-success btn-rounded text-uppercase pb-2 px-4"
                                                            data-url="{{ $reserva->matriaVoucher->url_voucher ?? route('ecommerce.cliente.pedidos.print', $reserva->voucher) }}">Imprimir voucher <span class="iconify ml-1" data-icon="jam:printer"></span></button>
                                                @elseif($reserva->servico->integracao == \TourFacil\Core\Enum\IntegracaoEnum::VILA_DA_MONICA)
                                                    <button type="button" data-action="print_voucher" class="btn btn-success btn-rounded text-uppercase pb-2 px-4"
                                                            data-url="{{ $reserva->vilaDaMonicaVoucher->url_voucher ?? route('ecommerce.cliente.pedidos.print', $reserva->voucher) }}">Imprimir voucher <span class="iconify ml-1" data-icon="jam:printer"></span></button>
                                                @elseif($reserva->servico->integracao == \TourFacil\Core\Enum\IntegracaoEnum::ACQUA_MOTION)
                                                    <button type="button" data-action="print_voucher" class="btn btn-success btn-rounded text-uppercase pb-2 px-4"
                                                            data-url="{{ $reserva->acquaMotionVoucher->url_voucher ?? route('ecommerce.cliente.pedidos.print', $reserva->voucher) }}">Imprimir voucher <span class="iconify ml-1" data-icon="jam:printer"></span></button>
                                                @elseif($reserva->servico->integracao == \TourFacil\Core\Enum\IntegracaoEnum::NBA_PARK)
                                                    <button type="button" data-action="print_voucher" class="btn btn-success btn-rounded text-uppercase pb-2 px-4"
                                                            data-url="{{ $reserva->nbaParkVoucher->url_voucher ?? route('ecommerce.cliente.pedidos.print', $reserva->voucher) }}">Imprimir voucher <span class="iconify ml-1" data-icon="jam:printer"></span></button>

                                                @else
                                                    <button type="button" data-action="print_voucher" class="btn btn-success btn-rounded text-uppercase pb-2 px-4"
                                                            data-url="{{ route('ecommerce.cliente.pedidos.print', $reserva->voucher) }}">Imprimir voucher <span class="iconify ml-1" data-icon="jam:printer"></span></button>
                                                @endif
                                            </td>
                                        @else
                                            <td class="text-center pl-0">
                                                <button class="btn btn-secondary text-uppercase pb-2 px-4 btn-rounded disabled" disabled>Imprimir voucher</button>
                                            </td>
                                        @endif
                                    @else
                                        <td class="text-center pl-0">
                                            <button id="finalizar-{{$reserva->id}}" style="background-color: #f21729; border-color:#f21729" type="button" class="btn btn-success btn-rounded text-uppercase pb-2 px-4 finalizar-reserva-botao" id_reserva="{{$reserva->id}}">Finalizar reserva <span class="iconify ml-1" data-icon="jam:printer"></span></button>
                                        </td>
                                    @endif
                                @else
                                    @if($reserva->avaliacaoServico == null)
                                        <td class="text-center pl-0">
                                            <button id="avaliar-servico" reserva_id="{{ $reserva->id }}" url_post="{{ route('ecommerce.cliente.pedidos.avaliacoes.store') }}" type="button" class="btn btn-success btn-rounded text-uppercase pb-2 px-4"
                                                    data-url="#">Avaliar serviço <span class="iconify ml-1" data-icon="jam:star"></span></button>
                                        </td>
                                    @else
                                        <td>
                                            <button disabled type="button" class="btn btn-success btn-rounded text-uppercase pb-2 px-4">Serviço realizado</button>
                                        </td>
                                    @endif
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <th colspan="4" class="text-left">
                                <span class="font-weight-medium">Total de {{ $pedido->reservas->count() }} serviço(s) adquirido(s)</span>
                            </th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <div class="links" style="display: none;">
            <span id="link-finalizacao">{{ Route('ecommerce.cliente.pedidos.informacao-finalizacao') }}</span>
            <span id="link-finalizacao-store">{{ Route('ecommerce.cliente.pedidos.informacao-finalizacao-store') }}</span>
        </div>
    </main>
    @include('paginas.modais.lista-acompanhantes-finalizar')
    @include('paginas.modais.campo-adicional-finalizar')
    @include('paginas.modais.avaliacao-servico')
    @include('template.footer')
@endsection
