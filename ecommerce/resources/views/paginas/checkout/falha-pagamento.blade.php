@extends('template.master')

@section('title', "Pagamento não autorizado")

@section('body')


    {{--  Footer sticky  --}}
    <div class="d-md-flex flex-column h-100">

        @include('template.navbar-clear')

        <main class="flex-shrink-0 bg-light">
            <div class="container pb-5">

                {{-- Passos para checkout --}}
                @include('paginas.checkout.step', ['current_step' => 3, 'has_erro_pagamento' => true])

                <div class="d-flex flex-column align-items-center justify-content-center bg-light section-payment">
                    <i class="iconify text-warning mt-4" data-icon="jam:alert"></i>
                    <h1 class="font-weight-bold mt-2 text-center">Pagamento não autorizado</h1>
                    <h3 class="h4 text-center">Código da transação #{{ $numero_pedido }}</h3>
                    <p class="text-muted text-center line-initial mt-2">
                        Não foi possível processar o seu <strong>pagamento.</strong><br>
                        Por favor verifique os dados informados e tente novamente.
                    </p>
                    <a href="{{ route('ecommerce.carrinho.pagamento') }}" title="Página Incial" class="btn btn-blue border-0 text-white btn-rounded px-4 pb-2 text-uppercase mt-1">
                        Tentar novamente <span class="iconify ml-1" data-icon="jam:refresh"></span>
                    </a>
                    <p class="text-muted text-center mt-3 line-initial mb-0">Caso essa mensagem persista por favor entre em contato com <br class="d-none d-sm-block"> o banco emissor do seu cartão.</p>
                </div>
            </div>
        </main>

        @include('template.footer-clear')
    </div>

@endsection
