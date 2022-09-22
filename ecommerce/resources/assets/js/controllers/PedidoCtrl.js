let PedidoCtrl = {

    init: () => {

        // Click no botao para imprimir o voucher
        PedidoCtrl.onClickButtonPrintVoucher();

        // Scroll para os servios adquiridos
        PedidoCtrl.scrollToServico();
    },

    /** Click no botao para imprimir o voucher */
    onClickButtonPrintVoucher: () => {
        $("button[data-action='print_voucher']").on('click', (event) => {
            event.preventDefault();
            let $this = $(event.currentTarget);
            window.open($this.attr('data-url'), '_blank');
        })
    },

    /** Scroll para os servios adquiridos */
    scrollToServico: () => {
        if(window.location.href.indexOf("#imprimir") > 0) {
            $(window).scrollTop($('#servicos-adquiridos').offset().top - 50);
        }
    }
};
