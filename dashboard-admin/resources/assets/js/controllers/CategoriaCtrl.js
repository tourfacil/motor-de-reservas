let CategoriaCtrl = {

    _urlCategoria: "",

    _dadosCategoria: "",

    /** Inicialização do controller */
    init: () => {

        // Envio do formulario de descrição da categoria
        CategoriaCtrl.onSubmitFormDescricaoCategoria();

        // Envio do formulário de cadastro das secao categoria
        CategoriaCtrl.onSubmitFormCadastroSecaoCategoria();

        // Envio do formulário das fotos da categoria
        CategoriaCtrl.onSubmitFormFotosCategoria();

        // Click no botão e no input para procurar o arquivo (Mesmo que o DestinoCtrl)
        DestinoCtrl.onClickSearchFile();

        // Carrega o preview da imagem
        CategoriaCtrl.onChangePhoto();

        // Click para editar secao categoria
        CategoriaCtrl.openModalEditSecaoCategoria();

        // Click no botão de excluir secao
        CategoriaCtrl.onClickButtonDeleteSecao();
    },

    /** Envio do formulario de descricao da categoria */
    onSubmitFormDescricaoCategoria: () => {
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
                        // Salva os dados da categoria
                        CategoriaCtrl._urlCategoria = response['data']['view'];
                        CategoriaCtrl._dadosCategoria = response['data']['categoria'];
                        // Coloca o valor do ID em todos os campos
                        $("input.callback_categoria_id").val(response['data']['categoria']['id']);
                        // Mensagem de sucesso
                        swal("Categoria cadastrada", "A categoria " + response['data']['categoria']['nome'] + " foi cadastrada com sucesso!", "success").then(() => {
                            // Next tab
                            CategoriaCtrl.nextTab(2);
                        });
                    } else {
                        swal("Categoria não cadastrada", "Não foi possível cadastrar a categoria, tente novamente!", "error");
                    }
                }).catch((error) => {App.captureErrorAjax(error)});
            }
        })
    },

    /** Envio do formulario de cadastro da seção categoria */
    onSubmitFormCadastroSecaoCategoria: () => {
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
                    if(response['data']['action']['result']) {
                        // Fecha a modal
                        $('#new-secao-categoria').modal('hide');
                        // Mensagem de sucesso
                        swal(response['data']['action']['title'], response['data']['action']['message'], "success").then(() => {
                            // Next tab
                            CategoriaCtrl.nextTab(3);
                        });
                    } else {
                        swal(response['data']['action']['title'], response['data']['action']['message'], "error");
                    }
                }).catch((error) => {App.captureErrorAjax(error)});
            }
        })
    },

    /** Envio do formulario das foto da categoria */
    onSubmitFormFotosCategoria: () => {
        $("form[name='fotos_categoria']").on('submit', (event) => {
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
                        swal("Foto cadastrada", "As fotos da categoria foram cadastradas com sucesso!", "success").then(() => {
                            // Mostra o loader
                            App.loader.show();
                            // Redireciona para a página da categoria
                            window.location.href = CategoriaCtrl._urlCategoria;
                        });
                    } else {
                        swal("Foto não cadastrada", response['data']['message'], "error");
                    }
                }).catch((error) => {App.captureErrorAjax(error)});
            }
        })
    },

    /** Preview da imagem */
    onChangePhoto: () => {
        $("input[type='file']").on('change', (event) => {
            // When cancel select image
            if(event.currentTarget.files.length === 0) return;
            // Onde a imagem vai ficar
            let $this = $(event.currentTarget),
                $place = $($this.attr('data-placeholder')),
                $name = $($this.attr('data-name')),
                $error = $name.siblings(".invalid-feedback");

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
                $name.val(event.currentTarget.files[0].name);
                // Load preview image
                reader.onload = function(){
                    $place.addClass('loaded');
                    $place.css('background-image', 'url(' + reader.result + ')');
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
        let $tabs = $("ul#tab_categoria li a"),
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

    /** Abre a modal para a edição da seção */
    openModalEditSecaoCategoria: () => {
        $(".actions a[data-action='edit-secao']").on('click', (event) => {
            event.preventDefault();
            let $this = $(event.currentTarget),
                url = $this.attr('href');
            // Mosta o loader
            App.loader.show();
            // Recupera os dados da secao
            axios.get(url).then((response) => {
                // Tira o loader
                App.loader.hide();
                // Verifica a resposta
                if(typeof response['data']['id'] !== "undefined") {
                    // Salva o ID da secao
                    $("input[name='secao_id']").val(response['data']['id']);
                    // Coloca o nome da secao
                    $("input#nome_edit_secao").val(response['data']['nome']);
                    // Remove a opção de deletar
                    $("input[name='delete_secao']").val("off");
                    // Abre a modal
                    $('#edit-secao-categoria').modal('show');
                } else {
                    swal("Seção não encontrada", "Não foi possível localizar a seção, tente novamente!", "error").then(() => {
                        // Abre a modal
                        $('#edit-secao-categoria').modal('hide');
                    });
                }
            });
        });
    },

    /** Altera o valor do input para excluir a seção e envia o formulario */
    onClickButtonDeleteSecao: () => {
        $("button[data-action='delete']").on('click', (event) => {
            event.preventDefault();
            // Recupera o input de delete
            let $input = $("input[name='delete_secao']");
            // Coloca o valor no input para deletar
            $input.val("on");
            // Envia o formulario
            $input.parents("form").trigger('submit');
        });
    }
};
