<header class="header" data-controller="NavbarCtrl">
    <nav class="navbar fixed-top">
        <div class="navbar-holder">
            <div class="row align-items-center">
                <div class="col-xl-4 col-md-6 col-9 d-flex">
                    <div class="navbar-header w-100 d-flex align-items-center">
                        <a id="toggle-btn" href="#" class="menu-btn ml-3" data-sidebar="false">
                            <span></span>
                            <span></span>
                            <span></span>
                        </a>
                        <div class="navbar-brand d-block ml-4 pl-1 text-truncate">
                            <a href="{{ route("app.dashboard") }}" class="navbar-brand d-block" title="Página inical - Dashboard">
                                <div class="page-header-title text-gradient-02 text-truncate d-block">{{ TourFacil\Core\Models\Fornecedor::find(auth()->user()->fornecedor_id)->razao_social }}</div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-8 col-md-6 col-3 text-right">
                    <a href="{{ route('app.validador.index') }}" class="btn btn-success text-white btn-validador d-none d-md-inline-block" title="Ir para o validador">Validador</a>
                    <ul class="nav-menu list-unstyled d-flex flex-md-row align-items-md-center pull-right">
                        <li class="nav-item d-flex align-items-center">
                            <a href="{{ route('app.meus-dados.index') }}" title="Meus dados"><i class="la la-user text-primary"></i></a>
                        </li>
                        <li class="nav-item d-flex align-items-center">
                            <a id="logout" href="{{ route('logout') }}"><i class="la la-power-off text-primary"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</header>
