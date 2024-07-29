let PedidoCtrl = {

    // Informações que servem para a finalização
    finalizacao: {
        data: [],
        acompanhantes: [],
        campos_adicionais_lista: [],
    },

    init: () => {

        // Click no botao para imprimir o voucher
        PedidoCtrl.onClickButtonPrintVoucher();

        // Carrega o Sweet Alert
        Helpers.loadSweetAlert();

        PedidoCtrl.onClickBotaoFinalizar();

        // Captura o click do botão de finalizar os acompanhantes
        PedidoCtrl.onClickBotaoFinalizarAcomps();

        // Captura o click do botão de finalizar os campos
        PedidoCtrl.onClickBotaoFinalizarCampos();

        PedidoCtrl.onClickBotaoAvaliarServico();

        PedidoCtrl.onFormSubmitAvaliacao();

        window.DadosFactory.loadAcompanhantes();

        AvaliacaoStarsCtrl.onHoverStars();

        AvaliacaoStarsCtrl.onClickStar();
    },

    /** Click no botao para imprimir o voucher */
    onClickButtonPrintVoucher: () => {
        $("button[data-action='print_voucher']").on('click', (event) => {
            event.preventDefault();
            let $this = $(event.currentTarget);
            window.open($this.attr('data-url'), '_blank');
        })
    },

    /** Scroll para os servios adquiridos */
    scrollToServico: () => {
        if(window.location.href.indexOf("#imprimir") > 0) {
            $(window).scrollTop($('#servicos-adquiridos').offset().top - 50);
        }
    },

    // Captura o click do botão de abrir a finalização
    onClickBotaoFinalizar: () => {
        $('.finalizar-reserva-botao').on('click', (event) => {
            PedidoCtrl.modalAcomps(event);
        });
    },

    // Captura o click do botão de finalizar os acompanhantes
    onClickBotaoFinalizarAcomps: () => {
        $('#salvar-acompanhantes').on('click', (event) => {
            PedidoCtrl.salvarAcomps();
        });
    },

    // Captura o click do botão de finalizar os campos
    onClickBotaoFinalizarCampos: () => {
        $('#salvar-campos').on('click', (event) => {
            PedidoCtrl.salvarCampos();
        });
    },

    onClickBotaoAvaliarServico: () => {
        $('#avaliar-servico').on('click', (event) => {
            PedidoCtrl.modalAvaliacaoServico();
        });
    },

    modalAvaliacaoServico: () => {

        let id_reserva = event.currentTarget.getAttribute('reserva_id');
        let url_post = event.currentTarget.getAttribute('url_post');

        let modal = $('#modal-avaliacao');
        let form = $('#form-avaliacao');
        let campo_reserva_id = $('#avaliacao_reserva_id');

        form.attr('action', url_post)
        campo_reserva_id.val(id_reserva);

        modal.jqmodal();
    },

    onFormSubmitAvaliacao: () => {
        let form = $('#form-avaliacao');
        form.submit((event) => {

            let nota = $('#star-contador').val();
            let avaliacao = $('#avaliacao').val();

            if(PedidoCtrl.validarFormAvaliacao(nota, avaliacao) == false) {
                event.preventDefault();
                return swal({
                    title: 'Preencha todos os campos',
                    text: 'Selecione a nota e preencha a avaliação com pelo menos 5 caracteres.',
                    icon: 'warning'
                });
            }
        });
    },

    validarFormAvaliacao: (nota, avaliacao) => {
        if(nota == '') {
            return false;
        }

        if(avaliacao.length <= 4) {
            return false;
        }

        return true;
    },

    modalAcomps: (event) => {

        let id_reserva = event.currentTarget.getAttribute('id_reserva');
        let url_base = $('#link-finalizacao').text();
        let link = `${url_base}?reserva_id=${id_reserva}`;

        let response = axios.get(link).then((response) => {
            let data = response.data;
            PedidoCtrl.finalizacao.data = data;
            PedidoCtrl.abrirModalAcomps(data.quantidades);
        });
    },


    // Abre a modal de preenchimento dos acompanhantes
    abrirModalAcomps: (quantidades) => {

        if(PedidoCtrl.finalizacao.data.servico.info_clientes != "SOLICITA_INFO_CLIENTES") {
            PedidoCtrl.changeAcompToCampoToFinish();
            return;
        }

        let modal_acompanhantes = $("#modal-acompanhantes");
        let contador = 0;
        let html = "";

        for(let i = 0; i < quantidades.length; i++) {

            let quantidade = quantidades[i];

            for(let j = 0; j < quantidade.quantidade; j++) {

                html +=
                    `
                        <div class="list-acompanhantes mb-2">
                            <h6 class="font-weight-medium h5" style="font-weight: bold;">${contador + 1}° Acompanhante - ${quantidade.variacao_servico.nome}</h6>
                            <div class="row">
                                <div class="form-group nome_acompanhante col-sm-12 col-lg-4">
                                    <label for="nome_${i}" style="color:#2c304d;">Nome completo*</label>
                                    <input type="text" class="form-control" id="nome_${contador}" name="acompanhantes[${contador}][nome]" data-auto-capitalize data-nome-completo="true" required data-min="5" data-required placeholder="Nome e sobrenome" autocomplete="off" title="Nome completo" data-list="nomes_acom" data-callback="nome">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="form-group doc_acompanhante col-sm-12 col-md-6 col-lg-4">
                                    <label for="documento_${i}" style="color:#2c304d;">N° do documento*</label>
                                    <input type="text" class="form-control" id="documento_${contador}" name="acompanhantes[${contador}][documento]" required data-min="5" data-required placeholder="Número CPF ou RG" title="Documento" autocomplete="off" data-list="doc_acom" data-callback="documento">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="form-group nasc_acompanhante col-sm-12 col-md-6 col-lg-4">
                                    <label for="nascimento_${i}" style="color:#2c304d;">Nascimento*</label>
                                    <input type="tel" class="form-control vanillaMask" id="nascimento_${contador}" name="acompanhantes[${contador}][nascimento]" required data-min="9" data-required placeholder="DD/MM/AAAA" data-mask="date" title="Nascimento" autocomplete="off" maxlength="10" autocomplete="off" data-list="data_acom" data-callback="nascimento">
                                    <div class="invalid-feedback"></div>
                                    <input type="hidden" id="variacao_${contador}" name="acompanhantes[${contador}][variacao_servico_id]" value="${quantidade.variacao_servico_id}">
                                    <input type="hidden" id="reserva_${contador}" name="acompanhantes[${contador}][reserva_pedido_id]" value="${quantidade.reserva_pedido_id}">
                                </div>
                            </div>
                        </div>
                    `;

                contador++;
                modal_acompanhantes.find(".fields-input").html(html);
                Helpers.vanillaMask();
            }
        }

        modal_acompanhantes.jqmodal();
    },

    /* Pega os dados dos inputs e salva os acompanhantes dentro da variavel global acompanhantes */
    salvarAcomps: () => {

        /* Variavel com as quantidades (Variações) */
        let quantidades = PedidoCtrl.finalizacao.data.quantidades;

        /* Array para guardar os clientes */
        let acomps = [];

        /* Contador para salvar em qual quantidade (Variação) estamos */
        let contador = 0;

        /* Passa todas as quantidades (Variações) */
        for(let i = 0; i < quantidades.length; i++) {

            /* Atribui a quantidade (Variação atual a uma variavel) */
            let quantidade = quantidades[i];

            /* Repete o número de pessoas que tem com aquela quantidade (Variação) */
            for(let j = 0; j < quantidade.quantidade; j++) {

                /* Cria um acomp vazio para guardar as informações */
                let acomp = {};

                /* Salva o nome do acomp */
                acomp.nome = $(`#nome_${contador}`).val();

                /* Verifica se o nome é valido ou retorna */
                if(PedidoCtrl.isNomeOuDocumentoAcompValido(acomp.nome) == false) {
                    return PedidoCtrl.alerta("Nome inválido", `O nome do acompanhante ${contador + 1} deve conter pelo menos 5 caracteres.`);
                }

                /* Salva o documento do acomp */
                acomp.documento = $(`#documento_${contador}`).val();

                /* Verifica se o documento é valido ou retorna */
                if(PedidoCtrl.isNomeOuDocumentoAcompValido(acomp.documento) == false) {
                    return PedidoCtrl.alerta("Documento inválido", `O documento do acompanhante ${contador + 1} deve conter pelo menos 5 caracteres.`);
                }

                /* Salva a data de nascimento do acomp */
                acomp.nascimento = $(`#nascimento_${contador}`).val();

                /* Verifica se a data de nascimento é valida ou retorna */
                if(PedidoCtrl.isNascimentoAcompValido(acomp.nascimento) == false) {
                    return PedidoCtrl.alerta("Data de nascimento inválida", `A data de nascimento do acompanhante ${contador + 1} deve ser válida.`);
                }

                /* Salva a variação_id */
                acomp.variacao_servico_id = $(`#variacao_${contador}`).val();

                /* Salva a reserva_id */
                acomp.reserva_pedido_id = $(`#reserva_${contador}`).val();

                /* Guarda o acomp no array */
                acomps.push(acomp);

                /* Aumenta o contador */
                contador++;
            }
        }

        /* Salva na variavel global, acompanhantes todos os acomps registrados na função */
        PedidoCtrl.finalizacao.acompanhantes = acomps;

        /* Salva os dados dos acompanhantes no preenchimento automatico */
        window.DadosFactory.saveDadosAcompanhantes(acomps);

        /* Verifica se tem campos adicionais para ser preenchidos
        Caso tenha: Abre a modal de preenchimento
        Caso não tenha: Submete a requisição */
        PedidoCtrl.changeAcompToCampoToFinish();
    },


    /* Verifica se é necessário preencher os campos adicionais, ou se pode submeter */
    changeAcompToCampoToFinish: () => {

        /* Salva os campos adicionais do serviço em uma variavel */
        let campos_adicionais = PedidoCtrl.finalizacao.data.servico.campos_adicionais_ativos;

        /* Verifica se tem campos
        Caso tenha: Abre a modal para preencher
        Caso não tenha: Subteme o formulario */
        if(campos_adicionais.length > 0) {
            PedidoCtrl.abrirModalCampo();
        } else {
            PedidoCtrl.submeterInformacoes();
        }
    },

    // Abre a modal de preenchimento dos campos adicionais
    abrirModalCampo: () => {

        let campos_adicionais = PedidoCtrl.finalizacao.data.servico.campos_adicionais_ativos;

        let $modal_adicionais = $("#modal-campo-adicional");
        let html = "";

        for(let i = 0; i < campos_adicionais.length; i++) {

            let campo = campos_adicionais[i];
            let classGrid = (i === 0) ? "col-sm-12" : "col-sm-12 col-md-6";
            let required = "", info_required = "";
            if(campo['obrigatorio'] === "SIM") {
                required = "data-required required"; info_required = "*";
            }

            html +=
                `<div class="form-group ${classGrid}">
                        <label for="adicional_${campo['id']}" style="color:#2c304d;">${campo['campo']}${info_required}</label>
                        <input type="text" class="form-control campos" numero="${i}" id="adicional_${campo['id']}" name="adicionais[${i}][informacao]" data-min="1" ${required} placeholder="${campo['placeholder']}" title="${campo['campo']}">
                        <span class="invalid-feedback"></span>
                        <input type="hidden" id="ad-${i}" name="adicionais[${i}][campo_adicional_servico_id]" value="${campo['id']}">
                        <input type="hidden" id="adr-${i}" name="adicionais[${i}][campo_adicional_servico_id]" value="${PedidoCtrl.finalizacao.data.reserva.id}">
                    </div>`;

            $modal_adicionais.find(".fields-input").html(html);
            $modal_adicionais.jqmodal();
        }
    },

    /* Pega o conteudo dos inputs dos campos adicionais e salva para a req */
    salvarCampos: () => {

        /* Busca todos os inputs de campos-adicionais */
        let campos_adicionais = $(".campos");

        /* Array para guadar os campos que serão retirados dos inputs */
        let campos = [];

        /* Roda todos os camos */
        for(let i = 0; i < campos_adicionais.length; i++) {

            /* Objeto de campo vazio para colocar os dados */
            let campo = {};

            /* Atribui o campo para uma variavel */
            let campo_adicional = campos_adicionais.eq(i);

            /* Atribui o campo com o campo_adicional_id a uma variavel */
            let campo_adicional_id = $(`#ad-${campo_adicional.attr('numero')}`);

            /* Atribui o campo reserva_servico_id a uma variavel */
            let campo_adicional_reserva_id = $(`#adr-${campo_adicional.attr('numero')}`);

            /* Pega os valor da variavel de informacao de campo no objeto campo */
            campo.informacao = campo_adicional.val();

            /* Verifica se o campo adicional é valido ou retorna */
            if(PedidoCtrl.isCampoAdicionalValido(campo.informacao) == false) {
                return PedidoCtrl.alerta("Campo adicional inválido", `O(s) campo(s) adicionais devem conter pelo menos 4 caracteres.`);
            }

            /* Pega as informações de campo_id e reserva_id e salva no objeto campo */
            campo.campo_adicional_servico_id = campo_adicional_id.val();
            campo.reserva_pedido_id = campo_adicional_reserva_id.val();

            /* Coloca o objeto campo dentro da lista de campos */
            campos.push(campo);

            /* Coloca a lista de campos na variavel global campos_adicionais_lista */
            PedidoCtrl.finalizacao.campos_adicionais_lista = campos;
        }

        /* Chama a função para submeter as informações */
        PedidoCtrl.submeterInformacoes();
    },

    /* Serve para subtemer e registrar os dados informados pelo cliente */
    submeterInformacoes: () => {

        /* Abre a modal de carregamento */
        Helpers.loader.show()

        /* Monta um objeto de requisião com as informações coletadas */
        let req = {
            acompanhantes: PedidoCtrl.finalizacao.acompanhantes,
            campos_adicionais: PedidoCtrl.finalizacao.campos_adicionais_lista,
        };

        let link_informacao_finalizacao = $('#link-finalizacao-store').text();

        /* Envia o post para registrar as informações e quando chega a resposta ele atualiza a página */
        axios.post(link_informacao_finalizacao, req).then((response) => {
            window.location.reload();
        });
    },

    isNomeOuDocumentoAcompValido: (nome) => {
        if(nome.length > 4) {
            return true;
        }
        return false;
    },

    isNascimentoAcompValido: (nascimento) => {
        if(nascimento.length == 10 && Helpers.isValidDate(nascimento)) {
            return true;
        }
        return false;
    },

    isCampoAdicionalValido: (campo_adicional) => {
        if(campo_adicional.length > 3) {
            return true;
        }
        return false;
    },

    alerta: (titulo, texto) => {
        swal(titulo, texto, 'warning');
    },

};
