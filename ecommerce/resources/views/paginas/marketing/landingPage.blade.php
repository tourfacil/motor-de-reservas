@extends('template.master')

@section('title', 'Descubra a TourFácil')

@section('body')

    <nav class="navbar navbar-expand-lg navbar-light index-page py-2 py-lg-0" data-controller="NavbarCtrl">
        <div class="container flex-row">
            {{-- nav toggler --}}
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#menu-nav"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand" href="{{ route('ecommerce.index') }}" title="Página inicial - Tour Fácil">
                <img src="{{ asset('images/logo_tourfacil.svg') }}" class="d-inline-block align-top"
                    alt="Logotipo Tour Fácil">
            </a>
            {{-- nav mobile --}}
            <ul class="navbar-nav flex-row ml-md-auto d-lg-none">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('ecommerce.carrinho.index') }}" title="Carrinho">
                        <i class="iconify" data-icon="jam:shopping-cart"></i><span
                            class="cart-count">{{ carrinho()->count() }}</span>
                    </a>
                </li>
            </ul>
            {{-- nav desktop and collapse mobile --}}
            <div class="collapse navbar-collapse align-self-start mb-0 mb-lg-4" id="menu-nav">
                <ul class="navbar-nav ml-md-auto dark-menu position-relative mt-3 mt-lg-0">
                    <li class="nav-item mr-md-2">
                        <a class="nav-link"
                            href="http://wa.me/{{ config('site.numero_whatsapp') }}?{{ config('site.whatsapp_text_message_parameter') }}"
                            target="_blank" title="Ajuda"><i class="iconify" data-icon="jam:help"></i> Ajuda</a>
                    </li>
                    <li class="nav-item mr-md-2 d-none d-lg-flex">
                        <a class="nav-link" href="{{ route('ecommerce.carrinho.index') }}" title="Carrinho">
                            <i class="iconify" data-icon="jam:shopping-cart"></i> Carrinho <span
                                class="cart-count">{{ carrinho()->count() }}</span>
                        </a>
                    </li>
                    @if (auth('clientes')->check())
                        <li class="d-lg-none">
                            <div class="nav-item nav-link disabled">Sua conta</div>
                        </li>
                        <li class="nav-item mr-md-2 d-lg-none">
                            <a class="nav-link" href="{{ route('ecommerce.cliente.pedidos.index') }}"
                                title="Meus pedidos"><i class="iconify" data-icon="jam:task-list"></i> Pedidos
                                realizados</a>
                            <a class="nav-link" href="{{ route('ecommerce.cliente.meus-dados.index') }}"
                                title="Meus dados"><i class="iconify" data-icon="jam:user-square"></i> Meus dados</a>
                        </li>
                        <li class="nav-item mr-md-2 d-lg-none">
                            <a class="nav-link" href="{{ route('ecommerce.cliente.logout') }}" data-action='logout'
                                title="Sair da conta"><i class="iconify" data-icon="jam:power"></i> Sair da conta</a>
                        </li>
                        <li class="nav-item d-none d-lg-flex">
                            <div class="dropdown">
                                <button class="nav-link btn-not-focus" data-boundary="window" data-toggle="dropdown">
                                    <i class="iconify" data-icon="ic:outline-account-circle"></i> Olá,
                                    {{ somenteNome(auth('clientes')->user()->nome) }}
                                </button>
                                <div class="dropdown-menu shadow border-0">
                                    <a href="{{ route('ecommerce.cliente.pedidos.index') }}"
                                        class="dropdown-item text-left" title="Meus pedidos">Pedidos realizados</a>
                                    <a href="{{ route('ecommerce.cliente.meus-dados.index') }}"
                                        class="dropdown-item text-left" title="Meus dados">Meus dados</a>
                                    <div class="dropdown-divider"></div>
                                    <a href="{{ route('ecommerce.cliente.logout') }}" data-action='logout'
                                        class="dropdown-item text-left" title="Sair da conta">Sair da conta</a>
                                </div>
                            </div>
                        </li>
                        <li class="nav-item ml-md-3 d-none d-lg-block no-hover">
                            <a href="{{ route('ecommerce.cliente.pedidos.index') }}" title="Meus pedidos"
                                class="btn btn-not-focus btn-outline-light text-uppercase btn-rounded btn-p-vertical">
                                Meus pedidos <i class="iconify align-text-bottom" data-icon="jam:chevron-right"></i>
                            </a>
                        </li>
                    @else
                        <li class="nav-item mr-md-1">
                            <button data-modal="#modal-login" title="Minha conta" class="nav-link btn-not-focus"><i
                                    class="iconify" data-icon="ic:outline-account-circle"></i> Minha conta</button>
                        </li>
                        <li class="nav-item ml-md-3 d-none d-lg-block no-hover">
                            <button data-modal="#modal-cadastro" title="Criar nova conta"
                                class="btn btn-not-focus btn-outline-light text-uppercase btn-rounded btn-p-vertical"
                                style="background-color: #8B45A8; color: #fff; border-color: #8B45A8;">
                                Criar conta <i class="iconify align-text-bottom" data-icon="jam:chevron-right"></i>
                            </button>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <div data-page="LandingPage" data-controller="HomeCtrl" data-destino="{{ route('ecommerce.destino.view') }}">

        {{-- PIX DESCONTO --}}
        @if (\TourFacil\Core\Services\Pagamento\DescontoPIXService::isDescontoPixAtivo())
            <div class="pix-desconto-header">
                <span class="emoji">🎉</span> APROVEITE! Semana do Consumidor: Ofertas com até 50% OFF, Cashback e
                <span class="desconto">{{ \TourFacil\Core\Services\Pagamento\DescontoPIXService::getPixDesconto() }}% DE
                    DESCONTO
                    NO PIX</span>
            </div>
        @endif

        {{-- Newsletter --}}
        @include('partials.newsletter-landingpage')

        <section class="section-servicos-populares mt-4 mb-4">
            <div class="container pt-3">
                <div>
                    <h2 class="text-center font-weight-medium pb-2">As atividades mais populares do momento</h2>
                </div>
                <div class="owl-carousel owl-nav-blue owl-nav-container owl-servicos mt-4 owl-padding">
                    @foreach ($servicos_destaques as $index => $servico_destaque)
                        @php($servico_destaque->tags->splice(\TourFacil\Core\Enum\ServicoEnum::LIMITE_TAGS))
                        @php($lazy_class = $index <= 3 ? 'lazyload' : 'owl-lazy')
                        <div class="card-servico item">
                            <a href="{{ route('ecommerce.servico.view', [$servico_destaque->destino->slug, $servico_destaque->categoria->slug, $servico_destaque->slug]) }}"
                                title="{{ $servico_destaque->nome }}">
                                <div class="card-servico-image">
                                    <img src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs="
                                        class="{{ $lazy_class }}"
                                        data-src="{{ $servico_destaque->fotoPrincipal->fotoLarge }}"
                                        alt="{{ $servico_destaque->nome }}">
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <h3 class="ellipsis-2-lines">{{ $servico_destaque->nome }}</h3>
                                    <div class="card-body-items mt-auto">
                                        @foreach ($servico_destaque->tags as $tag_servico)
                                            <div class="card-body-item">
                                                <i class="card-body-icon jam jam-{{ $tag_servico->icone }}"></i>
                                                <span>{{ $tag_servico->descricao }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="mt-auto d-flex justify-content-end align-items-center card-body-price"
                                        style="flex-direction: row;flex-wrap: wrap;">

                                        @if ($servico_destaque->descontoAtivo == null)
                                            <span>A partir de</span>
                                            <strong>R$ {{ formataValor($servico_destaque->valor_venda) }}</strong>
                                        @else
                                            <span style="text-decoration: line-through;">De R$
                                                {{ formataValor($servico_destaque->descontoAtivo->valor_de) }}</span>
                                            <strong style="color: #e73677;">Por R$
                                                {{ formataValor($servico_destaque->descontoAtivo->valor_por) }}</strong>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>


        {{--        Comendado MANTENHA A FLEXIBILIDADE. VERSÃO 1.3.5 --}}

        <section class="container py-5 text-left text-sm-center d-none d-sm-block">
            {{--            <h3 class="text-yellow h1 font-weight-bold mt-2 font-mobile">Mantenha a flexibilidade</h3> --}}
            {{--            <p class="text-muted font-size-1-1 mt-3 mb-0">Use a opção "Reserve agora, pague depois" para garantir sem<br class="d-none d-md-block"> compromisso as atividades que não quer perder.</p> --}}
        </section>






        {{-- Comentado dica de viajantes na versão 1.0.4 --}}

        <div class="section-blog py-5">
            <div class="container">
                <div class="row justify-content-between">
                    <div class="col-12 col-md-7 col-lg-6">
                        <section class="px-lg-4">
                            <h4 class="text-blue h1 font-weight-bold font-mobile">Dicas de viagem</h4>
                            <h5 class="h2 mt-3 font-weight-bold font-mobile">{{ $post['titulo'] }}</h5>
                            <p class="text-justify text-muted">{{ $post['descricao'] }}</p>
                            <a href="{{ $post['url'] }}" target="_blank" title="Continue lendo essa máteria"
                                class="btn btn-rounded btn-outline-dark text-uppercase btn-sm float-right mb-4 mb-md-0">Continue
                                lendo aqui</a>
                        </section>
                    </div>
                    <div class="col-12 col-md-5 col-lg-6">
                        <div class="section-blog-image lazyload" data-bg="{{ $post['image'] }}"></div>
                    </div>
                </div>
            </div>
        </div>
        <section class="section-destinos pt-4">
            <div class="container mt-2">
                <h2 class="h1 font-weight-bold text-center text-blue pb-4">Nossos destinos</h2>
                <div class="owl-carousel owl-nav-blue owl-nav-container owl-padding owl-destinos">
                    @foreach ($destinos as $destino)
                        <div class="item">
                            <a href="{{ route('ecommerce.destino.index', $destino->slug) }}"
                                title="{{ $destino->nome }}">
                                <div class="card card-destino">
                                    <img src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs="
                                        class="card-img lazyload" data-src="{{ $destino->foto_destino }}"
                                        alt="{{ $destino->nome }}">
                                    <div class="card-img-overlay d-flex align-items-center justify-content-center">
                                        <h3 class="card-title text-white">{{ $destino->nome }}</h3>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    </div>
    <div class="container mt-2 mb-4 section-home-partner">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-11 text-center">
                <h5 class="h2 mt-2 mb-3 font-weight-medium">Somos parceiros</h5>
                <div class="row align-items-center justify-content-center pt-4">
                    @foreach ($parceiros as $parceiro)
                        <div class="col-4 col-md mb-sm-0">
                            <img src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs="
                                class="img-fluid lazyload mb-4 mb-md-0" data-src="{{ asset($parceiro['logo']) }}"
                                alt="{{ $parceiro['nome'] }}">
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <hr class="mt-4">

    {{-- Footer --}}
    @include('template.footer-test')

@endsection
