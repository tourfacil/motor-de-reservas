let NavbarCtrl = {

    // Inicializacao do controller
    init: () => {
        // Click no botao de logout
        NavbarCtrl.onClickLinkLogout();
    },

    /** Click no link para fazer logout */
    onClickLinkLogout: () => {
        $("a[data-action='logout']").on('click', (event) => {
            event.preventDefault();
            let $this = $(event.currentTarget);
            // Loader
            window.Helpers.loader.show();
            // Efetua o POST na URL de logout
            let result = $.post($this.attr('href'), {_token: window.Helpers.getTokenLaravel()});
            result.done(() => {
                // Atualiza a tela
                window.location.reload();
            });
        })
    },
};

