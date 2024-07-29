let CanalVendaCtrl = {

    init: () => {

        // Click no botao para limpar o cache do canal de venda
        CanalVendaCtrl.onClickButtonResetCache();
    },

    /** Click no botao para limpar o cache do canal de venda */
    onClickButtonResetCache: () => {
        $("button[data-action='reset_cache']").on('click', (event) => {
            event.preventDefault();
            let $this = $(event.currentTarget);
            let dados = {canal_venda_id: $this.attr('data-canal')};
            // Mosta o loader
            App.loader.show();
            axios.post($this.attr('data-url'), dados).then((response) => {
                // Mosta o loader
                App.loader.hide();
                // Verifica o resultado
                if(response['data']['response'] !== null) {
                    swal("Cache atualizado", "O cache foi limpo com sucesso!", "success").then(() => {
                        // Atualiza a página
                        window.location.reload();
                    });
                } else {
                    swal("Falha ao limpar o cache", "Não foi possível limpar o cache, tente novamente!", "error");
                }
            })
        })
    }
};
