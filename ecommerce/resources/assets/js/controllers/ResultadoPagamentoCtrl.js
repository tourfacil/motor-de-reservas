let ResultadoPagamentoCtrl = {

    // Inicializacao do controller
    init: () => {
        // Envia os dados do pedido realizado
        ResultadoPagamentoCtrl.sendPurchase();
    },

    /** Envia os dados do pedido realizado para trackers */
    sendPurchase() {
        if(window.send_purchase === "true") {

            // Essa verificação foi adicionada porque não existia window.pedido['total'] e window.pedido['gtm'] e isso fazia bugar o analitycs
            // Deixei a verificação para evitar futuros BUGS
            if(window.pedido['total'] == undefined || window.pedido['gtm'] == undefined) {

                // Envia o pedido para o Google e ADS
                window.Google.sendPurchaseGtm(window.pedido['codigo'], window.pedido['valor_total'], window.pedido['reservas']);

            } else {

                // Envia o pedido para o Google e ADS
                window.Google.sendPurchaseGtm(window.pedido['codigo'], window.pedido['total'], window.pedido['gtm']);

            }

            // Envia o pedido para o Facebook
            if(window.pedido['total'] == undefined || window.pedido['fcb'] == undefined) {
                window.Facebook.sendPurchase(window.pedido['reservas'], window.pedido['valor_total']);
                console.log(window.pedido['reservas']);
                console.log(window.pedido['valor_total']);
            } else {
                window.Facebook.sendPurchase(window.pedido['fcb'], window.pedido['total']);
            }
        }
    },
};
