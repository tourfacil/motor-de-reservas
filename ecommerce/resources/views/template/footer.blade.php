@php($destinos = ($destinos->chunk(3) ?? []))

<a href="http://wa.me/{{config('site.numero_whatsapp')}}" style="position:fixed;width:60px;height:60px;bottom:65px;right:40px;background-color:#25d366;color:#FFF;border-radius:50px;text-align:center;font-size:30px;box-shadow: 1px 1px 2px #888;z-index:1000;" target="_blank">
    <i style="margin-top:16px" class="fa fa-whatsapp"></i>
</a>

<footer>
    <div class="container pt-3">
        <div class="row">
            <div class="col-12 icons-social-media py-4 mb-4">
                <a href="{{ config('site.facebook') }}" target="_blank" rel="nofollow" title="Curta nossa página no Facebook"><span class="iconify" data-icon="jam:facebook"></span></a>
                <a href="{{ config('site.instagram') }}" target="_blank" rel="nofollow" title="Siga nossa página no Instagram"><span class="iconify" data-icon="jam:instagram"></span></a>
                <a href="{{ config('site.twitter') }}" target="_blank" rel="nofollow" title="Siga nossa página no Twitter"><span class="iconify" data-icon="jam:twitter"></span></a>
                <a href="{{ config('site.youtube') }}" title="Inscreva-se no nosso canal do Youtube" target="_blank" rel="nofollow"><span class="iconify" data-icon="jam:youtube"></span></a>
            </div>
            <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                <h6 class="mb-3">Nossos destinos</h6>
                <div class="row">
                    <div class="col-12 col-md-6">
                        @if(isset($destinos[0]))
                            <ul class="list-unstyled mb-0 mb-md-3">
                                @foreach($destinos[0] as $destino)
                                    <li><a href="{{ route('ecommerce.destino.index', $destino->slug) }}" title="{{ $destino->nome }}">{{ $destino->nome }}</a></li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                    <div class="col-12 col-md-6">
                        @if(isset($destinos[1]))
                            <ul class="list-unstyled">
                                @foreach($destinos[1] as $destino)
                                    <li><a href="{{ route('ecommerce.destino.index', $destino->slug) }}" title="{{ $destino->nome }}">{{ $destino->nome }}</a></li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                <h6 class="mb-3">Suporte</h6>
                <ul class="list-unstyled">
                    <li><a href="{{ route('ecommerce.ajuda.cancelamento') }}" title="Termos e Condições">Políticas de Cancelamento</a></li>
                    <li><a href="{{ route('ecommerce.ajuda.privacidade') }}" title="Políticas de Privacidade">Políticas de Privacidade</a></li>
                    <li><a href="{{ route('ecommerce.ajuda.termos') }}" title="Termos e Condições">Termos e Condições</a></li>
                </ul>
            </div>
            <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                <h6 class="mb-3">Fornecedores</h6>
                <ul class="list-unstyled">
                    <li><a href="https://docs.google.com/forms/d/e/1FAIpQLSduPnYcf6aOln5LNfMBYTL_vAxb2RCnK8kFi4E_2HiBYwSDqA/viewform" title="Fornecedores" rel="noopener" target="_blank">Seja um Fornecedor</a></li>
                    <li><a href="https://docs.google.com/forms/d/e/1FAIpQLSe8VGb7AszsgSkN0U8SGDbgD73Kb_M7BD2Yt5r7Bsp7o0To-A/viewform" rel="noopener" title="Afiliados" target="_blank">Seja um Afiliado TourFácil</a></li>
                </ul>
            </div>
            <div class="col-12 col-sm-6 col-md-6 col-lg-2 d-none d-sm-block">
                <img src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" class="lazyload mt-3 img-fluid logos-pagamento" data-src="{{ asset('images/bandeiras_pagamento.svg') }}" alt="Formas de pagamento">
            </div>
        </div>
        <hr class="mt-4">
        <div class="pt-3 pb-5 info-empresa">
            <div class="row align-items-center justify-content-center">
                <div class="col-12 col-md-5 col-lg-4">
                    <div class="text-center">
                        <p>Expanda Viagens E Turismo LTDA</p>
                        <p>CNPJ: 34.242.332/0001-02</p>
                        <p>Rua João Alfredo Schneider, 635</p>
                        <p class="mb-1">Planalto, Gramado - RS - {{ config('site.numero_whatsapp_formatado')}}</p>
                        <strong>tourfacil.com.br</strong>
                    </div>
                </div>
                <div class="col-12 col-md-3 text-center mb-3">
                    <img src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" class="lazyload img-fluid mt-4 mt-md-0" data-src="{{ asset('images/logo_rodape.svg') }}" alt="Logotipo TourFácil">
                </div>
            </div>
        </div>
    </div>
</footer>

