let CalculadoraCtrl = {

    // Inicializacao do controller
    init: () => {

        // Calcular porcentagem
        CalculadoraCtrl.onSubmitFormCalcular();
    },

    // Envio do formulario para calcular a porcentagem
    onSubmitFormCalcular: () => {
        $("form[name='calcular']").on('submit', (event) => {
            event.preventDefault();

            let $this = $(event.currentTarget);
            let comissao = $this.find("input[name='comissao']").val(),
                net_servico = $this.find("input[name='net_servico']").val(),
                net_variacao = $this.find("input[name='net_variacao']").val();

            // Ação da janela
            let to = $this.find("input[name='to']").val();

            // Retira a pontuacao brasileira
            net_servico = net_servico.replace(".", "").replace(",", ".");
            net_variacao = net_variacao.replace(".", "").replace(",", ".");

            // Porcentagem encima do valor NET
            let porcent_net = ((net_variacao / net_servico) * 100).toFixed(2);
            let markup = (100 / (100 - comissao)).toFixed(5);

            // This will post a message to the parent
            window.opener.ServicoCtrl.recebeValoresCalculadora({
                encima_net: porcent_net,
                markup: markup,
                acao: to
            }, "*");

            window.close();
        });
    }
};