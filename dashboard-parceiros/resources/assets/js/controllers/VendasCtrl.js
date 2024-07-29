let VendasCtrl = {

    // Inicialização do controller
    init: () => {

        // Pesquisa do cliente
        VendasCtrl.searchCliente();

        // Click no botao para validar a reserva
        VendasCtrl.onClickButtonValidarReserva();
    },

    // Bind da funcao para pesquisar cliente
    searchCliente: () => {
        let timeout = "";
        let $start_result = $(".start-result");
        let $no_result = $(".not-result");
        let $list_results = $(".list-results");
        $("#search-reserva input").on('keyup', (event) => {
            clearTimeout(timeout);
            // Timeout
            timeout = setTimeout(() => {
                let $this = $(event.currentTarget);
                let pesquisa = $this.val().trim();
                let list_html = "";
                if(pesquisa.length > 2) {
                    // Pesquisa
                    axios.get($this.attr('data-route') + "?q=" + pesquisa).then((response) => {
                        let reservas = response['data']['reservas'] || [],
                            length_results = 0;

                        // Verifica se o retorno é reservas
                        if(reservas.length) {
                            // Quantidade de pedidos
                            length_results = reservas.length;
                            // HTML para a lista de resultados
                            list_html = VendasCtrl.createListResultReservas(reservas, response['data']['view']);
                        }

                        // esconde a informação dos clientes
                        $start_result.addClass('hide');

                        // Coloca os resultados na tela
                        if(length_results === 0) {
                            $no_result.removeClass('hide');
                            $list_results.html("");
                        } else if(list_html !== "") {
                            $no_result.addClass('hide');
                            $list_results.html(list_html);
                        }
                    });
                } else {
                    $list_results.html("");
                    $no_result.addClass('hide');
                    $start_result.removeClass('hide');
                }
            }, 500);
        });
    },

    /**
     * HTML da lista de reservas
     *
     * @param reservas
     * @param url
     * @returns {string}
     */
    createListResultReservas: (reservas, url) => {
        let list_html = "";
        let length_reservas = reservas.length;
        for(let i = 0; i < length_reservas; i++){
            let reserva = reservas[i];
            list_html +=
                `<li class="list-group-item lista-cliente-resultado">
                    <a href="${url}/${reserva.voucher}" title="Ver reserva" target="_blank" class="d-block">
                        <div class="other-message">
                            <div class="media">
                                <div class="media-left align-self-center mr-3">
                                    <div class="media-letter medium">${reserva.servico.nome.charAt(0)}</div>
                                </div>
                                <div class="media-body align-self-center w-75 pr-1">
                                    <div class="other-message-sender">Reserva #${reserva.voucher}</div>
                                    <div class="mt-1 text-primary text-truncate d-block">(${reserva.quantidade}x) ${reserva.servico.nome}</div>
                                </div>
                                <div class="media-right align-self-center">
                                    <i class="la la-file-text-o text-primary"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                </li>`;
        }

        return list_html;
    },

    // Click no botao para validar a reserva
    onClickButtonValidarReserva() {
        $("[data-action='validar']").on('click', (event) => {
            event.preventDefault();
            let $this = $(event.currentTarget);
            // Confirmação de remover o acompanhante
            swal({
                title: "Deseja autenticar este voucher?",
                text: " Não será possível desfazer essa ação.",
                icon: "warning",
                dangerMode: true,
                buttons: ["Não", "Sim, autenticar"],
            }).then((confirm) => {
                if(confirm) {
                    // Loader
                    App.loader.show();
                    // Dados para POST
                    let postData = {reserva: $this.attr('data-reserva')};
                    // POST para validar a reserva
                    let result = axios.post($this.attr('data-url'), postData);
                    result.then((response) => {
                        // Esconde o loader
                        App.loader.hide();
                        // Verifica o resultado
                        if (response['data']['action']['result']) {
                            // Mensagem de sucesso
                            swal(response['data']['action']['title'], response['data']['action']['message'], "success").then(() => {
                                // Loader
                                App.loader.show();
                                // Recarrega a tela
                                location.reload();
                            });
                        } else {
                            swal(response['data']['action']['title'], response['data']['action']['message'], "error");
                        }
                    });
                }
            });

            console.log($this);
        })
    }
};
