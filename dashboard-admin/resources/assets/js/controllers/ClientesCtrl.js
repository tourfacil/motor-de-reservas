let ClientesCtrl = {

    // Inicialização do controller
    init: () => {

        // Pesquisa do cliente
        ClientesCtrl.searchCliente();

        // Botao para desativar o cliente
        ClientesCtrl.onClickButtonDesativarCliente();

        // Botao para reativar o cliente
        ClientesCtrl.onClickButtonAtivarCliente();

        ClientesCtrl.onClickButtonResetarSenha();
    },

    // Bind da funcao para pesquisar cliente
    searchCliente: () => {
        let timeout = "";
        let $start_result = $(".start-result");
        let $no_result = $(".not-result");
        let $list_results = $(".list-results");
        $("#search-cliente input").on('keyup', (event) => {
            clearTimeout(timeout);
            // Timeout
            timeout = setTimeout(() => {
                let $this = $(event.currentTarget);
                let pesquisa = $this.val().trim();
                let list_html = "";
                if(pesquisa.length > 0) {
                    // Pesquisa
                    axios.get($this.attr('data-route') + "?q=" + pesquisa).then((response) => {
                        let clientes = response['data']['clientes'] || [],
                            pedidos = response['data']['pedidos'] || [],
                            length_results = 0;

                        // Verifica se o retorno é clientes
                        if(clientes.length) {
                            // Quantidade de clientes
                            length_results = clientes.length;
                            // HTML para a lista de resultados
                            list_html = ClientesCtrl.createListResultClientes(clientes, response['data']['view']);
                        }

                        // Verifica se o retorno é pedidos
                        if(pedidos.length) {
                            // Quantidade de pedidos
                            length_results = pedidos.length;
                            // HTML para a lista de resultados
                            list_html = ClientesCtrl.createListResultPedidos(pedidos, response['data']['view']);
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
     * HTML da lista de clientes
     *
     * @param clientes
     * @param url
     * @returns {string}
     */
    createListResultClientes: (clientes, url) => {
        let list_html = "";
        let length_cliente = clientes.length;
        for(let i = 0; i < length_cliente; i++){
            let cliente = clientes[i];
            list_html +=
                `<li class="list-group-item lista-cliente-resultado">
                    <a href="${url}/${cliente.id}" title="Ver cliente" target="_blank" class="d-block">
                        <div class="other-message">
                            <div class="media">
                                <div class="media-left align-self-center mr-3">
                                    <div class="media-letter medium">${cliente.nome.charAt(0)}</div>
                                </div>
                                <div class="media-body align-self-center">
                                    <div class="other-message-sender">${cliente.nome}</div>
                                    <div class="other-message-time">${cliente.email}</div>
                                </div>
                                <div class="media-right align-self-center">
                                    <i class="la la-edit"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                </li>`;
        }

        return list_html;
    },

    /**
     * HTML da lista de clientes
     *
     * @param pedidos
     * @param url
     * @returns {string}
     */
    createListResultPedidos: (pedidos, url) => {
        let list_html = "";
        let length_pedidos = pedidos.length;
        for(let i = 0; i < length_pedidos; i++){
            let transacao = pedidos[i],
                pedido = transacao.pedido || transacao,
                total_pedido = window.App.formataValor(parseFloat(pedido.valor_total) + parseFloat(pedido.juros), 2);
            list_html +=
                `<li class="list-group-item lista-cliente-resultado">
                    <a href="${url}/${pedido.codigo}" title="Ver pedido" target="_blank" class="d-block">
                        <div class="other-message">
                            <div class="media">
                                <div class="media-left align-self-center mr-3">
                                    <div class="media-letter medium">${pedido.codigo.charAt(0)}</div>
                                </div>
                                <div class="media-body align-self-center">
                                    <div class="other-message-sender">Pedido #${pedido.codigo}</div>
                                    <div class="other-message-time">R$ ${total_pedido}</div>
                                </div>
                                <div class="media-right align-self-center">
                                    <i class="la la-file-text-o"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                </li>`;
        }

        return list_html;
    },

    // Click para desativar o cliente
    onClickButtonDesativarCliente: () => {
        $("[data-action='desativar_cliente']").on('click', (event) => {
            event.preventDefault();
            let $this = $(event.currentTarget), $nome_cliente = $("input#nome"),
                url = $this.attr('data-route'), cliente_id = $this.attr('data-id');
            // Confirma o cancelamento
            swal({
                title: "Deseja desativar este cliente?",
                text: "O cliente " + $nome_cliente.val() + " será desativado.",
                icon: "warning",
                dangerMode: true,
                buttons: ["Fechar", "Sim, desativar"],
            }).then((confirm) => {
                if (confirm) {
                    // Loader
                    App.loader.show();
                    // Post para reativar o usuario
                    axios.post(url, {cliente_id: cliente_id, _method: "DELETE"}).then((response) => {
                        // Loader
                        App.loader.hide();
                        // Verifica o resultado
                        if(response['data']['action']['result']) {
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
        });
    },

    // Click para ativar o cliente
    onClickButtonAtivarCliente: () => {
        $("[data-action='reativar_cliente']").on('click', (event) => {
            event.preventDefault();
            let $this = $(event.currentTarget),
                url = $this.attr('data-route'), cliente_id = $this.attr('data-id');
            // Loader
            App.loader.show();
            // Post para reativar o usuario
            axios.put(url, {cliente_id: cliente_id}).then((response) => {
                // Loader
                App.loader.hide();
                // Verifica o resultado
                if(response['data']['action']['result']) {
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
        });
    },

    // Quando o cliente clica no botão de gerar nova senha
    onClickButtonResetarSenha: () => {
        $("[data-action='resetar_senha']").on('click', (event) => {
            event.preventDefault();
            let element = event.currentTarget;
            let url = element.getAttribute('data-route');
            let cliente_id = element.getAttribute('data-id');

            // Abre uma modal pedindo se o usuário tem certeza que deseja gerar uma nova senha
            swal({
                title: "Gerar nova senha",
                text: "Tem certeza que deseja gerar uma nova senha ?",
                icon: "warning",
                dangerMode: true,
                buttons: ["Não", "Sim"],
            }).then((confirm) => {
                if(confirm) {
                   
                    // Caso o usuário clicar em sim, mostra o loader
                    App.loader.show();

                    let payload = {
                        cliente_id: cliente_id
                    };

                    // Faz uma requisição para a troca de senha
                    axios.post(url, payload).then((response) => {
                        
                        let data = response.data;
                        App.loader.hide();

                        // Mostra a nova senha
                        swal({
                            title: 'Senha alterada',
                            text: `A senha do cliente foi alterada para ${data.senha}`,
                            icon: 'success',
                        })
                    });
                }
            });

        });
    },
};
