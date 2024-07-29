let BannersCtrl = {

    // URL para POST com a ação de (desativar, ativar e excluir o banner)
    _urlChangeStatus: "",

    // ID do banner que está sendo enditado
    _bannerId: "",

    // Inicializacao do controller
    init(page) {

        // Recupera os dados do banner que está sendo editado
        if(typeof page.getAttribute('data-banner') !== "undefined") {
            this._urlChangeStatus = page.getAttribute('data-route-status');
            this._bannerId = page.getAttribute('data-banner');
        }

        // Evento que altera o ID do destino na URL
        this.onChangeSelectDestinoIndex();

        // Evento ao trocar o destino para carregar os servicos
        this.onChangeSelectDestinoLoadServicos();

        // Preview do banner
        this.onChangePhoto();

        // Click nos botoes de acoes do banner (desativar, ativar e excluir o banner)
        this.onClickButtonActionsBanner();

        // Click no botão e no input para procurar o arquivo (Mesmo que o DestinoCtrl)
        DestinoCtrl.onClickSearchFile();
    },

    // Evento que altera o ID do destino na URL
    onChangeSelectDestinoIndex() {
        $("select#destino_filter").on('change', (event) => {
            let $this = $(event.currentTarget),
                destino = $this.val(),
                route = $this.attr('data-route');
            // Atualiza a URL
            App.loader.show();
            window.location.href = `${route}/${destino}`;
        })
    },

    // Evento ao trocar o destino para carregar os servicos
    onChangeSelectDestinoLoadServicos() {
        $("select#destino_id").on('change', (event) => {
            let $this = $(event.currentTarget);
            // Atualiza a lista dos servicos
            BannersCtrl.updateSelectServicos($this.val());
        }).trigger('change');
    },

    // Atualiza o select com os servicos
    updateSelectServicos(destino) {
        App.loader.show();
        let $select_servicos = $("select#servico_id"), html = "";
        // Recupera os servicos
        let result = $.getJSON(`${$select_servicos.attr('data-route')}/${destino}`);
        result.done((response) => {
            // Monta as opções com os servicos
            response.forEach((servico) => {
                html += `<option value="${servico.id}">${servico.nome}</option>`
            });
            // Atualiza os servicos do destino
            $select_servicos.html(html);
            $select_servicos.selectpicker('destroy');
            $select_servicos.selectpicker({
                liveSearch: true,
                liveSearchPlaceholder: "Pesquisar serviço"
            });
            App.loader.hide();
        });
    },

    // Preview do banner
    onChangePhoto() {
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

    // Click nos botoes de acoes do banner (desativar, ativar e excluir o banner)
    onClickButtonActionsBanner() {
        $("[data-buttons-action] button").on('click', (event) => {
            event.preventDefault();
            let $this = $(event.currentTarget);
            BannersCtrl.sendRequestAlteracaoBanner($this.attr('data-action'));
        });
    },

    // Envia o request para fazer a alteracao no banner (desativar, ativar ou excluir)
    sendRequestAlteracaoBanner(action) {
        // Loader
        App.loader.show();
        // Efetua o PUT para fazer a alteracao
        axios.put(this._urlChangeStatus, {banner_id: this._bannerId, action: action})
            .then(App.captureSuccessAjax)
            .catch(App.captureErrorAjax);
    }
};
