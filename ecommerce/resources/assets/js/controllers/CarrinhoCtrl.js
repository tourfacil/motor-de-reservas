let CarrinhoCtrl = {

    // time out
    _timeOut: "",

    // URL para zerar o carrinho
    _urlClear: "",

    // Inicializacao do controller
    init: (page) => {

        // Salva a referencia da caixa de aviso na modal
        ServicoCtrl._avisoModal = $("#comprar .info-error");

        // Salva a URL para limpar o carrinho
        CarrinhoCtrl._urlClear = page.getAttribute('data-limpar');

        // Click no botão para remover o servico do carrinho
        CarrinhoCtrl.onClickButtonRemoveServico();

        // Click no botao voltar mobile
        CarrinhoCtrl.onClickButtonVoltarMobile();

        // Send step checkout
        CarrinhoCtrl.sendStepCheckout();

        // Timeout de compra
        CarrinhoCtrl.countDownTimer();

        // Click no botao para editar o servico
        ServicoCtrl.onClickButtonComprar();

        // Click no botao de adicionar pessoa
        ServicoCtrl.onClickAddPessoa();

        // Click no botao de remover pessoa
        ServicoCtrl.onClickRemoverPessoa();

        // Bind para as modais
        ServicoCtrl.onCloseModals();

        // Click no botao para adicionar ao carrinho
        ServicoCtrl.onClickButtonAdicionarCarrinho();

        // Envio do formulário de acompanhantes
        ServicoCtrl.onSubmitFormPersons();

        // Envio do formulário de dados adicionais
        ServicoCtrl.onSubmitFormAdditional();

        // Click no botao voltar do navegador
        ServicoCtrl.onClickBackButtonNavegador();
    },

    // TimeOut carrinho de compras
    countDownTimer: () => {
        let $countdown = $(".alert-cart time");
        if($countdown.length) {
            // Reinicia o timeout
            clearInterval(CarrinhoCtrl._timeOut);

            // Timestamp para reload
            let minutesToMore = (window.location.hostname.indexOf(".test") !== -1) ? 60 : 20;

            // Set the date we're counting down to
            let countDownDate = new Date();
            countDownDate.setMinutes(countDownDate.getMinutes() + minutesToMore);
            // Timestamp
            countDownDate = countDownDate.getTime();

            // Update the count down every 1 second
            CarrinhoCtrl._timeOut = setInterval(() => {

                // Get todays date and time
                let now = new Date().getTime();

                // Find the distance between now and the count down date
                let distance = countDownDate - now;

                // Time calculations for days, hours, minutes and seconds
                let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                let seconds = Math.floor((distance % (1000 * 60)) / 1000);

                // Coloca zero a esquerda
                minutes = (minutes.toString().length === 1) ? "0" + minutes : minutes;
                seconds = (seconds.toString().length === 1) ? "0" + seconds : seconds;
                let step = (minutes === "00") ? "segundos" : "minutos";

                // Display the result in the element
                if (seconds > 0) {
                    $countdown.html(`${minutes}:${seconds} ${step}`);
                }

                // If the count down is finished, write some text
                if (distance < 0) {
                    clearInterval(CarrinhoCtrl._timeOut);
                    // Limpa o carrinho de compras e atualiza a pagina
                    CarrinhoCtrl.clearCart();
                }
            }, 1000);
        }
    },

    // Funcao chamada para zerar o carrinho de compras
    clearCart: () => {
        if(CarrinhoCtrl._urlClear.length) {
            // Loader
            window.Helpers.loader.show();
            // DELETE para limpar o carrinho
            let result = $.post(CarrinhoCtrl._urlClear, {_method: "DELETE", _token: window.Helpers.getTokenLaravel()});
            result.done(() => {
                window.location.href = window.location.href + "?" + window.Helpers.addURLParameter("expirado", "true");
            });
            result.fail(window.Helpers.loader.hide);
        }
    },

    /** Click no botao voltar mobile */
    onClickButtonVoltarMobile: () => {
        $("button[data-action='voltar']").on('click', () => window.history.back());
    },

    /** Click no botão para remover o servico do carrinho */
    onClickButtonRemoveServico: () => {
        $("[data-action='remove']").on('click', (event) => {
            event.preventDefault();
            // Loader
            window.Helpers.loader.show();
            let $this = $(event.currentTarget);
            let servico = window.carrinho[$this.attr('data-index')] || [];
            // Evento do servico removido Google
            window.Google.sendRemoveFromCart(servico);
            // delete para remover do carrinho
            let result = $.post($this.attr('data-remove'), {_method: "DELETE", _token: window.Helpers.getTokenLaravel()});
            result.done(() => { window.location.reload(); });
        });
    },

    /** Send step checkout Facebook and GTM */
    sendStepCheckout: () => {
        // Quantidade de itens no carrinho
        let length = window.carrinho.length,
            total_cart = 0, array_facebook = [];

        // Envia Checkout para o Google
        window.Google.sendStepCheckout(window.carrinho, 1);

        // Monta o array para o facebook
        for(let i = 0; i < length; i++) {
            let servico = window.carrinho[i];
            total_cart += parseFloat(servico['price']);
            array_facebook.push({id: servico['id'], quantity: 1});
        }

        // Envia Checkout para o Facebook
        window.Facebook.sendStepCheckout(array_facebook, total_cart);
    }
};
