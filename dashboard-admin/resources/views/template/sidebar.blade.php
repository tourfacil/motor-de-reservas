<div data-controller="SidebarCtrl"
    class="compact-light-sidebar has-shadow {{ $menu_sidebar == 'open' ? 'active' : '' }}">
    <nav class="side-navbar box-scroll sidebar-scroll">
        <ul class="list-unstyled">
            <li>
                <a href="{{ route('app.dashboard') }}" class="item-menu" title="Página inicial">
                    <i class="la la-columns"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            @if (userIsAdmin())
                <li>
                    <a href="{{ route('app.canal-venda') }}" class="item-menu" title="Canais de venda">
                        <i class="la la-puzzle-piece"></i>
                        <span>Canais de venda</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('app.venda-interna.index') }}" class="item-menu" title="Vendas Internas">
                        <i class="la la-money"></i>
                        <span>Vendas Internas</span>
                    </a>
                </li>
            @endif
            @if (userIsVendedor() == false && userIsAfiliado() == false)
                <li>
                    <a href="{{ route('app.fornecedores.index') }}" class="item-menu" title="Fornecedores">
                        <i class="la la-suitcase"></i>
                        <span>Fornecedores</span>
                    </a>
                </li>
            @endif
            @if (userIsVendedor())
                <li>
                    <a href="{{ route('app.venda-interna.index') }}" class="item-menu" title="Vendas Internas">
                        <i class="la la-money"></i>
                        <span>Vendas Internas</span>
                    </a>
                </li>
            @endif
        </ul>
        @if (userIsVendedor() == false && userIsAfiliado() == false)
            <span class="sidebar-separator"></span>
            <ul class="list-unstyled">
                <li>
                    <a href="{{ route('app.destinos.index') }}" class="item-menu" title="Destinos">
                        <i class="la la-map"></i>
                        <span>Destinos</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('app.categorias.index') }}" class="item-menu" title="Categorias">
                        <i class="la la-flag-o"></i>
                        <span>Categorias</span>
                    </a>
                </li>
                <li>
                    <a href="#dropdown-servicos" class="item-menu" aria-expanded="false" data-toggle="collapse">
                        <i class="la la-cube"></i>
                        <span>Serviços</span>
                    </a>
                    <ul id="dropdown-servicos" class="collapse list-unstyled">
                        <li>
                            <a href="{{ route('app.servicos.index') }}" class="item-menu">
                                <i class="la la-list-alt"></i> Gerenciar serviços
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('app.agenda.index') }}" class="item-menu">
                                <i class="la la-calendar"></i> Agenda serviços
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('app.servicos.pendentes.index') }}" class="item-menu">
                                <i class="la la-hourglass-2"></i> Serviços pendentes
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('app.servicos.avaliacoes.index') }}" class="item-menu">
                                <i class="la la-star"></i> Avaliações
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="{{ route('app.banners.index') }}" class="item-menu" title="Banners">
                        <i class="la la-photo"></i>
                        <span>Banners</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('app.newsletter.index') }}" class="item-menu" title="Newsletters">
                        <i class="la la-bullhorn"></i>
                        <span>Newsletters</span>
                    </a>
                </li>
                @if (UserIsAdmin())
                    <a href="#dropdown-descontos" class="item-menu" aria-expanded="false" data-toggle="collapse">
                        <i class="la la-pencil-square"></i>
                        <span>Descontos</span>
                    </a>
                    <ul id="dropdown-descontos" class="collapse list-unstyled">
                        <li>
                            <a href="{{ route('app.descontos.desconto.index') }}" class="item-menu">
                                <i class="la la-pencil-square"></i> Descontos
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('app.descontos.cupom.index') }}" class="item-menu">
                                <i class="la la-pencil-square"></i> Cupons
                            </a>
                        </li>
                    </ul>
                    <li>
                        <a href="#dropdown-afiliados" class="item-menu" aria-expanded="false" data-toggle="collapse">
                            <i class="la la-users"></i>
                            <span>Afiliados</span>
                        </a>
                        <ul id="dropdown-afiliados" class="collapse list-unstyled">
                            <li>
                                <a href="{{ route('app.afiliados.index') }}" class="item-menu">
                                    <i class="la la-user-plus"></i> Gerenciar afiliados
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('app.relatorios.afiliados.index2') }}" class="item-menu">
                                    <i class="la la-money"></i> Relatório de vendas
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('app.relatorios.afiliados.index') }}" class="item-menu">
                                    <i class="la la-ticket"></i> Ingressos vendidos
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="#dropdown-vendedores" class="item-menu" aria-expanded="false" data-toggle="collapse">
                            <i class="la la-users"></i>
                            <span>Vendedores</span>
                        </a>
                        <ul id="dropdown-vendedores" class="collapse list-unstyled">
                            {{-- <li>
                                <a href="{{ route('app.vendedores.index') }}" class="item-menu">
                                    <i class="la la-user-plus"></i> Gerenciar afiliados
                                </a>
                            </li> --}}
                            <li>
                                <a href="{{ route('app.relatorios.vendedores.index') }}" class="item-menu">
                                    <i class="la la-money"></i> Relatório de vendas
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('app.relatorios.vendedores.index2') }}" class="item-menu">
                                    <i class="la la-ticket"></i> Ingressos vendidos
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="#dropdown-faturas" class="item-menu" aria-expanded="false" data-toggle="collapse">
                            <i class="la la-money"></i>
                            <span>Faturas</span>
                        </a>
                        <ul id="dropdown-faturas" class="collapse list-unstyled">
                            <li>
                                <a href="{{ route('app.faturas.index') }}" class="item-menu">
                                    <i class="la la-money"></i> Faturas
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('app.faturas.pendente-pagamento') }}" class="item-menu">
                                    <i class="la la-money"></i> Faturas para pagamento
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('app.faturas.fatura-prevista') }}" class="item-menu">
                                    <i class="la la-money"></i> Faturas previstas
                                </a>
                            </li>
                        </ul>
                    </li>
                @ENDIF
            </ul>
        @endif
        <span class="sidebar-separator"></span>
        <ul class="list-unstyled">
            @if (userIsAdmin() || userIsVendedor())
                <li>
                    <a href="{{ route('app.pedidos.index') }}" class="item-menu" title="Pedidos do site">
                        <i class="la la-shopping-cart"></i>
                        <span>Pedidos do site</span>
                    </a>
                </li>
            @endif
            @if (userIsAfiliado() == false)
                <li>
                    <a href="{{ route('app.clientes.index') }}" class="item-menu" title="Lista de clientes">
                        <i class="la la-users"></i>
                        <span>Lista de clientes</span>
                    </a>
                </li>
            @endif
            <li class="d-none">
                <a href="#dropdown-terminais" class="item-menu" aria-expanded="false" data-toggle="collapse">
                    <i class="la la-desktop"></i>
                    <span>Terminais CDI</span>
                </a>
                <ul id="dropdown-terminais" class="collapse list-unstyled">
                    <li>
                        <a href="{{ route('app.terminais.index') }}" class="item-menu" title="Gerenciar terminais">
                            <i class="la la-list-alt"></i> Gerenciar terminais
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('app.terminais.relatorios.index') }}" class="item-menu"
                            title="Relatório de vendas">
                            <i class="la la-cart-plus"></i> Relatório de vendas
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('app.terminais.comissoes.index') }}" class="item-menu"
                            title="Pagamento de comissões">
                            <i class="la la-money"></i> Pagamento de comissões
                        </a>
                    </li>
                </ul>
            </li>
            @if (userIsAdmin())
                <li>
                    <a href="#dropdown-relatorios" class="item-menu" aria-expanded="false" data-toggle="collapse">
                        <i class="la la-archive"></i>
                        <span>Relatórios</span>
                    </a>
                    <ul id="dropdown-relatorios" class="collapse list-unstyled">
                        <!--
                        <li>
                            <a href="{{ route('app.relatorios.autenticados.index') }}" class="item-menu" title="Relatório de autenticados">
                                <i class="la la-check-circle"></i> Autenticados
                            </a>
                        </li>
                        -->
                        <li>
                            <a href="{{ route('app.relatorios.vendidos.index2') }}" class="item-menu"
                                title="Relatório de ingressos vendidos">
                                <i class="la la-ticket"></i> Ingressos vendidos
                            </a>
                        </li>
                        {{-- <li>
                            <a href="{{ route('app.relatorios.afiliados.index2') }}" class="item-menu" title="NOVO - Ingressos vendidos">
                                <i class="la la-ticket"></i> NOVO - Ingressos vendidos
                            </a>
                        </li> --}}
                        <li>
                            <a href="{{ route('app.relatorios.fornecedores.index') }}" class="item-menu"
                                title="Relatório de fornecedores com vendas">
                                <i class="la la-bullhorn"></i> Fornecedores
                            </a>
                        </li>
                        {{--                        <li> --}}
                        {{--                            <a href="{{ route('app.relatorios.afiliados.index') }}" class="item-menu" title="Relatório de fornecedores com vendas"> --}}
                        {{--                                <i class="la la la-users"></i> Afiliados --}}
                        {{--                            </a> --}}
                        {{--                        </li> --}}
                        <li>
                            <a href="{{ route('app.relatorios.homelist.index') }}" class="item-menu"
                                title="Relatório de homelist">
                                <i class="la la-bus"></i> Homelist
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('app.descontos.cupom.relatorio') }}" class="item-menu"
                                title="Relatório de cupons">
                                <i class="la la-pencil-square"></i> Cupons
                            </a>
                        </li>
                        @if (UserIsAdmin())
                            <li>
                                <a href="{{ route('app.relatorios.mala-pronta') }}" target="_blank"
                                    class="item-menu" title="Relatório de cupons">
                                    <i class="la la-suitcase"></i> Mala Pronta
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('app.relatorios.relatorio-disponibilidade') }}" class="item-menu"
                                    title="Relatório">
                                    <i class="la la-users"></i> Disponibilidade
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('app.relatorios.relatorio-reserva-com-detalhes') }}"
                                    target="_blank" class="item-menu" title="Relatório">
                                    <i class="la la-list"></i> Reservas com Detalhes
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
                <li>
                    <a href="{{ route('app.colaboradores.index') }}" class="item-menu" title="Colaboradores">
                        <i class="la la-user-plus"></i>
                        <span>Colaboradores</span>
                    </a>
                </li>
            @endif
            @if (userIsAfiliado())
                <li>
                    <a href="{{ route('app.relatorios.afiliados.index') }}" class="item-menu" title="Colaboradores">
                        <i class="la la-ticket"></i>
                        <span>Relatório de vendas</span>
                    </a>
                </li>
            @endif
            @if (userIsVendedor())
                <li>
                    <a href="{{ route('app.servicos.index') }}" class="item-menu" title="Colaboradores">
                        <i class="la la-bus"></i>
                        <span>Gerenciar serviços</span>
                    </a>
                </li>
            @endif
        </ul>
    </nav>
</div>
