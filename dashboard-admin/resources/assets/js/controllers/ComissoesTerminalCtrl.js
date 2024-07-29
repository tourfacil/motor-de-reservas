let ComissoesTerminalCtrl = {

    // Inicializaçao do controller
    init: () => {

        // Botão para efetuar o pagamento
        ComissoesTerminalCtrl.onClickButtonMakePayment();

        // Botao para ver os detalhes do pagamento
        ComissoesTerminalCtrl.onClickButtonDetailPayment();
    },

    // Botão para efetuar o pagamento
    onClickButtonMakePayment: () => {
        $("[data-action='makePayment']").on('click', (e) => {
            e.preventDefault();
            let $this = $(e.currentTarget),
                $modal = $("#view-pagamento"),
                url = $this.attr('href');
            // Loader
            window.App.loader.show();
            // Recupera os dados do pagamento
            axios.get(url).then((response) => {
                // Loader
                window.App.loader.hide();
                let comissao = window.App.formataValor(response['data']['total_comissao'], 2);
                // Coloca os dados na tela
                $modal.find("#nome_terminal").html(response['data']['terminal']['nome']);
                $modal.find("#valor_comissao").html("R$ " + comissao);
                $modal.find("[name='pagamento_id']").val(response['data']['id']);
                $modal.find("[name='valor_pago']").val(comissao);
                // Modal
                $modal.modal('show');
            });
        })
    },

    // Botão para ver os detalhes do pagamento
    onClickButtonDetailPayment: () => {
        $("[data-action='detailPayment']").on('click', (e) => {
            e.preventDefault();
            let $this = $(e.currentTarget),
                $modal = $("#detalhe-pagamento"),
                url = $this.attr('href');
            // Loader
            window.App.loader.show();
            // Recupera os dados do pagamento
            axios.get(url).then((response) => {
                // Loader
                window.App.loader.hide();
                let comissao = window.App.formataValor(response['data']['total_comissao'], 2);
                let valor_pago = window.App.formataValor(response['data']['total_pago'], 2);
                let data_pagamento = response['data']['data_pagamento'].split(" ");
                let data = data_pagamento[0].split("-");
                // Coloca os dados na tela
                $modal.find("#nome_terminal_detalhes").html(response['data']['terminal']['nome']);
                $modal.find("#valor_comissao_detalhes").html("R$ " + comissao);
                $modal.find("#valor_pago_detalhes").html("R$ " + valor_pago);
                $modal.find("#data_pagamento_detalhes").html(`${data[2]}/${data[1]}/${data[0]} ${data_pagamento[1].substr(0,5)}`);
                // Modal
                $modal.modal('show');
            });
        })
    }
};
