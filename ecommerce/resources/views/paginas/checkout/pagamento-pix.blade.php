@extends('template.master')

@section('title', "Pagamento do PIX")

@section('body')

    @php($year = date('Y'))
    @php($index_input = 1)
    @php($steps = 1)
    @php($position = 1)
    @php($json_servicos = [])

@include('template.navbar-clear')

<div data-page="pagamento" class="bg-light pb-5" data-controller="PagamentoPixCtrl">
    <main class="container pb-3">
        <div class="row justify-content-between">
            <div class="col-12">
                {{-- Passos para checkout --}}
                @include('paginas.checkout.step', ['current_step' => 2])
            </div>
            <div class="col-12">
                {{-- Mensagens de erro --}}
                @if($errors->any())
                <div class="alert alert-danger mb-2 mt-3" role="alert">
                    @foreach($errors->all() as $error)
                    <p class="m-0">{{ $error }}</p>
                    @endforeach
                </div>
                @endif
                {{-- Mensagem de sucesso --}}
                @if(session()->has('status'))
                <div class="alert alert-success mb-2 mt-3" role="alert">
                    @foreach($errors->all() as $error)
                    {{ session()->get('status') }}
                    @endforeach
                </div>
                @endif
            </div>
            <div class="col-12 col-lg-8 pr-lg-4">
                <div class="text" style="text-align: center; margin-top: 30px; margin-bottom: 15px;">
                    <small>Realize o pagamento utilizando a instituição bancaria de sua preferência e após aguarde alguns instantes.</small>
                </div>
                <div id="pix" style="display: block; text-align: center">
                    <div style="width: auto;">
                        <div class="test">
                            <img id="qrcode_pix" src="{{ $pix->qr_code_url }}">
                        </div>
                    </div>
                    <div style="width: auto">
                        {{-- <hr style="width: 100%; float: center"> --}}
                    </div>
                    <div class="pagamento-realizado" style="text-align: center; margin-top:15px; display:flex; justify-content: center;">
                        <div class="spinner-border text-info" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <div class="div" style="margin-left: 10px; margin-top: 5px;">
                            Aguardando pagamento
                        </div>
                    </div>
                    <fieldset style="width: 100%; border: 2px solid #5dc3e3; border-radius: 5px; padding: 10px">
                        <legend style="width: initial; font-size: 16px;">&nbsp;Copiar código Pix&nbsp;</legend>
                        <div>
                            <p id="codigo_pix_exibido" style="width: 100%; word-break: break-all;">{{ $pix->qr_code }}</p>
                            <p id="codigo_pix" style="display: none">{{ $pix->qr_code }}</p>
                        </div>
                        <div class="row d-flex justify-content-center">
                            <a id="copiaCodigoPix" class="btn btn-blue-outline btn-block btn-rounded text-uppercase col-6 col-md-6" style="opacity: 1;">Copiar Pix</a>
                        </div>
                    </fieldset>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="card-shop-left pt-2 position-sticky">
                    <hr class="blue mb-4">
                    <div class="row d-flex justify-content-between">
                        <div class="col-auto">
                            <h2 class="font-weight-medium h5">Total ({{ $pedido->reservas->count() }} item)</h2>
                        </div>
                        <div class="col-auto text-right">
                            <input type="hidden" id="servico_valor" value="{{ formataValor($pedido->valor_total) }}">
                            <strong class="d-block h4 m-0 font-weight-medium text-muted">
                                R$ {{ formataValor($pedido->valor_total) }}
                            </strong>
                            <small class="text-muted">Nenhum valor adicional</small>
                        </div>
                    </div>
                    <div class="list-cart-items mt-3">
                        @foreach($pedido->reservas as $index => $reserva)
                        <div class="row d-flex align-items-center mb-4">
                            <div class="col-3 pr-lg-1">
                                <img src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" class="img-fluid mt-1 rounded lazyload" data-src="{{ env("CDN_URL") . $reserva->servico->fotoPrincipal->foto['SMALL'] }}" alt="{{ $reserva->servico->nome }}">
                            </div>
                            <div class="col-9">
                                <input type="hidden" id="servico_nome" value="{{ $reserva->servico->nome }}">
                                <h4 class="font-weight-normal h5 text-truncate mb-1">
                                    {{ $reserva->servico->nome }}
                                </h4>
                                <div class="row d-flex justify-content-between">
                                    <div class="col-auto">
                                        <p class="mb-0 text-muted line-initial">
                                            {{ dataExtenso($reserva->agendaDataServico->data, true) }}
                                        </p>
                                        <p class="mb-0 text-muted line-initial">Para
                                            {{ $reserva->quantidade }}
                                            pessoa(s)
                                        </p>
                                    </div>
                                    <div class="col-auto align-self-end">
                                        <strong class="text-muted">
                                            R$ {{ formataValor($reserva->valor_total) }}
                                        </strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<span id="link-pesquisa" data="{{ Route('ecommerce.carrinho.pagamento.pix.json', $pedido->codigo) }}"></span>

{{-- Modal login --}}
@include('paginas.modais.modal-login')

{{-- Modal aviso de e-mail já utilizado --}}
@include('paginas.modais.aviso-login-pagamento')

{{-- footer --}}
@include('template.footer-clear')

@endsection

@section('scripts')
<script>
    window.carrinho = @json($json_servicos);

    /* Carrega o Sweet Alert assim que a página estiver carregada */
        window.onload = () => {
        Helpers.loadSweetAlert();
    };
</script>
@endsection
