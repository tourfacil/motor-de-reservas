@extends('template.master')

@section('title', 'Reserve atividades e atrações turísticas em qualquer lugar do Brasil')

@section('body')


    <nav class="navbar navbar-expand-lg navbar-light index-page py-2 py-lg-0" data-controller="NavbarCtrl">
        <div class="container flex-row">
            {{-- nav toggler --}}
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#menu-nav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand" href="{{ route('ecommerce.index') }}" title="Página inicial - Tour Fácil">
                <img src="{{ asset('images/logo_tourfacil.svg') }}" class="d-inline-block align-top" alt="Logotipo Tour Fácil">
            </a>
            {{-- nav mobile --}}
            <ul class="navbar-nav flex-row ml-md-auto d-lg-none">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('ecommerce.carrinho.index') }}" title="Carrinho">
                        <i class="iconify" data-icon="jam:shopping-cart"></i><span class="cart-count">{{ carrinho()->count() }}</span>
                    </a>
                </li>
            </ul>
            {{-- nav desktop and collapse mobile --}}
            <div class="collapse navbar-collapse align-self-start mb-0 mb-lg-4" id="menu-nav">
                <ul class="navbar-nav ml-md-auto dark-menu position-relative mt-3 mt-lg-0">
                    <li class="nav-item mr-md-2">
                        <a class="nav-link" href="http://wa.me/{{config('site.numero_whatsapp')}}" target="_blank" title="Ajuda"><i class="iconify" data-icon="jam:help"></i> Ajuda</a>
                    </li>
                    <li class="nav-item mr-md-2 d-none d-lg-flex">
                        <a class="nav-link" href="{{ route('ecommerce.carrinho.index') }}" title="Carrinho">
                            <i class="iconify" data-icon="jam:shopping-cart"></i> Carrinho <span class="cart-count">{{ carrinho()->count() }}</span>
                        </a>
                    </li>
                    @if(auth('clientes')->check())
                        <li class="d-lg-none"><div class="nav-item nav-link disabled">Sua conta</div></li>
                        <li class="nav-item mr-md-2 d-lg-none">
                            <a class="nav-link" href="{{ route('ecommerce.cliente.pedidos.index') }}" title="Meus pedidos"><i class="iconify" data-icon="jam:task-list"></i> Pedidos realizados</a>
                            <a class="nav-link" href="{{ route('ecommerce.cliente.meus-dados.index') }}" title="Meus dados"><i class="iconify" data-icon="jam:user-square"></i> Meus dados</a>
                        </li>
                        <li class="nav-item mr-md-2 d-lg-none">
                            <a class="nav-link" href="{{ route('ecommerce.cliente.logout') }}" data-action='logout' title="Sair da conta"><i class="iconify" data-icon="jam:power"></i> Sair da conta</a>
                        </li>
                        <li class="nav-item d-none d-lg-flex">
                            <div class="dropdown">
                                <button class="nav-link btn-not-focus" data-boundary="window" data-toggle="dropdown">
                                    <i class="iconify" data-icon="ic:outline-account-circle"></i> Olá, {{ somenteNome(auth('clientes')->user()->nome) }}
                                </button>
                                <div class="dropdown-menu shadow border-0">
                                    <a href="{{ route('ecommerce.cliente.pedidos.index') }}" class="dropdown-item text-left" title="Meus pedidos">Pedidos realizados</a>
                                    <a href="{{ route('ecommerce.cliente.meus-dados.index') }}" class="dropdown-item text-left" title="Meus dados">Meus dados</a>
                                    <div class="dropdown-divider"></div>
                                    <a href="{{ route('ecommerce.cliente.logout') }}" data-action='logout' class="dropdown-item text-left" title="Sair da conta">Sair da conta</a>
                                </div>
                            </div>
                        </li>
                        <li class="nav-item ml-md-3 d-none d-lg-block no-hover">
                            <a href="{{ route('ecommerce.cliente.pedidos.index') }}" title="Meus pedidos" class="btn btn-not-focus btn-outline-light text-uppercase btn-rounded btn-p-vertical">
                                Meus pedidos <i class="iconify align-text-bottom" data-icon="jam:chevron-right"></i>
                            </a>
                        </li>
                    @else
                        <li class="nav-item mr-md-1">
                            <button data-modal="#modal-login" title="Minha conta" class="nav-link btn-not-focus"><i class="iconify" data-icon="ic:outline-account-circle"></i> Minha conta</button>
                        </li>
                        <li class="nav-item ml-md-3 d-none d-lg-block no-hover">
                            <button data-modal="#modal-cadastro" title="Criar nova conta" class="btn btn-not-focus btn-outline-light text-uppercase btn-rounded btn-p-vertical">
                                Criar conta <i class="iconify align-text-bottom" data-icon="jam:chevron-right"></i>
                            </button>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <div data-page="Home" data-controller="HomeCtrl" data-destino="{{ route('ecommerce.destino.view') }}">
        <div class="section-home-form position-relative">
            <div class="owl-carousel owl-destinos-bg owl-theme">
                @foreach($banners_home as $banner_home)
                    <div class="item owl-lazy" data-src="{{ asset($banner_home['image']) }}"></div>
                @endforeach
            </div>
            <div class="backdrop"></div>
            <section class="d-flex align-items-center" data-controller="SearchBox">
                <div class="container">
                    <div class="row pt-5">
                        <div class="col-12">
                            <h1 class="text-white h2 text-center text-sm-left">Expanda agora é Tour Fácil</h1>
                            <h6 class="text-white h5 text-center text-sm-left">Reserve as principais atividades pelo Tour Fácil</h6>
                        </div>
                        <div class="col-sm-11 col-lg-6">
                            <form action="{{ route('ecommerce.servico.search') }}" class="mt-4">
                                <div class="input-group input-group-lg">
                                    <input type="text" class="form-control" name="q" required aria-label="Pesquisar" data-required="true" data-min="3"
                                           id="search" title="Pesquisar" placeholder="em Gramado, Bonito..." autocomplete="off"
                                           data-action="search" data-route="{{ route('ecommerce.servico.search-ajax') }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-purple text-uppercase">Eu quero &nbsp; ;)</button>
                                    </div>
                                </div>
                            </form>
                            <div id="autocomplete-list" class="py-2"></div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        {{-- CAMPANHA DE DIA DOS NAMORADOS --}}
        @IF(config('campanha.ativo') == true)
           

            @php

                $ids_servico = config('campanha.servicos'); 
                $servicos_campanha = TourFacil\Core\Models\Servico::whereIn('id', $ids_servico)
                                                                ->with([
                                                                    'fotoPrincipal',
                                                                    'categoria',
                                                                    'destino',
                                                                    'tags'
                                                                    ])
                                                                ->get()->shuffle();


            @endphp


            <style>
                .owl-nav-campanha .owl-nav button.owl-prev {
                    background-color: {{ config('campanha.cor') }};
                } 

                .owl-nav-campanha .owl-nav button.owl-next {
                    background-color: {{ config('campanha.cor') }};
                } 
                
            </style>


            <section class="section-servicos-populares mt-4 mb-4" id="namorados" class="namorados">
                <div class="container pt-3">
                    <h2 class="text-center font-weight-medium pb-2" style="color: {{ config('campanha.cor') }}">{{ config('campanha.titulo') }}</h2>
                    <div class="owl-carousel owl-nav-campanha owl-nav-container owl-servicos mt-4 owl-padding">
                        @foreach($servicos_campanha as $index => $servico_destaque)
                            @php($servico_destaque->tags->splice(\TourFacil\Core\Enum\ServicoEnum::LIMITE_TAGS))
                            @php($lazy_class = ($index <= 3) ? "lazyload" : "owl-lazy")
                            <div class="card-servico item">
                                <a href="{{ route('ecommerce.servico.view', [$servico_destaque->destino->slug, $servico_destaque->categoria->slug, $servico_destaque->slug]) }}" title="{{ $servico_destaque->nome }}">
                                    <div class="card-servico-image">
                                        <img src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" class="{{ $lazy_class }}" data-src="{{ $servico_destaque->fotoPrincipal->fotoLarge }}" alt="{{ $servico_destaque->nome }}">
                                    </div>
                                    <div class="card-body d-flex flex-column">
                                        <h3 class="ellipsis-2-lines" sty style="color: {{ config('campanha.cor') }}">{{ $servico_destaque->nome }}</h3>
                                        <div class="card-body-items mt-auto">
                                            @foreach($servico_destaque->tags as $tag_servico)
                                                <div class="card-body-item">
                                                    <i class="card-body-icon jam jam-{{ $tag_servico->icone }}"></i>
                                                    <span>{{ $tag_servico->descricao }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="mt-auto d-flex justify-content-end align-items-center card-body-price">
                                            <span>A partir de</span>
                                            <strong style="color: {{ config('campanha.cor') }}">R$ {{ formataValor($servico_destaque->valor_venda) }}</strong>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @ENDIF    

        {{-- FIM DA CAMPANHA DE DIA DOS NAMORADOS --}}

        <section class="section-servicos-populares mt-4 mb-4">
            <div class="container pt-3">
                <h2 class="text-center font-weight-medium pb-2">As atividades mais populares do momento</h2>
                <div class="owl-carousel owl-nav-blue owl-nav-container owl-servicos mt-4 owl-padding">
                    @foreach($servicos_destaques as $index => $servico_destaque)
                        @php($servico_destaque->tags->splice(\TourFacil\Core\Enum\ServicoEnum::LIMITE_TAGS))
                        @php($lazy_class = ($index <= 3) ? "lazyload" : "owl-lazy")
                        <div class="card-servico item">
                            <a href="{{ route('ecommerce.servico.view', [$servico_destaque->destino->slug, $servico_destaque->categoria->slug, $servico_destaque->slug]) }}" title="{{ $servico_destaque->nome }}">
                                <div class="card-servico-image">
                                    <img src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" class="{{ $lazy_class }}" data-src="{{ $servico_destaque->fotoPrincipal->fotoLarge }}" alt="{{ $servico_destaque->nome }}">
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <h3 class="ellipsis-2-lines">{{ $servico_destaque->nome }}</h3>
                                    <div class="card-body-items mt-auto">
                                        @foreach($servico_destaque->tags as $tag_servico)
                                            <div class="card-body-item">
                                                <i class="card-body-icon jam jam-{{ $tag_servico->icone }}"></i>
                                                <span>{{ $tag_servico->descricao }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="mt-auto d-flex justify-content-end align-items-center card-body-price">
                                        <span>A partir de</span>
                                        <strong>R$ {{ formataValor($servico_destaque->valor_venda) }}</strong>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <div class="container home-sections pt-5 pb-4">
            <div class="row">
                <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                    <section class="text-center mb-5 mb-sm-0">
                        <img src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" class="lazyload" data-src="{{ asset('images/icons/qualidade-home.svg') }}" alt="Qualidade Garantida">
                        <h3>Qualidade Garantida</h3>
                        <p>A Tour Fácil possui parceria com os<br> melhores parques e atrações!</p>
                    </section>
                </div>
                <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                    <section class="text-center mb-5 mb-sm-0">
                        <img src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" class="lazyload" data-src="{{ asset('images/icons/parcelamento-home.svg') }}" alt="Parcelamento Exclusivo">
                        <h3>Parcelamento Exclusivo</h3>
                        <p>Na bilheteria não há opção de<br> parcelamento. Na Tour Fácil tem!</p>
                    </section>
                </div>
                <div class="col-lg-4 d-sm-none d-lg-block">
                    <section class="text-center">
                        <img src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" class="lazyload" data-src="{{ asset('images/icons/suporte-home.svg') }}" alt="Suporte">
                        <h3>Suporte</h3>
                        <p>Contamos com uma equipe<br> especializada nos destinos!</p>
                    </section>
                </div>
            </div>
        </div>

        <div class="bg-blue-light">
            <section class="container prev-destinos pb-3 pb-sm-4">
                <h2 class="h1 mt-0 mb-4">Destinos em destaques</h2>
                <div class="nav-scroll-shadows" id="destino-content">
                    <div class="auto-scroll-buttons horizontal-scroll py-1">
                        @foreach($destinos as $destino)
                            @php($active = ($destino->slug == $destino_destaque['slug']) ? "active" : "")
                            <button data-action="trocarDestino" title="{{ $destino->nome }}" data-destino="{{ $destino->slug }}"
                                    class="btn btn-purple-outline btn-rounded text-uppercase {{ $active }}">{{ $destino->nome }}</button>
                        @endforeach
                    </div>
                </div>
            </section>
            <div id="destino-replace" class="mt-3">
                @include('partials.view-destino-home')
            </div>
        </div>

        <div class="bg-green-whats">
            <section class="container section-whats">
                <div class="row align-items-center justify-content-center">
                    <div class="col-12 col-md-12 col-lg-8 d-md-flex align-items-center justify-content-center">
                        <div class="pr-md-5 pb-4 pb-md-0 text-sm-center">
                            <h3 class="h1 font-weight-bold">troque uma ideia no whats!</h3>
                            <p class="mt-3 mb-3 font-size-1-1">Tem alguma dúvida sobre as opções de ingressos ou<br class="d-none d-md-block"> funcionamento? Chame nossa equipe pelo WhatsApp!</p>
                            <a href="http://wa.me/{{config('site.numero_whatsapp')}}" target="_blank" class="btn btn-outline-light font-weight-medium px-4 pb-2 text-uppercase btn-rounded btn-p-vertical" title="Converse conosco no WhatsApp">
                                Enviar mensagem <i class="iconify ml-2 right" data-icon="jam:whatsapp"></i>
                            </a>
                        </div>
                        <div class="text-center text-md-left"><i class="iconify" data-icon="jam:whatsapp"></i></div>
                    </div>
                </div>
            </section>
        </div>

        <section class="section-destinos pt-4">
            <div class="container mt-2">
                <h2 class="h1 font-weight-bold text-center text-blue pb-4">Nossos destinos</h2>
                <div class="owl-carousel owl-nav-blue owl-nav-container owl-padding owl-destinos">
                    @foreach($destinos as $destino)
                        <div class="item">
                            <a href="{{ route('ecommerce.destino.index', $destino->slug) }}" title="{{ $destino->nome }}">
                                <div class="card card-destino">
                                    <img src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" class="card-img lazyload" data-src="{{ $destino->foto_destino }}" alt="{{ $destino->nome }}">
                                    <div class="card-img-overlay d-flex align-items-center justify-content-center">
                                        <h3 class="card-title text-white">{{ $destino->nome }}</h3>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                    @foreach($breve_destinos as $breve_destino)
                        <div class="item">
                            <div class="card card-destino">
                                <img src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" class="card-img lazyload" data-src="{{ asset($breve_destino['image']) }}" alt="{{ $breve_destino['nome'] }}">
                                <div class="card-img-overlay d-flex align-items-center justify-content-center">
                                    <h3 class="card-title text-white">Em breve!<br> {{ $breve_destino['nome'] }}</h3>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

{{--        Comendado MANTENHA A FLEXIBILIDADE. VERSÃO 1.3.5--}}

        <section class="container py-5 text-left text-sm-center d-none d-sm-block">
{{--            <h3 class="text-yellow h1 font-weight-bold mt-2 font-mobile">Mantenha a flexibilidade</h3>--}}
{{--            <p class="text-muted font-size-1-1 mt-3 mb-0">Use a opção "Reserve agora, pague depois" para garantir sem<br class="d-none d-md-block"> compromisso as atividades que não quer perder.</p>--}}
        </section>






        {{-- Comentado dica de viajantes na versão 1.0.4 --}}

        {{-- <div class="section-blog py-5">
            <div class="container">
                <div class="row justify-content-between">
                    <div class="col-12 col-md-7 col-lg-6">
                        <section class="px-lg-4">
                            <h4 class="text-yellow h1 font-weight-bold font-mobile">dica de viajantes</h4>
                            <h5 class="h2 mt-3 font-weight-bold font-mobile">{{ $post['titulo'] }}</h5>
                            <p class="text-justify text-muted">{{ $post['descricao'] }}</p>
                            <a href="{{ $post['url'] }}" target="_blank" title="Continue lendo essa máteria"
                               class="btn btn-rounded btn-outline-dark text-uppercase btn-sm float-right mb-4 mb-md-0">Continue lendo aqui</a>
                        </section>
                    </div>
                    <div class="col-12 col-md-5 col-lg-6">
                        <img src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" class="overlay-blog d-none d-lg-block img-fluid lazyload" data-src="{{ asset('images/overlay_blog.svg') }}" alt="Overlay post blog">
                        <div class="section-blog-image lazyload" data-bg="{{ $post['image'] }}"></div>
                    </div>
                </div>
            </div>
        </div>

{{--        <div class="container mt-2 mb-4 section-home-partner">--}}
{{--            <div class="row justify-content-center">--}}
{{--                <div class="col-12 col-lg-11 text-center">--}}
{{--                    <h5 class="h2 mt-2 mb-3 font-weight-medium">Somos parceiros</h5>--}}
{{--                    <div class="row align-items-center justify-content-center pt-4">--}}
{{--                        @foreach($parceiros as $parceiro)--}}
{{--                            <div class="col-4 col-md mb-sm-0">--}}
{{--                                <img src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" class="img-fluid lazyload mb-4 mb-md-0" data-src="{{ asset($parceiro['logo']) }}" alt="{{ $parceiro['nome'] }}">--}}
{{--                            </div>--}}
{{--                        @endforeach--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}

{{--        <div class="container section-home-midia pt-3 pt-sm-4">--}}
{{--            <div class="row justify-content-center">--}}
{{--                <div class="col-12 col-lg-11 text-center">--}}
{{--                    <h5 class="h2 mt-2 mb-3 font-weight-medium">Na mídia</h5>--}}
{{--                    <div class="row align-items-center justify-content-center pt-4">--}}
{{--                        @foreach($midias as $midia)--}}
{{--                            <div class="col-4 col-md-3 mb-sm-0">--}}
{{--                                <a href="{{ $midia['url'] }}" title="{{ $midia['nome'] }}">--}}
{{--                                    <img src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" class="img-fluid lazyload" data-src="{{ asset($midia['logo']) }}" alt="{{ $midia['nome'] }}">--}}
{{--                                </a>--}}
{{--                            </div>--}}
{{--                        @endforeach--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}

        <section class="container py-5" style="display: none;">
            <h3 class="h1 font-weight-bold mt-3 text-center font-mobile">Siga nossos passos <i class="iconify align-middle d-none d-sm-inline-block" data-icon="jam:instagram"></i></h3>
            <div id="instagram-feed" class="pt-4 pb-3">
                <div class="loading-insta-feed d-flex align-items-center justify-content-center">
                    <div class="spinner-border text-dark" role="status">
                        <span class="sr-only">Aguarde...</span>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <a href="https://www.instagram.com/tourfaciloficial/" rel="nofollow" title="Veja nosso instagram" target="_blank"
                   class="btn btn-outline-dark btn-rounded mt-3 text-uppercase">Veja nosso instagram</a>
            </div>
        </section>
    </div>

    {{-- Newsletter --}}
    @include('partials.newsletter')

    {{-- Modais de login e cadastro --}}
    @if(! auth('clientes')->check())

        {{-- Modal de cadastro --}}
        @include('paginas.modais.modal-cadastro')

        {{-- Modal de cadastro e redefinicao de senha --}}
        @include('paginas.modais.modal-login')
    @endif

    {{-- Footer --}}
    @include('template.footer')

@endsection

@section('seo')
    <meta name="description" content="Reserve atividades e atrações turísticas em qualquer lugar do Brasil" />
    <meta property="og:description" content="Reserve atividades e atrações turísticas em qualquer lugar do Brasil" />
    <meta name="twitter:description" content="Reserve atividades e atrações turísticas em qualquer lugar do Brasil" />
@endsection
