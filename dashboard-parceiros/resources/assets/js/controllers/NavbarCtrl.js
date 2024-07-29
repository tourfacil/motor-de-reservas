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
    }
};
