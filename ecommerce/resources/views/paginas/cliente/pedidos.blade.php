@extends('template.master')

@section('title', "Pedidos realizados")

@section('body')

    {{-- Navbar --}}
    @include('template.navbar')

    <main class="bg-light">
        <div class="container pb-5">
            {{-- breadcrumb --}}
            <nav class="custom-bread py-3" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('ecommerce.index') }}">Tour Fácil</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Pedidos realizados</li>
                </ol>
            </nav>
            <div class="p-3 p-sm-4 bg-white shadow-sm radius-10">
                <h1 class="font-weight-bold h2 mb-1">Pedidos realizados</h1>
                <p class="line-initial text-muted">Gerencie seus pedidos realizados em nosso site.</p>
                <hr class="blue mt-0 mb-3">
                <div class="table-responsive">
                    <table class="table  table-striped table-borderless mb-0">
                        <thead>
                        <tr>
                            <th class="text-center font-weight-medium">#</th>
                            <th class="text-center font-weight-medium">Código do pedido</th>
                            <th class="text-center font-weight-medium">Data da compra</th>
                            <th class="text-center font-weight-medium">Valor total</th>
                            <th class="text-center font-weight-medium">Pagamento</th>
                            <th class="text-center font-weight-medium">Status pedido</th>
                            <th class="text-center font-weight-medium">Informações</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($pedidos as $pedido)
                            <tr>
                                <td data-label="#" class="text-center">{{ $loop->iteration }}</td>
                                <td data-label="Código do pedido" class="text-center">
                                    <strong class="font-weight-medium">#{{ $pedido->codigo }}</strong>
                                </td>
                                <td data-label="Data da compra" class="text-center">{{ $pedido->created_at->format('d/m/Y') }}</td>
                                <td data-label="Valor total" class="text-center">R$ {{ formataValor($pedido->valor_total + $pedido->juros) }}</td>
                                <td data-label="Pagamento" class="text-center text-{{ $pedido->cor_status_pagamento }} text-uppercase">
                                    <strong class="font-weight-medium">{{ $pedido->pagamento_status }}</strong>
                                </td>
                                <td data-label="Status pedido" class="text-center text-{{ $pedido->cor_status_pedido }} text-uppercase">
                                    <strong class="font-weight-medium">{{ $pedido->status_pedido }}</strong>
                                </td>
                                <td class="text-center pl-0">
                                    <a href="{{ route('ecommerce.cliente.pedidos.view', $pedido->codigo) }}" class="btn btn-blue btn-rounded text-white border-0 pb-2 text-uppercase" title="Detalhes do pedido">
                                        Ver pedido <i class="iconify ml-1 align-text-bottom" data-icon="jam:chevron-right"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="py-3 text-center" colspan="7">
                                    Você ainda não realizou nenhum pedido
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                        <tfoot>
                        <tr>
                            <th colspan="4" class="text-left">
                                <span class="font-weight-medium">Total de {{ $pedidos->count() }} pedido(s) realizado(s)</span>
                            </th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </main>

    @include('template.footer')

@endsection
