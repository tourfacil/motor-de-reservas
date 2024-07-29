@extends('template.master')

@section('title', "Pagamento expirado")

@section('body')


    {{--  Footer sticky  --}}
    <div class="d-md-flex flex-column h-100">

        @include('template.navbar-clear')

        <main class="flex-shrink-0 bg-light">
            <div class="container pb-5">

                {{-- Passos para checkout --}}
                @include('paginas.checkout.step', ['current_step' => 3, 'has_erro_pagamento' => true, 'pix_expirado' => true])

                <div class="d-flex flex-column align-items-center justify-content-center bg-light section-payment">
                    <i class="iconify text-warning mt-4" data-icon="jam:alert"></i>
                    <h1 class="font-weight-bold mt-2 text-center">Pagamento expirado</h1>
                    <h3 class="h4 text-center">Código da transação #{{ $numero_pedido }}</h3>
                    <p class="text-muted text-center line-initial mt-2">
                        Não identificamos o pagamento durante o período de compra.<br><small> O período máximo de compra por PIX é de {{env('PIX_TIMEOUT', 5)}} minuto(s).</small><br>
                    </p>
                    <a href="{{ route('ecommerce.index') }}" title="Página Incial" class="btn btn-blue border-0 text-white btn-rounded px-4 pb-2 text-uppercase mt-1">
                        Tentar novamente <span class="iconify ml-1" data-icon="jam:refresh"></span>
                    </a>
                    <p class="text-muted text-center mt-3 line-initial mb-0">Em caso de dúvida entre em contato por whatsapp através do número <br class="d-none d-sm-block"> <a style="font-weight: bold;" href="http://wa.me/{{config('site.numero_whatsapp')}}?{{config('site.whatsapp_text_message_parameter')}}" >{{ config('site.numero_whatsapp_formatado') }}</a></p>
                </div>
            </div>
        </main>

        @include('template.footer-clear')
    </div>

@endsection
