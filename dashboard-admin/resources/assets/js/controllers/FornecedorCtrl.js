let FornecedorCtrl = {

    _urlCNPJ: "",

    _urlParceiro: "",

    _dadosParceiro: "",

    // Inicialização do controller
    init:(page) => {

        // OnBlur CNPJ input
        FornecedorCtrl.onBlurInputCNPJ();

        // Envio do 1 formulário de cadastro
        FornecedorCtrl.onSubmitFormFichaCadastro();

        // Envio do 2 formulário de cadastro
        FornecedorCtrl.onSubmitFormDadosFinanceiros();

        // Envio do 3 formulário de cadastro
        FornecedorCtrl.onSubmitFormRegrasTermos();

        // Click no botao para editar o token do split de pagamento
        FornecedorCtrl.onClickButtonEditSplit();

        // Click no botao para editar o usuario
        FornecedorCtrl.onClickButtonEditUser();

        // Click no botao para desativar o usuario
        FornecedorCtrl.onClickButtonDesativarUsuario();

        // Click para reativar o usuario
        FornecedorCtrl.onClickButtonAtivarUsuario();

        // URL para busca do CNPJ
        FornecedorCtrl._urlCNPJ = page.getAttribute('data-cnpj');
    },

    /** Pesquisa os dados do fornecedor pelo CNPJ */
    onBlurInputCNPJ: () => {
        $("input.search_cnpj").blur((event) => {
            let $input = $(event.currentTarget),
                cnpj = $input.val().trim().replace(/\D/g, ''),
                $feedback = $input.next(".invalid-feedback");
            // Caso o campo não esteja vazio
            if(cnpj.length === 14) {
                // Mostra a mensagem de busca
                let noty = App.noty.warning("Buscando informações sobre o CNPJ, aguarde...");
                // Pesquisa os dados do CNPJ
                let resultado = axios.get(FornecedorCtrl._urlCNPJ + "/" + cnpj);
                resultado.then((response) => {
                    noty.close();
                    if(response['status'] === 200) {
                        let dados_cnpj = response['data'];
                        if(typeof response['data']['has_cnpj'] !== "undefined") {
                            $input.addClass("invalid");
                            $feedback.html('Este CNPJ já está em uso.');
                            App.noty.error('Este CNPJ já está em uso');
                        } else if(typeof dados_cnpj['fantasia'] !== "undefined") {
                            // Coloca nos campos os dados do CNPJ
                            $("[name='razao_social']").val(dados_cnpj['nome'].toLowerCase()).trigger("keyup");
                            $("[name='nome_fantasia']").val(dados_cnpj['fantasia'].toLowerCase()).trigger("keyup");
                            $("[name='cep']").val(dados_cnpj['cep'].replace('.', ''));
                            $("[name='endereco']").val(dados_cnpj['logradouro'].toLowerCase() + ", " + dados_cnpj['numero'].toLowerCase()).trigger("keyup");
                            $("[name='bairro']").val(dados_cnpj['bairro'].toLowerCase()).trigger("keyup");
                            $("[name='cidade']").val(dados_cnpj['municipio'].toLowerCase()).trigger("keyup");
                            $("[name='estado']").val(App.estados[dados_cnpj['uf'].toUpperCase()]).trigger("keyup");
                            $("[name='emails']").val(dados_cnpj['email'].toLowerCase());
                            $("[name='phone']").val(dados_cnpj['telefone'].toLowerCase());
                            // Mostra a mensagem de busca
                            App.noty.sucesso("Dados preenchidos :)");
                        } else {
                            App.noty.error('Não encontramos nada para este CNPJ');
                        }
                    } else {
                        App.noty.error('Não encontramos nada para este CNPJ');
                    }
                });
                resultado.catch(() => {App.noty.error('Não encontramos nada para este CNPJ');});
            } else if(cnpj.length > 0 && cnpj.length < 14) {
                $input.addClass("invalid");
                $feedback.html('O CNPJ deve conter 14 caracteres.');
            }
        });
    },

    /** Envio do formulário de cadastro */
    onSubmitFormFichaCadastro: () => {
        $("form[name='ficha-cadastro']").on('submit', (event) => {
            event.preventDefault();
            let $this = $(event.currentTarget);
            // Valida o formulario
            if(App.validateForm($this)) {
                // Mostra o loader
                App.loader.show();
                // Abre a requisão AJAX
                axios.post($this.attr('action'), $this.serialize()).then((response) => {
                    // Remove o loader
                    App.loader.hide();
                    // Verifica o resultado
                    if(response['data']['action']) {
                        // Salva os dados do parceiro
                        FornecedorCtrl._urlParceiro = response['data']['view'];
                        FornecedorCtrl._dadosParceiro = response['data']['partner'];
                        // Coloca o valor do ID em todos os campos
                        $("input.callback_fornecedor_id").val(response['data']['partner']['id']);
                        // Mensagem de sucesso
                        swal("Fornecedor cadastrado", "O fornecedor " + response['data']['partner']['nome_fantasia'] + " foi cadastrado com sucesso!", "success").then(() => {
                            // Next tab
                            FornecedorCtrl.nextTab(2);
                        });
                    } else {
                        swal("Fornecedor não cadastrado", "Não foi possível cadastrar o fornecedor, tente novamente!", "error");
                    }
                }).catch((error) => {App.captureErrorAjax(error)});
            }
        });
    },

    /** Envio do formulário de dados financeiros */
    onSubmitFormDadosFinanceiros: () => {
        $("form[name='financeiro-cadastro']").on('submit', (event) => {
            event.preventDefault();
            let $this = $(event.currentTarget);
            // Valida o formulario
            if(App.validateForm($this)) {
                // Mostra o loader
                App.loader.show();
                // Abre a requisão AJAX
                axios.post($this.attr('action'), $this.serialize()).then((response) => {
                    // Remove o loader
                    App.loader.hide();
                    // Verifica o resultado
                    if(response['data']['action']) {
                        // Mensagem de sucesso
                        swal("Dados cadastrados", "O dados bancários foram salvos com sucesso!", "success").then(() => {
                            // Next tab
                            FornecedorCtrl.nextTab(3);
                        });
                    } else {
                        swal("Dados não cadastrados", "Não foi possível salvar os dados bancários, tente novamente!", "error");
                    }
                }).catch((error) => {App.captureErrorAjax(error)});
            }
        });
    },

    /** Envio do formulário de dados financeiros */
    onSubmitFormRegrasTermos: () => {
        $("form[name='regras-cadastro']").on('submit', (event) => {
            event.preventDefault();
            let $this = $(event.currentTarget);
            // Valida o formulario
            if(App.validateForm($this)) {
                // Mostra o loader
                App.loader.show();
                // Abre a requisão AJAX
                axios.post($this.attr('action'), $this.serialize()).then((response) => {
                    // Remove o loader
                    App.loader.hide();
                    // Verifica o resultado
                    if(response['data']['action']) {
                        // Mensagem de sucesso
                        swal("Regras e termos cadastrados", "As regras e termos foram salvas com sucesso!", "success").then(() => {
                            // Remove o loader
                            App.loader.show();
                            // Redireciona para a página do fornecedor
                            window.location.href = FornecedorCtrl._urlParceiro;
                        });
                    } else {
                        // Mesangem de erro
                        swal("Regras não cadastrados", "Não foi possível salvar as regras e termos, tente novamente!", "error");
                    }
                }).catch((error) => {App.captureErrorAjax(error)});
            }
        });
    },

    /** Edicao do split de pagamento */
    onClickButtonEditSplit: () => {
        $("[data-action='edit-split']").on('click', (event) => {
            event.preventDefault();
            let $this = $(event.currentTarget),
                url = $this.attr('href');
            // Mosta o loader
            App.loader.show();
            // Recupera os dados do split
            axios.get(url).then((response) => {
                // Tira o loader
                App.loader.hide();
                response = response['data'];
                // Verifica a resposta
                if(typeof response['id'] !== "undefined") {
                    // Site do canal de venda
                    $("input#canal_venda_id_edit").val(response['canal_venda']['site']);
                    // Token do split
                    $("input#token_edit").val(response['token']);
                    // Id do cadastro
                    $("input[name='split_pagamento_id']").val(response['id']);
                    // Abre a modal
                    $('#edit-split').modal('show');
                } else {
                    swal(
                        "Falha ao recuperar os dados",
                        "Não foi possível localizar os dados do split, tente novamente!",
                        "error"
                    );
                }
            });
        });
    },

    /** OnClick para editar o usuario */
    onClickButtonEditUser: () => {
        $("[data-action='edit-user']").on('click', (event) => {
            event.preventDefault();
            let $this = $(event.currentTarget),
                url = $this.attr('href');
            // Loader
            App.loader.show();
            // Recupera os dados do campo
            axios.get(url).then((response) => {
                // Loader
                App.loader.hide();
                response = response.data;
                // Modal
                let $modal = $("#edit-user");
                let $select_nivel = $("select#level-edit");
                // Limpa o formulario
                $modal.find("form").trigger('reset');
                $select_nivel.find("option").removeAttr('selected');
                // Coloca os valores nos campos
                $modal.find("#nome-edit").val(response['nome']);
                $modal.find("#email-edit").val(response['email']);
                $modal.find("input[name='usuario_id']").val(response['id']);
                // Seleciona o nivel de acesso
                $select_nivel.find("[value='" + response['level'] +"']").attr('selected', true);
                $select_nivel.selectpicker('refresh');
                // Abre a modal de edição
                $modal.modal('show');
            });
        });
    },

    /** Altera o valor do input para desativar o usuario e envia o formulario */
    onClickButtonDesativarUsuario: () => {
        $("button[data-action='desativar']").on('click', (event) => {
            event.preventDefault();
            // Recupera o input de desativar
            let $input = $("input[name='desativar_usuario']");
            // Coloca o valor no input para desativar
            $input.val("on");
            // Envia o formulario
            $input.parents("form").trigger('submit');
        });
    },

    /** Click para reativar o usuario */
    onClickButtonAtivarUsuario: () => {
        $("[data-action='activate-user']").on('click', (event) => {
            event.preventDefault();
            let $this = $(event.currentTarget),
                url = $this.attr('href'), usuario_id = $this.attr('data-id');
            // Loader
            App.loader.show();
            // Post para reativar o usuario
            axios.put(url, {usuario: usuario_id}).then((response) => {
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

    /**
     * Proxima tab do cadastro
     * @param number_tab
     */
    nextTab: (number_tab) => {
        let $tabs = $("ul#tab_fornecedor li a"),
            length = $tabs.length;
        // Percorre as tabs
        for(let i = 0; i < length; i++) {
            let $tab = $($tabs[i]);
            $tab.removeClass('active').addClass('disabled');
            if((i + 1) === number_tab) {
                $tab.removeClass("disabled").click();
            }
        }
    }
};
