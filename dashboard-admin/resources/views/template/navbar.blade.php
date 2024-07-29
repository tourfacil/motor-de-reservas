@php

    $pedidos_pendentes = TourFacil\Core\Models\Pedido::where('status_pagamento', TourFacil\Core\Enum\StatusPagamentoEnum::PENDENTE)
                                                    ->where('metodo_pagamento',  TourFacil\Core\Enum\MetodoPagamentoEnum::PIX)
                                                    ->where('status', TourFacil\Core\Enum\StatusPedidoEnum::AGUARDANDO)->get();
@endphp

<header class="header" data-controller="NavbarCtrl">
    <nav class="navbar fixed-top">
        <div class="navbar-holder">
            <div class="row align-items-center justify-content-between navbar-row">
                <div class="col-xl-8 col-7 d-flex col-sm-9">
                    <div class="navbar-header">
                        <a id="toggle-btn" href="#" class="menu-btn ml-3 {{ ($menu_sidebar == "open") ? "active" : "" }}"
                           data-sidebar="{{ ($menu_sidebar == "open") ? "true" : "false" }}">
                            <span></span>
                            <span></span>
                            <span></span>
                        </a>
                        <div class="navbar-brand ml-4">
                            <select id="canais_venda" data-url="{{ route('app.dashboard.trocar-canal') }}" title="Canais de venda" class="select_navbar">
                                @foreach($canais_composer as $canal_navbar)
                                    @if($canal_navbar->id == canalSession()->getCanal()->id)
                                        <option value="{{ $canal_navbar->id }}" selected>{{ $canal_navbar->site }}</option>
                                    @else
                                        <option value="{{ $canal_navbar->id }}">{{ $canal_navbar->site }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-sm-3 col-3">
                    <ul class="nav-menu list-unstyled d-flex flex-md-row align-items-md-center pull-right">
                        <li class="nav-item d-flex align-items-center">
                            <a href="#" id="sino_pedidos_pendentes" title="Há {{ $pedidos_pendentes->count() }} pedido(s) pendente(s)"><span class="reservas-count">{{$pedidos_pendentes->count()}}</span>
                                <i class="la la-bell-o text-primary"></i>
                            </a>
                        </li>
                        <li class="nav-item d-flex align-items-center">
                            <a href="{{ route('app.meus-dados') }}" title="Meus dados"><i class="la la-user text-primary"></i></a>
                        </li>
                        <li class="nav-item d-flex align-items-center">
                            <a id="logout" href="{{ route('logout') }}"><i class="la la-power-off text-primary"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <span id="link_atualizar_pedidos" data="{{Route('app.pedidos.pix-pendentes')}}"></span>

    <style>

        .reservas-count {
            all: initial;
            background-color: #ff9800;
            position: absolute;
            top: 8px;
            right: -1px;
            padding: 2px;
            border-radius: 5px;
            padding-right: 5px;
            padding-left: 5px;
            color: white;
            font-weight: bold;
        }

        nav.navbar .nav-item>a span {
            position: absolute;
            top: 7px;
            right: -2px;
            border: 3px solid #fff;
            font-size: 13px;
            cursor: pointer;
        }

        .reservas-count * {
            all: unset
        }

    </style>


</header>