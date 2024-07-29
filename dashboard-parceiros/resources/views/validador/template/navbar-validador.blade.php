<div id="top" data-controller="SidebarCtrl">
    <div id="menu" class="">
        <div class="title">
            <a href="javascript:void(0)" class="trigger" data-menu-trigger>
                <i class="fas fa-bars"></i>
            </a>
            <span class="desc">Menu</span>
            <a href="javascript:void(0)" class="close" data-menu-close>
                <i class="fas fa-angle-double-left"></i>
            </a>
        </div>
        <ul>
            <li><a href="{{ route('app.validador.index') }}">Autenticar ingresso</a></li>
            <li><a href="{{ route('app.validador.index', ['tipo' => strtolower(\PortalGramado\Admin\Enum\ValidacaoEnum::VALIDACAO_CPF)]) }}">Buscar por CPF</a></li>
            <li>
                <a target="_blank" href="https://compradeingressos.com.br/gramado/panel_users/sign_in">CompraDeIngressos.com.br</a>
            </li>
            <li>
                <a target="_blank" href="https://old.ingressos.com.br/gramado/panel_users/sign_in">(Antigo) Ingressos.com.br</a>
            </li>
        </ul>
    </div>

    <h2>Autenticador</h2>
</div>
