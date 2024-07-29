let ColaboradoresCtrl = {

    // Inicialização do controller
    init: () => {

        // Click no botão para editar o colaborador
        ColaboradoresCtrl.onClickButtonEditColaborador();

        // Click para desativar o colaborador
        ColaboradoresCtrl.onClickButtonDesativarColaborador();

        // Click para reativar o colaborador
        ColaboradoresCtrl.onClickButtonAtivarColaborador();
    },

    // Click para editar o colaborador
    onClickButtonEditColaborador: () => {
        $("[data-action='edit-colaborador']").on('click', (event) => {
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
                let $modal = $("#edit-colaborador");
                let $select_nivel = $("select#edit_level");
                // Limpa o formulario
                $modal.find("form").trigger('reset');
                $modal.find("input[type='checkbox']").attr('checked', false);
                $select_nivel.find("option").removeAttr('selected');
                // Coloca os valores nos campos
                $modal.find("#edit_name").val(response['name']);
                $modal.find("#edit_email").val(response['email']);
                $modal.find("#afiliado_id").val(response['afiliado_id']);
                $modal.find("input[name='colaborador_id']").val(response['id']);
                // Seleciona o nivel de acesso
                $select_nivel.find("[data-nivel='" + response['level'] +"']").attr('selected', true);
                $select_nivel.selectpicker('refresh');

                // Abre a modal de edição
                $modal.modal('show');
            });
        });
    },

    /**
     * Altera o valor do input para desativar o colaborador e envia o formulario
     */
    onClickButtonDesativarColaborador: () => {
        $("button[data-action='desativar']").on('click', (event) => {
            event.preventDefault();
            // Recupera o input de desativar
            let $input = $("input[name='desativar_colaborador']");
            // Coloca o valor no input para desativar
            $input.val("on");
            // Envia o formulario
            $input.parents("form").trigger('submit');
        });
    },

    // Click para reativar o colaborador
    onClickButtonAtivarColaborador: () => {
        $("[data-action='activate-colaborador']").on('click', (event) => {
            event.preventDefault();
            let $this = $(event.currentTarget),
                url = $this.attr('href'), colaborador_id = $this.attr('data-id');
            // Loader
            App.loader.show();
            // Post para reativar o usuario
            axios.put(url, {colaborador: colaborador_id}).then((response) => {
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
};
