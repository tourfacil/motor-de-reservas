let NavbarCtrl = {

    _cookie: "menu_sidebar",

    // Inicialização do controller
    init: () => {
        // Toggle menu lateral
        NavbarCtrl.onClickToggleMenu();
        // Click no botão de sair
        NavbarCtrl.onClickLogout();
        // Status da navbar sempre fechada para mobile
        let status = "close";
        // Recupera o status do menu lateral quando for desktop
        if(window.innerWidth > 1024) {
            status = App.cookie.read(NavbarCtrl._cookie) || "close";
        }
        // Abre o menu lateral
        if(status === "open") NavbarCtrl.openSidebar();
        // Select canal de venda
        NavbarCtrl.selectCanalDeVenda();

        NavbarCtrl.onClickBotaoPedidosPendentes();
    },

    /** Toggle menu lateral */
    onClickToggleMenu: () => {
        $("nav a#toggle-btn").on('click', (event) => {
            event.preventDefault();
            if($(event.currentTarget).attr("data-sidebar") === "false") {
                NavbarCtrl.openSidebar();
            } else {
                NavbarCtrl.closeSidebar();
            }
        });
    },

    /** Abre o menu lateral */
    openSidebar: () => {
        $(".compact-light-sidebar").addClass('active');
        $("#content-inner").removeClass("compact");
        $("nav a#toggle-btn").addClass('active').attr("data-sidebar", true);
        App.cookie.create(NavbarCtrl._cookie, "open", 5);
    },

    /** Fecha o menu lateral */
    closeSidebar: () => {
        $(".compact-light-sidebar").removeClass('active');
        $("#content-inner").addClass("compact");
        $("nav a#toggle-btn").removeClass('active').attr("data-sidebar", false);
        App.cookie.create(NavbarCtrl._cookie, "close", 5);
    },

    /** Logout application */
    onClickLogout: () => {
        $("nav a#logout").on('click', (event) => {
            event.preventDefault();
            let $this = $(event.currentTarget);
            App.loader.show();
            // Post logout
            axios.post($this.attr('href')).then((response) => {
                // similar behavior as an HTTP redirect
                window.location.replace(response['data']['url']);
            });
        });
    },

    /** Select Custom Canal de venda **/
    selectCanalDeVenda: () => {
        let $select = $('select.select_navbar');
        // Select custom
        $select.selectpicker({
            liveSearch: true,
            liveSearchPlaceholder: "Procurar canal de venda"
        });
        // Trigger ao trocar de canal de venda
        $select.on('change', (event) => {
            let $this = $(event.currentTarget),
                canal_venda_id = $this.val();
            // Loader
            App.loader.show();
            // Post para trocar o canal
            axios.post($this.attr('data-url'), {canal_id: canal_venda_id}).then((response) => {
                // Caso de certo a troca
                if(response['data']['action']) {
                    // Recarrega a tela
                    location.reload();
                } else {
                    // Loader
                    App.loader.hide();
                    // Mensagem de aviso
                    swal("Op's ocorreu um erro!", "Não foi possível alterar o canal, tente novamente!.", "error");
                }
            });
        });
    },

    onClickBotaoPedidosPendentes: () => {

        $("#sino_pedidos_pendentes").on('click', () => {

            App.loader.show();
            
            let link = $("#link_atualizar_pedidos").attr('data');

            axios.post(link, {}).then((response) => {

                App.loader.hide();

                let data = response.data;

                let pagos = data.pagos;
                let pendentes = data.pendentes;
                let expirados = data.expirados;

                
                let total = pagos + pendentes + expirados;

                swal(`Avaliado(s) ${total} pedido(s) por PIX`, `${pendentes} pedidos continuam pendentes\n${expirados} pedidos expiraram\n${pagos} pedidos foram pagos`, `success`).then(() => {
                    if(pagos > 0 || expirados > 0) {
                        window.location.reload();
                    }
                });
            })
        });
    },
};
