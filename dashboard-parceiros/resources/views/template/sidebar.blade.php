<div data-controller="SidebarCtrl" class="compact-light-sidebar has-shadow">
    <nav class="side-navbar box-scroll sidebar-scroll">
        <ul class="list-unstyled">
            <li>
                <a href="{{ route('app.dashboard') }}" class="item-menu" title="Página inicial">
                    <i class="la la-columns"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('app.validador.index') }}" class="item-menu" title="Validador de reservas">
                    <i class="la la-check-circle-o"></i>
                    <span>Validador</span>
                </a>
            </li>
            <li>
                <a href="{{ route('app.reservas.index') }}" class="item-menu" title="Reservas">
                    <i class="la la-shopping-cart"></i>
                    <span>Reservas</span>
                </a>
            </li>
            <li>
                <a href="{{ route('app.relatorios.homelist.index') }}" class="item-menu" title="Homelist">
                    <i class="la la-bus"></i>
                    <span>Homelist</span>
                </a>
            </li>
        </ul>
        <span class="sidebar-separator"></span>
        <ul class="list-unstyled">
            <li>
                <a href="#dropdown-faturas" class="item-menu" aria-expanded="false" data-toggle="collapse">
                    <i class="la la-money"></i>
                    <span>Faturas</span>
                </a>
                <ul id="dropdown-faturas" class="collapse list-unstyled">
                    <li>
                        <a href="{{ route('app.faturas.index') }}" class="item-menu" title="Faturas">
                            <i class="la la-money"></i> Faturas
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('app.faturas.fatura-prevista') }}" class="item-menu" title="Faturas previstas">
                            <i class="la la-money"></i> Faturas previstas
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="{{ route('app.servicos.index') }}" class="item-menu" title="Serviços">
                    <i class="la la-cube"></i>
                    <span>Serviços</span>
                </a>
            </li>
            <li>
                <a href="{{ route('app.agenda.index') }}" class="item-menu" title="Serviços">
                    <i class="la la-calendar"></i>
                    <span>Agenda</span>
                </a>
            </li>
            <li>
                <a href="#dropdown-relatorios" class="item-menu" aria-expanded="false" data-toggle="collapse">
                    <i class="la la-archive"></i>
                    <span>Relatórios</span>
                </a>
                <ul id="dropdown-relatorios" class="collapse list-unstyled">
                    <li>
                        <a href="{{ route('app.relatorios.autenticados') }}" class="item-menu" title="Relatório de autenticados">
                            <i class="la la-check-circle"></i> Autenticados
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('app.relatorios.vendidos') }}" class="item-menu" title="Relatório de ingressos vendidos">
                            <i class="la la-ticket"></i> Ingressos vendidos
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
</div>
