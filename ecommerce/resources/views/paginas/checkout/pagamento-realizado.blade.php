@extends('template.master')

@section('title', "Seu pedido foi aprovado!")

@section('body')

    @php
        // Verifica se o pedido esta ou não finalizado
        $pedido = session()->get('pedido_realizado');
        $is_finalizado = \TourFacil\Core\Services\FinalizacaoService::isPedidoFinalizado($pedido);

    @endphp


    {{--  Footer sticky  --}}
    <div class="d-md-flex flex-column h-100">

        @include('template.navbar-clear', ['back_button' => false])

        <main class="flex-shrink-0 bg-light" data-controller="ResultadoPagamentoCtrl">
            <div class="container pb-5">

                {{-- Passos para checkout --}}
                @include('paginas.checkout.step', ['current_step' => 3])

                <div class="d-flex flex-column align-items-center justify-content-center bg-light section-payment">
                    <h3 class="h5 mt-4 line-initial font-weight-medium text-center">
                        Oi {{ somenteNome(auth()->user()->nome) }}, <br>
                        @IF($is_finalizado == true)
                            Trago boas notícias:
                        @ENDIF
                    </h3>
                    <h1 class="font-weight-bold my-1 text-center">
                        @IF($is_finalizado == true)
                            Seu pedido foi aprovado <span class="iconify align-middle text-success" data-icon="jam:check"></span>
                        @ELSE
                            Seu pedido está quase pronto <span class="iconify align-middle text-success" data-icon="jam:check"></span>
                        @ENDIF
                    </h1>
                    @IF($is_finalizado == true)
                        <h3 class="h4 line-initial font-weight-medium text-md-center">Obaaaa! :)</h3>
                    @ELSE
                        <h3 class="h4 line-initial font-weight-medium text-md-center">Para a confirmação da sua reserva preencha os dados dos viajantes clicando no botão abaixo</h3>
                    @ENDIF
                    <div class="emoji text-center my-3">
                        <span class="iconify" data-icon="fxemoji:partypopper"></span>
                        <span class="iconify" data-icon="fxemoji:partypopper"></span>
                        <span class="iconify" data-icon="fxemoji:partypopper"></span>
                    </div>
                    <p class="text-center line-initial mt-2">
                        O código do seu pedido é: <br>
                        <strong class="text-blue h4 font-weight-bold">#{{ $pedido['codigo'] }}</strong>
                    </p>
                    <p class="text-center line-initial mt-2">
                        Obrigado por comprar conosco! <br>
                        @IF($is_finalizado == true)
                            Na próxima tela você poderá imprimir seu(s) voucher(s)
                        @ELSE
                            Na próxima tela você poderá finalizar sua(s) reserva(s) e imprimir seu(s) voucher(s)
                        @ENDIF
                    </p>
                    @IF($is_finalizado == true)
                        <a href="{{ route('ecommerce.cliente.pedidos.view', $pedido['codigo']) }}#imprimir" title="Página Incial"
                            class="btn btn-blue border-0 text-white btn-rounded px-4 pb-2 text-uppercase mt-1">Ver meu pedido</a>
                    @ELSE
                        <a href="{{ route('ecommerce.cliente.pedidos.view', $pedido['codigo']) }}#imprimir" title="Página Incial"
                           class="btn btn-blue border-0 text-white btn-rounded px-4 pb-2 text-uppercase mt-1">Finalizar meu pedido</a>
                    @ENDIF
                    @IF($is_finalizado == true)
                    <p class="text-muted text-center mt-3 line-initial mb-0">
                        As informações desta reserva foram enviadas para <br class="d-none d-sm-block">
                        o e-mail <strong class="font-weight-medium">{{ auth()->user()->email }}</strong>
                    </p>
                    @ELSE
                    <p class="text-muted text-center mt-3 line-initial mb-0">
                        As informações desta reserva serão enviadas para <br class="d-none d-sm-block">
                        o e-mail <strong class="font-weight-medium">{{ auth()->user()->email }}</strong> após a finalização
                    </p>
                    @ENDIF
                </div>
            </div>
        </main>

        @include('template.footer-clear')
    </div>

@endsection

@section('scripts')
    <script>
        window.send_purchase = '{{ $send_purchase }}';
        window.pedido = @json($pedido)
    </script>
@endsection
