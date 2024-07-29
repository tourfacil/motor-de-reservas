let ServicoCtrl = {

    // Url do servico
    _urlServico: "",

    // Dados do serviço cadastrado
    _dadosServico: "",

    // Inicialização do controller
    init: () => {

        // Envio do formulario do descritivo do serviço
        ServicoCtrl.onSubmitFormDescricaoServico();

        // Envio do formulario de cadastro da categoria no serviço
        ServicoCtrl.onSubmitFormCadastroCategoriaServico();

        // Envio do formulario de cadastro das regras e info no voucher
        ServicoCtrl.onSubmitFormRegrasVoucher();

        // Envio do formulario de cadastro do campo adicional
        ServicoCtrl.onSubmitFormCadastroCampoAdicional();

        // Envio do formulario das fotos do serviço
        ServicoCtrl.onSubmitFormFotosServico();

        // Envio do formulario de cadastro das variacoes
        ServicoCtrl.onSubmitFormCadastroVariacaoServico();

        // Click no botao para adicionar categoria
        ServicoCtrl.onClickAdicionarCategoria();

        // Change no select na modal para cadastrar as seções da categoria
        ServicoCtrl.onChangeCategoriaCadastroModal();

        // Click no botão e no input para procurar o arquivo (Mesmo que o DestinoCtrl)
        DestinoCtrl.onClickSearchFile();

        // Preview da imagem
        ServicoCtrl.onChangePhoto();

        // Mudança no tipo de corretagem
        ServicoCtrl.onChangeSelectTipoCorretagem();

        // Click no botao proximo passo formulario
        ServicoCtrl.onClickButtonProximoPasso();

        // Click no botao para editar o campo adicional
        ServicoCtrl.onClickButtonEditField();

        // Click no botao para remover o campo adicional
        ServicoCtrl.onClickButtonDeleteCampo();

        // Click no botao para reativar o campo adicional
        ServicoCtrl.onClickButtonActivateField();

        // Click no botao para editar a foto do servico
        ServicoCtrl.onClickButtonEditPhoto();

        // Click no botao para remover a foto
        ServicoCtrl.onClickButtonDeleteFoto();

        // Click no botao para editar a categoria
        ServicoCtrl.onClickButtonEditCategoria();

        // Click no botao para remover a categoria do servico
        ServicoCtrl.onClickButtonDeleteCategoria();

        // Click no botao para editar a variacao do servico
        ServicoCtrl.onClickButtonEditVariation();

        // Click no botao para remover a variacao do servico
        ServicoCtrl.onClickButtonDeleteVariacao();

        // Click no botao para reativar a variacao
        ServicoCtrl.onClickButtonActivateVariation();

        // Click no botao para editar a comissao da variacao
        ServicoCtrl.onClickButtonEditComissao();

        // Click no botao para editar o markup
        ServicoCtrl.onClickButtonEditMarkup();

        // Click no botao para adicionar uma nova tag
        ServicoCtrl.onClickButtonOpenModalTag();

        // Click no botao para editar uma nova tag
        ServicoCtrl.onClickButtonEditTag();

        // Pesquisa para filtrar os icones para tag
        ServicoCtrl.onChangeInputSearchIcon();

        // Altera o valor do input para deletar a tag serviço
        ServicoCtrl.onClickButtonDeleteTag();
    },

    // Click no botao para editar a comissao da variacao
    onClickButtonEditComissao: () => {
        $("[data-action='toggleEditComissao']").on('click', (event) => {
            event.preventDefault();
            let $comissao = $(".toggleComissao");
            let $field_edit_comissao = $("input[name='edit_comissao']");
            if($comissao.hasClass('hide')) {
                $comissao.find("input").attr('data-required', true).attr('required', true).removeAttr('disabled');
                $comissao.removeClass('hide');
                $field_edit_comissao.val('on');
            } else {
                $comissao.find("input").removeAttr('data-required').removeAttr('required').attr('disabled', true);
                $comissao.addClass('hide');
                $field_edit_comissao.val('off');
            }
        });
    },

    // Recebe os valores da calculadora
    recebeValoresCalculadora: (message) => {

        // Recupera os valores passados
        let markup = message.markup,
            net_porcent = message.encima_net;

        // Verifica se é a modal de cadastro
        if(message.acao === "new_variation") {
            $("#new_variation_percent").val(net_porcent);
            $("#new_variation_markup").val(markup);
        }

        // Verifica se é a modal de edição
        if(message.acao === "edit_variation") {
            $("#edit_variation_percent").val(net_porcent);
            $("#edit_variation_markup").val(markup);
        }
    },

    // Envio do formulário de descrição do serviço
    onSubmitFormDescricaoServico: () => {
        $("form[name='descricao']").on('submit', (event) =>{
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
                        // Salva os do destino
                        ServicoCtrl._urlServico = response['data']['view'];
                        ServicoCtrl._dadosServico = response['data']['servico'];
                        // Coloca a URL do destino no botão
                        $("button[data-action='add-categoria']").attr('data-route', response['data']['destino']);
                        // Coloca o valor do ID em todos os campos
                        $("input.callback_servico_id").val(response['data']['servico']['id']);
                        // Mensagem de sucesso
                        swal("Serviço cadastrado", "O serviço " + response['data']['servico']['nome'] + " foi cadastrado com sucesso!", "success").then(() => {
                            // Next tab
                            ServicoCtrl.nextTab(2);
                        });
                    } else {
                        swal("Serviço não cadastrado", "Não foi possível cadastrar o serviço, tente novamente!", "error");
                    }
                }).catch((error) => {App.captureErrorAjax(error)});
            }
        });
    },

    // Envio do formulário para cadastro da categoria no serviço
    onSubmitFormCadastroCategoriaServico: () => {
        $("form[name='new_secao_categoria']").on('submit', (event) => {
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
                        swal(response['data']['action']['title'], response['data']['action']['message'], "success").then(() => {
                            // Fecha a modal
                            $("#add-categoria").modal('hide');
                            // Next tab
                            ServicoCtrl.nextTab(3);
                        });
                    } else {
                        swal(response['data']['action']['title'], response['data']['action']['message'], "error");
                    }
                }).catch((error) => {App.captureErrorAjax(error)});
            }
        });
    },

    // Envio do formulario de cadastro das regras e info no voucher
    onSubmitFormRegrasVoucher: () => {
        $("form[name='regras_servico_voucher']").on('submit', (event) => {
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
                        swal(response['data']['action']['title'], response['data']['action']['message'], "success").then(() => {
                            // Next tab
                            ServicoCtrl.nextTab(4);
                        });
                    } else {
                        swal(response['data']['action']['title'], response['data']['action']['message'], "error");
                    }
                }).catch((error) => {App.captureErrorAjax(error)});
            }
        });
    },

    // Envio do formulario de cadastro do campo adicional
    onSubmitFormCadastroCampoAdicional: () => {
        $("form[name='new_field']").on('submit', (event) => {
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
                        swal(response['data']['action']['title'], response['data']['action']['message'], "success").then(() => {
                            // Pergunta se quer adicionar mais um campo
                            swal({
                                title: "Cadastrar mais um campo?",
                                text: "Você gostaria de cadastrar mais um campo ou ir para próximo passo?",
                                icon: "info",
                                dangerMode: true,
                                buttons: ["Próximo passo", "Novo campo"],
                            }).then((confirm) => {
                                // Caso queira adicionar mais um campo
                                if (confirm) {
                                    // Reseta o formulario de cadastro
                                    $("form[name='new_field']").trigger('reset');
                                } else {
                                    // Fecha a modal
                                    $("#add-field").modal('hide');
                                    // Next tab
                                    ServicoCtrl.nextTab(6);
                                }
                            });
                        });
                    } else {
                        swal(response['data']['action']['title'], response['data']['action']['message'], "error");
                    }
                }).catch((error) => {App.captureErrorAjax(error)});
            }
        });
    },

    // Envio do formulario das fotos do serviço
    onSubmitFormFotosServico: () => {
        $("form[name='fotos_servico']").on('submit', (event) => {
            event.preventDefault();
            let $this = $(event.currentTarget);
            let formData = new FormData($this[0]);
            // Valida o formulario
            if(App.validateForm($this)) {
                // Mostra o loader
                App.loader.show();
                // Abre a requisão AJAX
                axios.post($this.attr('action'), formData).then((response) => {
                    // Remove o loader
                    App.loader.hide();
                    // Verifica o resultado
                    if(response['data']['action']) {
                        // Mensagem de sucesso
                        swal(response['data']['action']['title'], response['data']['action']['message'], "success").then(() => {
                            // Mostra o loader
                            App.loader.show();
                            // Redireciona para a página do serviço
                            window.location.href = ServicoCtrl._urlServico;
                        });
                    } else {
                        swal(response['data']['action']['title'], response['data']['action']['message'], "error");
                    }
                }).catch((error) => {App.captureErrorAjax(error)});
            }
        })
    },

    // Mudança no select do tipo de corretagem
    onChangeSelectTipoCorretagem: () => {
        $("select[name='tipo_corretagem']").on('change', (event) => {
            event.preventDefault();
            let $this = $(event.currentTarget),
                tipo = $this.val();

            // Elementos
            let $percentual = $(".corretagem-percentual");
            let $fixo = $(".corretagem-valor-fixo");

            // Casos no select
            if(tipo === "CORRETAGEM_FIXA") {
                // Esconde e desabilita o percentual
                $percentual.addClass('hide');
                $percentual.find("input").val("").attr('disabled', true).removeAttr('required');
                // Mostra e habilita o valor fixo
                $fixo.removeClass('hide');
                $fixo.find("input").removeAttr('disabled').attr('required', true);
            } else if(tipo === "CORRETAGEM_PORCENTUAL") {
                // Esconde e desabilita o valor fixo
                $fixo.addClass('hide');
                $fixo.find("input").val("").attr('disabled', true).removeAttr('required');
                // Mostra e habilita o percentual
                $percentual.removeClass('hide');
                $percentual.find("input").removeAttr('disabled').attr('required', true);
            } else {
                // Esconde e desabilita o valor fixo
                $fixo.removeClass('hide');
                $fixo.find("input").val("").attr('disabled', true).removeAttr('required');
                // Esconde e desabilita o percentual
                $percentual.addClass('hide');
                $percentual.find("input").val("").attr('disabled', true).removeAttr('required');
            }
        });
    },

    // Preview das fotos
    onChangePhoto: () => {
        $("input#callback_fotos").on('change', (event) => {
            // When cancel select image
            if(event.currentTarget.files.length === 0) return;

            // Recupera as imanges selecionadas
            let imagens = event.currentTarget.files,
                length = imagens.length;

            // Onde a imagem vai ficar
            let $place = $("input#fotos_servico"),
                $error = $place.siblings(".invalid-feedback"),
                nome_imagens = "";

            // Remove as imagens anteriores
            // Ele nao acumula as fotos selecionadas
            $(".new_imagens").addClass('hide');

            // Percorre as imagens selecionadas
            for(let i = 0; i < length; i++) {
                let imagem = imagens[i];

                // Valid extensions image
                let is_valid = App.validExtensionFile(imagem.name, [
                    ".jpg", ".jpeg", ".png"
                ]);

                // Valida o tamanho da imagem
                if(! App.validMaxSize(imagem)) {
                    swal("A imagem excede os 3MB", "A imagem " + imagem.name + " é maior que 3MB, diminua o peso dela e tente novamente!", "error");
                    return false;
                }

                // If is valid image load to preview
                if(is_valid) {
                    let reader = new FileReader();
                    // Concatena o nome das imagens
                    nome_imagens += imagem.name + ";";
                    // Save file to form
                    $place.val(nome_imagens);
                    // Load preview image
                    reader.onload = function(){
                        let html =
                            "<div class=\"col-xl-3 mb-3 new_imagens\">" +
                                "<div class=\"d-inline-block position-relative w-100\">" +
                                    "<img src=\"" + reader.result + "\" alt=\"" + imagem.name + "\" class=\"img-fluid border-3 shadow-sm\">" +
                                "</div>" +
                            "</div>";
                        // Preview da foto
                        $('.list-imagens-servico').append(html);
                    };
                    reader.readAsDataURL(imagem);
                } else {
                    $place.addClass('invalid');
                    $error.html("Não foi possível carregar o arquivo, tente novamente!");
                }
            }
        });
    },

    // Change no select na modal para cadastrar as seções da categoria
    onChangeCategoriaCadastroModal: () => {
        $("select#categoria-cadastro").on('change', (event) => {
            let $this = $(event.currentTarget),
                url = $this.attr('data-route'),
                categoria_id = $this.val();
            // Loader
            App.loader.show();
            // Recupera os dados do destino com as categorias
            axios.get(url + "/" + categoria_id).then((response) => {
                // Loader
                App.loader.hide();
                let html = "";
                // secoes da catoria
                let secoes = response['data']['secoes_categoria'],
                    length = secoes.length;
                // Percorre as secoes
                for(let i = 0; i < length; i++) {
                    let secao = secoes[i];
                    html +=
                        "<div class=\"col-xl-6 mt-2 mb-2\">\n" +
                            "<div class=\"styled-checkbox\">\n" +
                                "<input type=\"checkbox\" name=\"secoes[]\" id=\"secao-" + secao['id'] + "\" value=\"" + secao['id'] + "\">\n" +
                                "<label for=\"secao-" + secao['id'] + "\">" + secao['nome'] +"</label>\n" +
                            "</div>\n" +
                        "</div>";
                }
                // Coloca as secoes na lista
                $(".list-secoes-categoria").html(html);
            });
        });
    },

    // Adicionar categoria ao serviço
    onClickAdicionarCategoria: () => {
        $("button[data-action='add-categoria']").on('click', (event) => {
            event.preventDefault();
            let $this = $(event.currentTarget),
                url = $this.attr('data-route'),
                $modal = $($this.attr('data-target'));
            // Loader
            App.loader.show();
            // Recupera os dados do destino com as categorias
            axios.get(url).then((response) => {
                // Loader
                App.loader.hide();
                // Verifica se existe categorias para o destino
                if(response['data']['categorias'].length > 0) {
                    let options = "<option value='' selected disabled>Selecione um categoria</option>",
                        categorias = response['data']['categorias'],
                        length = response['data']['categorias'].length;
                    // Percorre as categorias
                    for(let i = 0; i < length; i++) {
                        options += `<option value='${categorias[i].id}'>${categorias[i].nome}</option>`;
                    }
                    // Atualiza o select das categorias
                    $("select#categoria-cadastro").html(options);
                    $("label[for='categoria-cadastro']").html(`Categorias disponíveis em ${response['data'].nome}`);
                    // Zera a lista de seções
                    $(".list-secoes-categoria").html("<div class='col-xl-12'>" +
                        "<div class='alert alert-primary-bordered alert-lg square'>" +
                        "<p class='m-0'>Selecione uma categoria.</p></div></div>");
                    // Abre a modal
                    $modal.modal('show');
                } else {
                    // Caso não encontre o destino
                    swal("Sem categorias para o destino!", "O destino não possui categorias cadastradas, tente novamente.", "error");
                }
            }).catch(() => {
                // Loader
                App.loader.hide();
                // Caso não encontre o destino
                swal("Destino não encontrado!", "Não foi possível encontrar o destino, tente novamente.", "error");
            });
        });
    },

    // Click no botao proximo passo no formulario
    onClickButtonProximoPasso: () => {
        $("button[data-action='nextStep']").on('click', (event) => {
            event.preventDefault();
            ServicoCtrl.nextTab(6);
        });
    },

    // Edição do campo adicional
    onClickButtonEditField: () => {
        $("[data-action='edit-field']").on('click', (event) => {
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
                let $modal = $("#edit-field");
                // Limpa o formulario
                $modal.find("form").trigger('reset');
                // Coloca os valores nos campos
                $modal.find("#edit_field_nome").val(response['campo']);
                $modal.find("#edit_field_placeholder").val(response['placeholder']);
                $modal.find("input[name='campo_id']").val(response['id']);
                $modal.find("[data-field='" + response['obrigatorio'] + "']").attr('checked', true);
                // Abre a modal de edição
                $modal.modal('show');
            });
        });
    },

    // Altera o valor do input para deletar o campo adicional
    onClickButtonDeleteCampo: () => {
        $("button[data-action='delete']").on('click', (event) => {
            event.preventDefault();
            // Recupera o input de delete
            let $input = $("input[name='delete_campo']");
            // Coloca o valor no input para deletar
            $input.val("on");
            // Envia o formulario
            $input.parents("form").trigger('submit');
        });
    },

    // Click para reativar o campo adicional
    onClickButtonActivateField: () => {
        $("[data-action='activate-field']").on('click', (event) => {
            event.preventDefault();
            let $this = $(event.currentTarget),
                url = $this.attr('href'),
                campo_id = $this.attr('data-id');
            // Loader
            App.loader.show();
            // Recupera os dados do campo
            axios.post(url, {campo_id: campo_id}).then((response) => {
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

    // Click no botao para editar a foto do servico
    onClickButtonEditPhoto: () => {
        $("[data-action='edit-photo']").on('click', (event) => {
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
                let $modal = $("#edit-photo");
                // Limpa o formulario
                $modal.find("form").trigger('reset');
                $modal.find("input[type='checkbox']").attr('checked', false);
                // Coloca os valores nos campos
                $modal.find("#foto_modal").attr('src', response['FotoLarge']);
                $modal.find("#legenda_foto").val(response['legenda']);
                $modal.find("input[name='foto_id']").val(response['id']);
                $modal.find("[data-principal='" + response['tipo'] + "']").attr('checked', true);
                // Abre a modal de edição
                $modal.modal('show');
            });
        });
    },

    // Altera o valor do input para deletar a foto
    onClickButtonDeleteFoto: () => {
        $("button[data-action='delete_photo']").on('click', (event) => {
            event.preventDefault();
            // Recupera o input de delete
            let $input = $("input[name='delete_foto']");
            // Coloca o valor no input para deletar
            $input.val("on");
            // Envia o formulario
            $input.parents("form").trigger('submit');
        });
    },

    // Altera o valor do input para deletar a foto
    onClickButtonEditCategoria: () => {
        $("[data-action='edit-categoria']").on('click', (event) => {
            event.preventDefault();
            let $this = $(event.currentTarget),
                url = $this.attr('href'),
                $modal = $("#edit-categoria");
            // Loader
            App.loader.show();
            // Recupera os dados do campo
            axios.get(url).then((response) => {
                // Recupera os dados
                response = response.data;
                let categoria = response['categorias'][0];
                let $select = $modal.find("select#tipo_categoria");
                $select.find("option").attr('selected', false);
                $select.find(`option[value="${categoria['pivot']['padrao']}"]`).attr('selected', true);
                // Atualiza o boostrap select
                $select.selectpicker('refresh');

                // Limpa o formulario
                $modal.find("form").trigger('reset');
                // Coloca os valores nos campos
                $modal.find("#categoria_edit").val(categoria['nome'] + " - " + categoria['destino']['nome']);
                $modal.find("input[name='categoria_id']").val(categoria['id']);
                // Cria o html das secoes da categoria
                let html = "";
                // secoes da catoria
                let secoes = categoria['secoes_categoria'],
                    length = secoes.length,
                    secoes_servico = response['secoes_categoria'],
                    length_servico = secoes_servico.length;
                // Percorre as secoes
                for(let i = 0; i < length; i++) {
                    let secao = secoes[i], checked = "";
                    // Verifica se a secao esta ligada ao servico
                    for(let j = 0; j < length_servico; j++) {
                        if(secoes_servico[j]['id'] === secao['id']) {
                            checked = "checked";
                            break;
                        }
                    }
                    html +=
                        "<div class=\"col-xl-6 mt-2 mb-2\">\n" +
                            "<div class=\"styled-checkbox\">\n" +
                                "<input type=\"checkbox\" name=\"secoes[]\" id=\"secao-edit-" + secao['id'] + "\" " + checked + " value=\"" + secao['id'] + "\">\n" +
                                "<label for=\"secao-edit-" + secao['id'] + "\">" + secao['nome'] +"</label>\n" +
                            "</div>\n" +
                        "</div>";
                }
                // Coloca as secoes na lista
                $(".list-edit-secoes-categoria").html(html);
                // Loader
                App.loader.hide();
                // Abre a modal de edição
                $modal.modal('show');
            });
        });
    },

    // Altera o valor do input para deletar a categoria
    onClickButtonDeleteCategoria: () => {
        $("button[data-action='delete_category']").on('click', (event) => {
            event.preventDefault();
            // Recupera o input de delete
            let $input = $("input[name='delete_category']");
            // Coloca o valor no input para deletar
            $input.val("on");
            // Envia o formulario
            $input.parents("form").trigger('submit');
        });
    },

    // Click no botao para editar o markup
    onClickButtonEditMarkup: () => {
        $("[data-action='edit-markup']").on('click', (event) => {
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
                let $modal = $("#edit-markup");
                // Limpa o formulario
                $modal.find("form").trigger('reset');
                // Coloca os valores nos campos
                $modal.find("#markup_atual").val(response['markup']);
                $modal.find("#new_markup").val(response['markup']);
                $modal.find("input[name='variacao_id']").val(response['id']);
                // Abre a modal de edição
                $modal.modal('show');
            });
        });
    },

    // Click no botao para editar a foto do servico
    onClickButtonEditVariation: () => {
        $("[data-action='edit-variation']").on('click', (event) => {
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
                let $modal = $("#edit-variation");
                let $select_tipo_variacao = $("select#edit_tipo_variacao");
                // Limpa o formulario
                $modal.find("form").trigger('reset');
                $modal.find("input[type='radio']").attr('checked', false);
                $select_tipo_variacao.find("option").removeAttr('selected');
                // Coloca os valores nos campos
                $modal.find("#edit_variation_name").val(response['nome']);
                $modal.find("#edit_variation_description").val(response['descricao']);
                $modal.find("#edit_variation_percent").val(response['percentual']);
                $modal.find("#edit_variation_markup").val(response['markup']);
                $modal.find("[data-bloqueio='" + response['consome_bloqueio'] + "']").attr('checked', true);
                $modal.find("input[name='variacao_id']").val(response['id']);
                $select_tipo_variacao.find("option[data-tipo-variacao='" +  response['destaque'] + "']").attr('selected', true);
                $select_tipo_variacao.selectpicker('refresh');
                // Abre a modal de edição
                $modal.modal('show');
            });
        });
    },

    // Altera o valor do input para deletar a variacao do servico
    onClickButtonDeleteVariacao: () => {
        $("button[data-action='delete_variacao']").on('click', (event) => {
            event.preventDefault();
            // Recupera o input de delete
            let $input = $("input[name='delete_variacao']");
            // Coloca o valor no input para deletar
            $input.val("on");
            // Envia o formulario
            $input.parents("form").trigger('submit');
        });
    },

    // Click para reativar a variacao
    onClickButtonActivateVariation: () => {
        $("[data-action='activate-variation']").on('click', (event) => {
            event.preventDefault();
            let $this = $(event.currentTarget),
                url = $this.attr('href'),
                variacao_id = $this.attr('data-id');
            // Loader
            App.loader.show();
            // Post para reativar a variacao
            axios.post(url, {variacao_id: variacao_id}).then((response) => {
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

    // Envio do formulario de cadastro das variacoes servico
    onSubmitFormCadastroVariacaoServico: () => {
        $("form[name='new_variation_service']").on('submit', (event) => {
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
                        swal(response['data']['action']['title'], response['data']['action']['message'], "success").then(() => {
                            // Pergunta se quer adicionar mais um campo
                            swal({
                                title: "Cadastrar mais uma variação?",
                                text: "Você gostaria de cadastrar mais uma variação ou ir para próximo passo?",
                                icon: "info",
                                dangerMode: true,
                                buttons: ["Próximo passo", "Nova variação"],
                            }).then((confirm) => {
                                // Caso queira adicionar mais um campo
                                if (confirm) {
                                    // Reseta o formulario de cadastro
                                    let $form_cadastro = $("form[name='new_variation_service']");
                                    let $select_tipo_variacao = $("select#new_tipo_variacao");
                                    $form_cadastro.trigger('reset');
                                    $form_cadastro.find("input[type='radio']").attr('checked', false);
                                    $select_tipo_variacao.find("option").removeAttr('selected');
                                    $select_tipo_variacao.selectpicker('refresh');
                                } else {
                                    // Fecha a modal
                                    $("#add-variation").modal('hide');
                                    // Next tab
                                    ServicoCtrl.nextTab(5);
                                }
                            });
                        });
                    } else {
                        swal(response['data']['action']['title'], response['data']['action']['message'], "error");
                    }
                }).catch((error) => {App.captureErrorAjax(error)});
            }
        });
    },

    /**
     * Proxima tab do cadastro
     * @param number_tab
     */
    nextTab: (number_tab) => {
        let $tabs = $("ul#tab_servico li a"),
            length = $tabs.length;
        // Percorre as tabs
        for(let i = 0; i < length; i++) {
            let $tab = $($tabs[i]);
            $tab.removeClass('active').addClass('disabled');
            if((i + 1) === number_tab) {
                $tab.removeClass("disabled").click();
            }
        }
    },

    // Pesquisa de icones
    onChangeInputSearchIcon() {
        $("[data-action='search']").on('keyup', (e) => {
            let $this = $(e.currentTarget);
            let $target = $($this.attr('data-target'));
            let $buttons = $target.find("button"), length = $buttons.length;
            let filter = Plugins.removerAcentos($this.val().trim().toLowerCase());
            // Percorre os servicos
            for(let i = 0; i < length; i++) {
                let $button = $($buttons[i]),
                    nome_icone = Plugins.removerAcentos($button.find("p").text().toLowerCase());
                // Verifica se o nome do servico esta no texto digitado
                if (nome_icone.indexOf(filter) > -1) {
                    $button.parent().removeClass("d-none");
                } else {
                    $button.parent().addClass("d-none");
                }
            }
        });
    },

    /**
     * Carrega os icones
     *
     * @param url
     * @param $modal
     * @param onSelect
     * @param selectedIcon
     */
    loadIconesTag(url, $modal, tipo_tag, onSelect, selectedIcon = null) {
        window.App.loader.show();

        if(tipo_tag == 'INTERNA') {
            url = $("#link-icones2").text();
        }

        axios.get(url).then((response) => {
            let icons = response['data'], length = icons.length;

            let html = "";

            if(ServicoCtrl.isTagExterna(tipo_tag)) {
                for(let i = 0; i < length; i++) {
                    let icon = icons[i];
                    let selected = (selectedIcon === icon['name']) ? "active" : "";
                    html += `<div class="col-2"><button class="${selected}" data-action="clickIcon" data-icon="${ icon['name'] }"><i class="jam jam-${ icon['name'] }"></i><p class="mb-0 text-truncate">${ icon['name'] }</p></button></div>`;
                }
            } else {

                for(let categoria_index in icons) {
                    let categoria = icons[categoria_index];

                    for(let icone_index in categoria.icons) {
                        let icone = categoria.icons[icone_index];

                        html += `<div class="col-2"><button class="" data-action="clickIcon" data-icon="${categoria.prefix} fa-${icone}"><i class="${categoria.prefix} fa-${icone} icone"></i><p class="mb-0 text-truncate">${icone}</p></button></div>`;

                    }
                }
            }

            // Coloca os icones na lista
            $modal.find(".list-icones-tag").html(html);
            $("[data-action='clickIcon']").on('click', (event) => {
                event.preventDefault();
                let $this = $(event.currentTarget);
                onSelect($this.attr('data-icon'), $this);
            });

            // Loader e abre a modal
            window.App.loader.hide();
            $modal.modal('show');

            // Caso tiver icone selecionado (edicao)
            if(selectedIcon) {
                setTimeout(() => {
                    let $lista_icones = $modal.find(".list-icones-tag");
                    let $button = $lista_icones.find("button.active").parent();
                    ServicoCtrl.scrollIfNeeded($button[0], $lista_icones[0]);
                }, 500);
            }
        });
    },

    /**
     * Scroll icones
     *
     * @param element
     * @param container
     */
    scrollIfNeeded(element, container) {
        if (element.offsetTop < container.scrollTop) {
            container.scrollTop = element.offsetTop;
        } else {
            const offsetBottom = element.offsetTop + element.offsetHeight;
            const scrollBottom = container.scrollTop + container.offsetHeight;
            if (offsetBottom > scrollBottom) {
                container.scrollTop = offsetBottom - container.offsetHeight;
            }
        }
    },

    // Click no botao para adicionar uma nova tag
    onClickButtonOpenModalTag() {
        $("[data-action='newTag']").on('click', (event) => {
            event.preventDefault();
            let $this = $(event.currentTarget);
            let $modal = $($this.attr('data-target'));
            let $tipo_tag = $this[0].getAttribute('tipo-tag');

            if(ServicoCtrl.isTagExterna($tipo_tag)){
                ServicoCtrl.hideCampoTituloModal();
                ServicoCtrl.setFormTagToExterna();
            } else {
                ServicoCtrl.showCampoTituloModal();
                ServicoCtrl.setFormTagToInterna();
            }

            let onClose = () => {
                $modal.find(".list-icones-tag").html("");
                $modal.off('hide.bs.modal');
            };

            $modal.on('hide.bs.modal', onClose);
            ServicoCtrl.loadIconesTag($this.attr('data-route'), $modal, $tipo_tag,function (icon, $button) {
                // Desmarca os outros icones
                $modal.find(".list-icones-tag button.active").not($button).removeClass('active');
                // Marca o icone
                $button.addClass('active');
                // Salva o nome do icone
                $modal.find("[name='icone']").val(icon);
            });
        })
    },

    // Click no botao para editar uma nova tag
    onClickButtonEditTag() {
        $("[data-action='editTag']").on('click', (event) => {
            event.preventDefault();
            let $this = $(event.currentTarget);
            let $modal = $($this.attr('data-target'));
            let $tipo_tag = $this[0].getAttribute('tipo-tag');

            let onClose = () => {
                $modal.find(".list-icones-tag").html("");
                $modal.off('hide.bs.modal');
            };

            if(ServicoCtrl.isTagExterna($tipo_tag)){
                ServicoCtrl.hideCampoTituloModal();
                ServicoCtrl.setFormTagToExterna();
            } else {
                ServicoCtrl.showCampoTituloModal();
                ServicoCtrl.setFormTagToInterna();
            }

            $modal.on('hide.bs.modal', onClose);
            window.App.loader.show();
            // Recupera os detalhes datag
            axios.get($this.attr('data-tag')).then((response) => {
                // Coloca os valores no input
                $modal.find("[name='titulo']").val(response['data']['titulo']);
                $modal.find("[name='descricao']").val(response['data']['descricao']);
                $modal.find("[name='ordem']").val(response['data']['ordem']);
                $modal.find("[name='icone']").val(response['data']['icone']);
                $modal.find("[name='tag_id']").val(response['data']['id']);
                // Recupera os icones
                ServicoCtrl.loadIconesTag($this.attr('data-route'), $modal, $tipo_tag,function (icon, $button) {
                    // Desmarca os outros icones
                    $modal.find(".list-icones-tag button.active").not($button).removeClass('active');
                    // Marca o icone
                    $button.addClass('active');

                    // Salva o nome do icone
                    $modal.find("[name='icone']").val(icon);
                }, response['data']['icone']);
            }).catch(window.App.captureErrorAjax);
        })
    },

    // Altera o valor do input para deletar a tag serviço
    onClickButtonDeleteTag: () => {
        $("button[data-action='delete-tag']").on('click', (event) => {
            event.preventDefault();
            // Recupera o input de delete
            let $input = $("input[name='delete_tag']");
            // Coloca o valor no input para deletar
            $input.val("on");
            // Envia o formulario
            $input.parents("form").trigger('submit');
        });
    },

    // Faz aparecer o campo de titulo na modal de icones
    // Serve para casos de cadastro de tag interna que possui titulo
    // Ativa o campo do form tmb.
    showCampoTituloModal: () => {
        $("#campo-titulo").css('display', 'block');
        $("#titulo_tag_new").attr('disabled', false);
        $("#campo-titulo2").css('display', 'block');
        $("#titulo_tag_new2").attr('disabled', false);
    },

    // Faz desaparecer o campo de titulo na modal de icones
    // Serve para casos de cadastro de tag externa que não possui titulo
    // Desativa o campo do form tmb, para não travar na validação
    hideCampoTituloModal: () => {
        $("#campo-titulo").css('display', 'none');
        $("#titulo_tag_new").attr('disabled', 'true');
        $("#campo-titulo2").css('display', 'none');
        $("#titulo_tag_new2").attr('disabled', 'true');
    },

    // Muda o link do form, para casos onde mudamos de cadastro ou edição de tag internas para externas
    // Neste caso muda para Interna
    setFormTagToInterna: () => {
        let link_cadastrar = $("#link-cadastrar2").text();
        let link_atualizar = $("#link-atualizar2").text();
        $("#form-tag").attr('action', link_cadastrar);
        $("#form-tag2").attr('action', link_atualizar);
    },

    // Muda o link do form, para casos onde mudamos de cadastro ou edição de tag internas para externas
    // Neste caso muda para Externa
    setFormTagToExterna: () => {
        let link_cadastrar = $("#link-cadastrar").text();
        let link_atualizar = $("#link-atualizar").text();
        $("#form-tag").attr('action', link_cadastrar);
        $("#form-tag2").attr('action', link_atualizar);
    },

    // Retorna se a tag a ser editada ou criada é interna ou externa
    // Esta informação esta registrada no HTML
    isTagExterna: (tipo_tag) => {
        if(tipo_tag == 'EXTERNA') {
            return true;
        }
        return false;
    }
};
