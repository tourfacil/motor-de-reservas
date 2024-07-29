@php($count_destinos = count($destinos))

@if (\TourFacil\Core\Services\Pagamento\DescontoPIXService::isDescontoPixAtivo())
    <style>
        @keyframes pulse {
            0% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(255, 215, 0, 0.7);
            }

            50% {
                transform: scale(1.05);
                box-shadow: 0 0 10px 10px rgba(255, 215, 0, 0);
            }

            100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(255, 215, 0, 0);
            }
        }

        .pix-desconto-header {
            padding: 10px;
            text-align: center;
            background-color: #482777;
            color: white;
            font-weight: 600;
            font-family: Migrena_Grotesque, sans-serif;
            text-transform: uppercase;
            animation: pulse 2s infinite;
        }

        .pix-desconto-header .emoji {
            font-size: 1.2em;
        }

        .pix-desconto-header .desconto {
            font-size: 1.1em;
            color: #FFD700;
        }
    </style>

    <div class="pix-desconto-header">
        <span class="emoji">🎁</span> SUPER PIX TOURFÁCIL - RESERVE AGORA E GANHE
        <span class="desconto">{{ \TourFacil\Core\Services\Pagamento\DescontoPIXService::getPixDesconto() }}% DE DESCONTO
            NO PIX</span><span class="emoji">🎁</span>
    </div>
@else
    {{-- <style>
        .pix-desconto-header {
            padding: 10px;
            text-align: center;
            /*background-color: #e73577;*/
            /*background-color: #8b45a8;*/
            background-color: #482777;
            color: white;
            font-weight: 600;
            font-family: Migrena_Grotesque, sans-serif;
            text-transform: uppercase;
        }
    </style> --}}

    {{-- <div class="pix-desconto-header">
        COMPRE E GANHE - ATÉ 15% DE CASHBACK
    </div> --}}
@endif



{{-- @if (config('campanha.ativo'))
    <style>
        .pix-desconto-header {
            padding: 10px;
            text-align: center;
            /*background-color: #e73577;*/
            /*background-color: #8b45a8;*/
            background-color: #000000;
            color: #f8d700;
            font-weight: 600;
            font-family: Migrena_Grotesque,sans-serif;
            text-transform: uppercase;
        }
    </style>

    <div class="pix-desconto-header">
        {{ str_replace("_", " ", env('CAMPANHA_TEXTO')) }}
    </div>
@endif --}}

<nav class="navbar navbar-expand-lg navbar-light py-2 py-lg-0" data-controller="NavbarCtrl">
    <div class="container flex-row">
        {{-- nav toggler --}}
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#menu-nav">
            <span class="navbar-toggler-icon"></span>
        </button>
        {{-- Logo --}}
        <a class="navbar-brand" href="{{ route('ecommerce.index') }}" title="Página inicial da {{ $destino->nome }}">
            <img src="{{ asset('images/logo_tourfacil.svg') }}" class="d-inline-block align-top"
                alt="Logotipo Tour Fácil">
        </a>
        {{-- nav top mobile --}}
        <ul class="navbar-nav flex-row ml-md-auto d-lg-none">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('ecommerce.carrinho.index') }}" title="Carrinho">
                    <i class="iconify" data-icon="jam:shopping-cart"></i><span
                        class="cart-count">{{ carrinho()->count() }}</span>
                </a>
            </li>
        </ul>
        {{-- nav desktop and collapse mobile --}}
        <div class="collapse navbar-collapse align-self-start mb-0 mb-md-3" id="menu-nav">
            <ul class="navbar-nav ml-md-auto dark-menu position-relative mt-2 mb-1 mt-lg-0 mb-lg-0">
                <li class="nav-item mr-md-2">
                    <button class="nav-link btn-not-focus" data-action="open-search-box" title="Buscar"><i
                            class="iconify" data-icon="jam:search"></i> Busca</button>
                </li>
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
                @else
                    <li class="nav-item">
                        <button data-modal="#modal-login" title="Minha conta" class="nav-link btn-not-focus"><i
                                class="iconify" data-icon="ic:outline-account-circle"></i> Minha conta</button>
                    </li>
                @endif
                <li class="d-lg-none">
                    <div class="nav-item nav-link disabled">Destinos</div>
                </li>
                <li class="nav-item d-lg-none">
                    <button {!! $count_destinos > 1 ? "data-modal='#modal-trocar-destino'" : '' !!} title="Alterar destino" class="nav-link">
                        <i class="iconify" data-icon="jam:map-marker-f"></i> {{ $destino->nome }} @if ($count_destinos > 1)
                            <i class="iconify text-dark" data-icon="jam:chevron-down"></i>
                        @endif
                    </button>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="container sub-nav">
    <div class="nav-scroll-shadows">
        <div class="d-flex align-items-center justify-content-center">
            <div class="sub-nav-content horizontal-scroll" itemscope
                itemtype="https://schema.org/SiteNavigationElement">
                @foreach ($categorias_navbar as $categoria_navbar)
                    @php($active_category = isset($current_categoria) ? ($current_categoria == $categoria_navbar->id ? 'active' : '') : '')
                    <a href="{{ route('ecommerce.categoria.index', [$destino->slug, $categoria_navbar->slug]) }}"
                        itemprop="url" class="{{ $active_category }}"
                        title="{{ $categoria_navbar->nome }} em {{ $destino->nome }}">
                        <i class="iconify" data-icon="{{ iconeCategoria($categoria_navbar->slug) }}"></i>
                        <span itemprop="name">{{ $categoria_navbar->nome }}</span>
                    </a>
                @endforeach
            </div>
            <div class="btn-select-destino d-none d-lg-block">
                <button {!! $count_destinos > 1 ? "data-modal='#modal-trocar-destino'" : '' !!} title="Alterar destino"
                    class="btn btn-purple-outline text-uppercase btn-rounded px-4">
                    {{ $destino->nome }} @if ($count_destinos > 1)
                        <i class="iconify ml-1" data-icon="jam:chevron-down"></i>
                    @endif
                </button>
            </div>
        </div>
    </div>
</div>

{{-- search box --}}
@include('partials.search-box')

{{-- modal para alterar o destino --}}
@include('paginas.modais.trocar-destino')

{{-- Modais de login --}}
@if (!auth('clientes')->check())
    {{-- Modal de cadastro e redefinicao de senha --}}
    @include('paginas.modais.modal-login')
@endif
