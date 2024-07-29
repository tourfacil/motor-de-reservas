let PagamentoPixCtrl = {

    link_pesquisa: '',

    init: () => {

        PagamentoPixCtrl.link_pesquisa = $("#link-pesquisa").attr('data')
        
        PagamentoPixCtrl.onClickCopyPix();

        PagamentoPixCtrl.consultarStatusPix();

    },

    /**
    * Responsavel por controlar o botão de copiar código PIX
    * */
    onClickCopyPix: () => {
        $("#copiaCodigoPix").on('click', (event) => {

            let copyText = document.getElementById("codigo_pix").innerText;
            click_copy(copyText);
            return swal('Código PIX copiado', 'O código pix foi copiado para a área de transferência.', 'success');
        });
    },

    // Faz uma requisição para entender o status do pedido.
    // Caso retorne que ainda esta pendente, não faz nada
    // Caso retorne que esta expirado ou pago, da um update na página.
    // Apos o update, o servidor cuida dos procedimentos e redirects necessários
    consultarStatusPix: () => {

        setTimeout(() => {

            let link = PagamentoPixCtrl.link_pesquisa;

            axios.post(link, {}).then((response) => {

                let data = response.data;
                let status = data.status;

                if(status) {
                    window.Helpers.loader.show();
                    window.location.reload();
                } else {
                    PagamentoPixCtrl.consultarStatusPix();
                }                
            }).catch((error) => {
                window.location.reload();
            });

        }, 3000);
    }

};
