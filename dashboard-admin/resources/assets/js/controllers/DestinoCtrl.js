let DestinoCtrl = {

    _urlDestino: "",

    _dadosDestino: "",

    _secaoServicos: [],

    _secaoAtual: [],

    // Inicialização do controller
    init: () => {
        // Envio do 1 formulário de cadastro
        DestinoCtrl.onSubmitFormDescricaoDestino();

        // Envio do 2 formulário de cadastro
        DestinoCtrl.onSubmitFormFotoDestino();

        // Click no botão e no input para procurar o arquivo
        DestinoCtrl.onClickSearchFile();

        // Carrega o preview da imagem
        DestinoCtrl.onChangePhoto();

        // Click para editar a secao da home destino
        DestinoCtrl.onClickButtonEditSecao();

        // Click no botao para reativar a secao
        DestinoCtrl.onClickButtonAtivarSecao();

        // Pesquisa do servico
        DestinoCtrl.onKeyUpInputSearch();

        // Click no botão para adicionar nova secao (MODAL)
        DestinoCtrl.onClickButtonNovaSecao();

        // Click no botao para desativar a secao
        DestinoCtrl.onClickButtonRemoveSecao();

        // Change checkbox na lista dos servicos disponiveis
        DestinoCtrl.onChangeCheckboxServicos();

        // Click no icone para remover o servico da lista dos selecionados
        DestinoCtrl.onClickButtonRemoveServico();
    },

    /** Envio do formulario de descricao do destino */
    onSubmitFormDescricaoDestino: () => {
        $("form[name='descricao']").on('submit', (event) => {
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
                        DestinoCtrl._urlDestino = response['data']['view'];
                        DestinoCtrl._dadosDestino = response['data']['destino'];
                        // Coloca o valor do ID em todos os campos
                        $("input.callback_destino_id").val(response['data']['destino']['id']);
                        // Mensagem de sucesso
                        swal("Destino cadastrado", "O destino " + response['data']['destino']['nome'] + " foi cadastrado com sucesso!", "success").then(() => {
                            // Next tab
                            DestinoCtrl.nextTab(2);
                        });
                    } else {
                        swal("Destino não cadastrado", "Não foi possível cadastrar o destino, tente novamente!", "error");
                    }
                }).catch((error) => {App.captureErrorAjax(error)});
            }
        })
    },

    /** Envio do formulario da foto do destino */
    onSubmitFormFotoDestino: () => {
        $("form[name='foto-destino']").on('submit', (event) => {
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
                        swal("Foto cadastrada", "A foto do destino foi cadastrada com sucesso!", "success").then(() => {
                            // Remove o loader
                            App.loader.show();
                            // Redireciona para a página do destino
                            window.location.href = DestinoCtrl._urlDestino;
                        });
                    } else {
                        swal("Foto não cadastrada", response['data']['message'], "error");
                    }
                }).catch((error) => {App.captureErrorAjax(error)});
            }
        })
    },

    /** Trigger click input file  */
    onClickSearchFile: () => {
        $(".open-search-file").on('click', (event) => {
            let $this = $(event.currentTarget),
                $target = $($this.attr('data-callback'));
            $target.click();
        });
    },

    /** Preview da imagem */
    onChangePhoto: () => {
        $("input[name='foto']").on('change', (event) => {
            // When cancel select image
            if(event.currentTarget.files.length === 0) return;
            // Onde a imagem vai ficar
            let $place = $("input[name='placeholder']"), $error = $place.siblings(".invalid-feedback");

            // Valid extensions image
            let is_valid = App.validExtensionFile(event.currentTarget.files[0].name, [
                ".jpg", ".jpeg", ".png"
            ]);

            // Recupera a foto enviada
            let file = event.currentTarget.files[0];

            // Valida o tamanho da imagem
            if(! App.validMaxSize(file)) {
                swal("A imagem excede os 3MB", "A imagem " + file.name + " é maior que 3MB, diminua o peso dela e tente novamente!", "error");
                return false;
            }

            // If is valid image load to preview
            if(is_valid) {
                let reader = new FileReader();
                // Save file to form
                $place.val(event.currentTarget.files[0].name);
                // Load preview image
                reader.onload = function(){
                    let output = $('#place-foto');
                    output.addClass('loaded');
                    output.css('background-image', 'url(' + reader.result + ')');
                };
                reader.readAsDataURL(event.currentTarget.files[0]);
            } else {
                $place.addClass('invalid');
                $error.html("Não foi possível carregar o arquivo, tente novamente!");
            }
        });
    },

    /**
     * Proxima tab do cadastro
     * @param number_tab
     */
    nextTab: (number_tab) => {
        let $tabs = $("ul#tab_destino li a"),
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

    // Filtro para buscar o servico
    onKeyUpInputSearch: () => {
        $("input.search-on-list").on('keyup', (e) => {
            let $this = $(e.currentTarget);
            let $target = $($this.attr('data-target'));
            let $lis = $target.find("li"), length = $lis.length;
            let filter = Plugins.removerAcentos($this.val().trim().toLowerCase());
            // Percorre os servicos
            for(let i = 0; i < length; i++) {
                let $li = $($lis[i]),
                    nome_servico = Plugins.removerAcentos($li.find("[data-nome]").text().toLowerCase());
                    nome_servico += " " + Plugins.removerAcentos($li.find("[data-fornecedor]").text().toLowerCase());
                // Verifica se o nome do servico esta no texto digitado
                if (nome_servico.indexOf(filter) > -1) {
                    $li.removeClass("d-none");
                } else {
                    $li.addClass("d-none");
                }
            }
        });
    },

    /** Ordenação dos serviços selecionados */
    onDragListSelecionados: (target) => {
        let dragSrcEl = null;

        // Verifica qual lista é para colcoar os binds
        let $lista = (target === "cadastro")
            ? $("#cadastro-selecionados") : $("#editar-selecionados");

        // Recupera as li da lista
        let $lis = $lista.find("li");

        function handleDragStart(e) {
            dragSrcEl = this;
            e.dataTransfer.effectAllowed = 'move';
            e.dataTransfer.setData('text/html', this.outerHTML);
            this.classList.add('dragElem');
        }

        function handleDragOver(e) {
            if (e.preventDefault) e.preventDefault();
            this.classList.add('over');
            e.dataTransfer.dropEffect = 'move';
            return false;
        }

        function handleDragLeave(e) {
            this.classList.remove('over');
        }

        function handleDrop(e) {
            if (e.stopPropagation) e.stopPropagation();

            // Don't do anything if dropping the same column we're dragging.
            if (dragSrcEl !== this) {
                this.parentNode.removeChild(dragSrcEl);
                let dropHTML = e.dataTransfer.getData('text/html');
                this.insertAdjacentHTML('beforebegin',dropHTML);
                let dropElem = this.previousSibling;
                addDnDHandlers(dropElem);
            }
            this.classList.remove('over');

            // Atualiza a ordem dos servicos
            DestinoCtrl.reordernarSelecionados($lista);

            return false;
        }

        function handleDragEnd(e) {
            $(e.currentTarget).removeClass('over dragElem');
        }

        function addDnDHandlers(elem) {
            elem.addEventListener('dragstart', handleDragStart, false);
            elem.addEventListener('dragover', handleDragOver, false);
            elem.addEventListener('dragleave', handleDragLeave, false);
            elem.addEventListener('drop', handleDrop, false);
            elem.addEventListener('dragend', handleDragEnd, false);
        }

        // Coloca os binds na lista
        let length = $lis.length;
        for (let i = 0; i < length; i++) {
            addDnDHandlers($lis[i]);
        }
    },

    /** Reordena os servicos selecionados */
    reordernarSelecionados: ($lista) => {
        $lista = $lista.find("li");
        let nova_ordem = [];
        // Coloca os binds na lista
        let length = $lista.length;
        for (let i = 0; i < length; i++) {
            let $li = $($lista[i]);
            let servico_id = parseInt($li.find("input[data-servico]").val());
            let ordem = i+1;
            nova_ordem.push({
                id: servico_id,
                nome: $li.find("[data-nome]").text(),
                fornecedor: $li.find("[data-fornecedor]").text(),
            });
            // Atualiza a ordem no servico no HTML
            $li.find("[data-ordem]").html(ordem);
            $li.find("input[data-posicao]").val(ordem);
            $li.removeClass('dragElem');
        }

        // Salva a nova ordem dos servicos
        DestinoCtrl._secaoServicos = nova_ordem;
    },

    /** OnChange checkbox dos servicos */
    onChangeCheckboxServicos: () => {
        $(".grid-servicos input[type='checkbox']").on('change', (event) => {
            let $this = $(event.currentTarget),
                $li = $this.parents('li'),
                target_action = $this.attr('data-action'),
                servico_id = parseInt($this.val());

            // Caso seja marcado
            if($this.prop('checked')) {

                // Caso tente selecionar mais que 10 serviços
                if(DestinoCtrl._secaoServicos.length === 10) {
                    swal("Máximo 10 serviços", "Você pode selecionar no máximo 10 serviços por seção!", "error");
                    $this.prop('checked', false);
                    return false;
                }

                // Puxa o servico selecionado para a lista
                DestinoCtrl._secaoServicos.push({
                    id: servico_id,
                    nome: $li.find("[data-nome]").text(),
                    fornecedor: $li.find("[data-fornecedor]").text(),
                });

            } else {
                // Remove o serviço da lista
                DestinoCtrl.removeServicoSelecionado(servico_id);
            }

            // Atualiza a lista dos servicos selecionados
            DestinoCtrl.updateListServicoSelecionados(target_action);
        })
    },

    /**
     * Atualiza a lista dos servicos selecionados
     *
     * @param target
     */
    updateListServicoSelecionados: (target) => {
        // Monta o html para colocar na lista
        let length = DestinoCtrl._secaoServicos.length, html = "";
        // Lista onde fica os serviços selecionados
        let $ul = (target === "cadastro")
            ? $("#cadastro-selecionados") : $("#editar-selecionados");

        for(let i = 0; i < length; i++) {
            let servico = DestinoCtrl._secaoServicos[i];
            let ordem = i+1;
            html +=
                `<li draggable="true">
                    <div class="row">
                        <div class="col-10 text-truncate">
                            <strong data-ordem>${ordem}</strong> -
                            <strong data-nome class="text-truncate">${servico['nome']}</strong>
                            <small data-fornecedor class="text-truncate">${servico['fornecedor']}</small>
                        </div>
                        <div class="col-2 d-flex align-items-center no-padding-left">
                            <button type="button" class="btn-remove-servico" title="Remover serviço" data-action="${target}" data-servico="${servico['id']}">
                                <i class="la la-trash text-danger"></i>
                            </button>
                        </div>
                    </div>
                    <input type="hidden" data-servico name="servicos[${ordem}][id]" value="${servico['id']}">
                    <input type="hidden" data-posicao name="servicos[${ordem}][ordem]" value="${ordem}">
                </li>`;
        }

        // Coloca o html da lista dos serviços selecionados
        $ul.html(html);

        // Ordenação da lista dos serviços selecionados
        DestinoCtrl.onDragListSelecionados(target);
    },

    /**
     * Remove um servico da lista dos selecionados
     * @param servico_id
     */
    removeServicoSelecionado: (servico_id) => {
        // Remove o serviço da lista
        let length = DestinoCtrl._secaoServicos.length;
        for(let i = 0; i < length; i++) {
            if(DestinoCtrl._secaoServicos[i]['id'] === servico_id) {
                DestinoCtrl._secaoServicos.splice(i, 1);
                break;
            }
        }
    },

    /** Click para remover o serviço da lista dos selecionados */
    onClickButtonRemoveServico: () => {
        $(document).on("click", "button.btn-remove-servico", (event) => {
            event.preventDefault();
            let $this = $(event.currentTarget),
                target_action = $this.attr('data-action'),
                servico_id = parseInt($this.attr('data-servico'));
            // Remove o serviço da lista
            DestinoCtrl.removeServicoSelecionado(servico_id);
            // Atualiza a lista dos servicos selecionados
            DestinoCtrl.updateListServicoSelecionados(target_action);
            // Desmarca o checbox
            $(`[data-action='${target_action}'][value='${servico_id}']`).prop('checked', false);
        })
    },

    /** Click no botão para adicionar nova secao (MODAL) */
    onClickButtonNovaSecao: () => {
        $("button[data-target='#new-secao-destino']").on('click', (event) => {
            // Reseta os itens selecionados
            DestinoCtrl._secaoServicos = [];
            $(`input[data-action='cadastro']`).prop('checked', false);
        });
    },

    /** Click para editar a secao da home destino */
    onClickButtonEditSecao: () => {
        let $modal = $("#edit-secao-destino"); // Modal de edicao
        let $select_tipo = $("select#tipo_edit_secao"); // Select do tipo secao
        $("[data-action='edit-secao']").on('click', (event) => {
            event.preventDefault();
            let $this = $(event.currentTarget);
            // Reseta os itens selecionados
            DestinoCtrl._secaoServicos = [];
            $(`input[data-action='editar']`).prop('checked', false);
            // Mosta o loader
            App.loader.show();
            // Recupera as informacoes da data
            axios.get($this.attr('href')).then((response) => {
                response = response['data'];
                DestinoCtrl._secaoAtual = response;
                // Reseta o formulario
                $modal.find("form").trigger('reset');
                $select_tipo.find("option").removeAttr('selected');
                // Coloca os valores no form
                $modal.find("input#titulo_edit_secao").val(response['titulo']);
                $modal.find("input#ordem_edit_secao").val(response['ordem']);
                $modal.find("input#descricao_edit_secao").val(response['descricao']);
                $modal.find("input[name='home_destino_id']").val(response['id']);
                // Seleciona o tipo da secao
                $select_tipo.find("option[value='" + response['tipo'] +"']").attr('selected', true);
                $select_tipo.selectpicker('refresh');
                // Monta o array com os servicos selecionados na secao
                let servicos = response['servicos'], legnth = servicos.length;
                for(let i = 0; i < legnth; i++) {
                    let servico = servicos[i];
                    DestinoCtrl._secaoServicos.push({
                        id: parseInt(servico['id']),
                        nome: servico['nome'],
                        fornecedor: servico['fornecedor']['nome_fantasia'],
                    });
                    // Marca o checkbox do serviço
                    $(`input[data-action='editar'][value=${servico['id']}]`).prop('checked', true);
                }
                // Atualiza a lista dos serviços selecionados
                DestinoCtrl.updateListServicoSelecionados('editar');
                // Retira o loader
                App.loader.hide();
                // Abre a modal
                $modal.modal();
            });
        });
    },

    /** Click no botao para desativar a secao */
    onClickButtonRemoveSecao: () => {
        $("button[data-action='delete']").on('click', (event) => {
            event.preventDefault();
            let $this = $(event.currentTarget),
                url = $this.attr('data-route');
            // Loader
            App.loader.show();
            // DELETE para desativar a secao
            axios.post(url, {home_destino_id: DestinoCtrl._secaoAtual['id'], _method: "DELETE"}).then((response) => {
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

    /** Click no botao para reativar a secao */
    onClickButtonAtivarSecao: () => {
        $("a[data-action='ativar-secao']").on('click', (event) => {
            event.preventDefault();
            let $this = $(event.currentTarget);
            // Loader
            App.loader.show();
            // PUT para reativar a secao
            axios.put($this.attr('href'), {home_destino_id: $this.attr('data-id')}).then((response) => {
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
    }
};
