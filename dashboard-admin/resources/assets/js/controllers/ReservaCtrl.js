let ReservaCtrl = {

    // Dados da data
    _disponibilidade: [],

    // Datas para o calendario
    _events: [],

    // Quantidade atual da reserva para edicao
    _quantideAtualReserva: 0,

    // Informações que servem para a finalização
    finalizacao: {
        data: [],
        acompanhantes: [],
        campos_adicionais_lista: [],
    },

    // Inicialização do controller
    init: (page) => {

        // Plugin do calendario
        Plugins.loadLibDateRanger();

        // Ação para remover um acompanhante da reserva
        ReservaCtrl.onClickButtonRemoveAcompanhante();

        // Ação para alterar a data de utilização da reserva
        ReservaCtrl.onClickButtonAlterarAgenda();

        // Ação para editar a quantidade adquirida
        ReservaCtrl.onClickButtonEditQuantidade();

        // Desabilita as ações na reserva de acordo com o status
        ReservaCtrl.disableActionsReserva(page);

        // Click no botao para imprimir o voucher da reserva
        ReservaCtrl.onClickButtonPrint();

        ReservaCtrl.onClickBotaoFinalizar();

        ReservaCtrl.onClickBotaoFinalizarAcomps();

        ReservaCtrl.onClickBotaoFinalizarCampos();

        ReservaCtrl.forcarIntegracao();
    },

    // Click no botao para imprimir o voucher da reserva
    onClickButtonPrint: () => {
        $("button[data-action='print_voucher']").on('click', (event) => {
            event.preventDefault();
            let $this = $(event.currentTarget);
            window.open($this.attr('data-url'), '_blank');
        });
    },

    /**
     * Desabilita as ações na reserva de acordo com o status
     * @param page
     */
    disableActionsReserva: (page) => {
        // Recupera o paramentro passado pela página
        if(page.getAttribute('data-disabled') === "1") {
            let $fields = $(".disable-action");
            $fields.addClass('disabled').attr('disabled', true);
        }
    },

    // Ação para editar a quantidade adquirida
    onClickButtonEditQuantidade: () => {
        $("button[data-action='edit_quantidade']").on('click', (event) => {
            event.preventDefault();
            let $this = $(event.currentTarget);
            // Loader
            App.loader.show();
            // POST para deletar
            let result = axios.get($this.attr('data-route'));
            result.then((response) => {
                // Modal para editar a quantidade
                let $modal = $("#edit-quantidade-reserva");
                let html_select = "<option selected disabled>Selecione a nova quantidade</option>",
                    quantidade_atual = parseInt(response['data']['quantidade']);
                // Verifica se tem mais de uma quantidade na reserva
                if(quantidade_atual >= 1) {
                    // Salva a quantidade atual da reserva
                    ReservaCtrl._quantideAtualReserva = quantidade_atual;
                    // Coloca a quantidade atual
                    $modal.find("#quantidade_atual").val(`${quantidade_atual}x ${response['data']['variacao_servico']['nome']}`);
                    // Monta as opções da nova quantidade
                    for(let i = (quantidade_atual - 1); i >= 0; i--) {
                        if(i > 0) {
                            html_select += `<option value="${i}">${i}x ${response['data']['variacao_servico']['nome']}</option>`;
                        } else {
                            html_select += `<option value="${i}">Remover ${response['data']['variacao_servico']['nome']}</option>`;
                        }
                    }
                    // Coloca as opções no select
                    $modal.find("#nova_quantidade").html(html_select);
                    $modal.find("input[name='quantidade_reserva_id']").val(response['data']['id']);
                    // Esconde o loader
                    App.loader.hide();
                    // Abre a modal de edição
                    $modal.modal('show');
                } else {
                    // Esconde o loader
                    App.loader.hide();
                    // Aviso sobre a quantidade
                    swal("Não é possível alterar", "A variação atual possui somente 1 (uma) quantidade", "error");
                }
            });
        });
    },

    // Ação para remover um acompanhante da reserva
    onClickButtonRemoveAcompanhante: () => {
        $("button[data-action='delete_acompanhante']").on('click', (event) => {
            event.preventDefault();
            let $this = $(event.currentTarget);
            let $nome_acompanhante = $("#nome_acop_" + $this.attr('data-index'));
            // Confirmação de remover o acompanhante
            swal({
                title: "Deseja remover este acompanhante?",
                text: $nome_acompanhante.val() + " será removido da reserva! Não será possível desfazer essa ação.",
                icon: "warning",
                dangerMode: true,
                buttons: ["Fechar", "Sim, remover"],
            }).then((confirm) => {
                if(confirm) {
                    // Loader
                    App.loader.show();
                    // Dados para POST
                    let postData = {acompanhante_id: $this.attr('data-id'), _method: "DELETE"};
                    // POST para deletar
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
        });
    },

    /** Click no botao para alterar a data de utilizacao da reserva */
    onClickButtonAlterarAgenda: () => {
        $("button[data-action='alterar_agenda']").on('click', (event) => {
            event.preventDefault();
            let $this = $(event.currentTarget);
            let $modal = $("#edit-agenda-reserva");
            // Loader
            App.loader.show();
            // Recupera as datas da reserva
            let result = axios.get($this.attr('data-url'));
            result.then((response) => {
                // Salva as datas para o calendario
                ReservaCtrl._events = response['data']['events'];
                ReservaCtrl._disponibilidade = response['data']['disponibilidade'];
                // Coloca os valores nos campos
                $modal.find("#data_atual").val(response['data']['data_atual']);
                $modal.find("#qtd_pessoas").val(response['data']['quantidade'] + " pessoas");
                // Calendario com as datas disponiveis
                let $field_calendar = $modal.find("#new_date");
                // reseta o campo do calendario
                $field_calendar.val("");
                // Configura o calendario
                $field_calendar.daterangepicker({
                    minDate: ReservaCtrl.dataToIE(response['data']['events'][0]['date']),
                    singleDatePicker: true,
                    autoUpdateInput: false,
                    isCustomDate: ReservaCtrl.filterDateCalendar,
                    locale: {
                        format: 'DD/MM/YYYY',
                        daysOfWeek: ["Do", "2º", "3º", "4º", "5º", "6º", "Sá"],
                        monthNames: ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"],
                    }
                });
                // Evento ao selecionar a data
                $field_calendar.on('apply.daterangepicker', ReservaCtrl.onSelectDateCalendar);
                // Esconde o loader
                App.loader.hide();
                // Abre a modal de edição
                $modal.modal('show');
            })
        })
    },

    /**
     * Evento chamado ao selecionar uma data no calendario
     *
     * @param ev
     * @param picker
     */
    onSelectDateCalendar: (ev, picker) => {
        let id_nova_data = "";
        // Coloca a data no input
        $(ev.currentTarget).val(picker.startDate.format("DD/MM/YYYY"));
        // Data selecionada no calendario
        let data_selecionada = picker.startDate.format("YYYY-MM-DD");
        let length_disponibilidade = ReservaCtrl._disponibilidade.length;
        // Recupera o id da data selecionada
        for(let i = 0; i < length_disponibilidade; i++) {
            let dados_data = ReservaCtrl._disponibilidade[i];
            // Caso seja a mesma data recupera o ID da data
            if(dados_data['data'] === data_selecionada) {
                id_nova_data = dados_data['data_servico_id'];
            }
        }

        // Coloca o id da nova data no campo
        $("input[name='data_agenda_id']").val(id_nova_data);
    },

    /**
     * Habiltia o desabilita a data no calendario
     *
     * @param date
     * @returns {string}
     */
    filterDateCalendar: (date) => {
        let length = ReservaCtrl._events.length;
        let data_calendario = date.format("YYYY-MM-DD") + " 00:00:00";

        for(let i = 0; i < length; i++) {
            let data = ReservaCtrl._events[i]['date'];
            if(data === data_calendario) return "";
        }

        return "disabled";
    },

    /** BUG IE, Safari, iPhone, iPad e etc */
    dataToIE: (date) => {
        let data = date.split(' ');
        data = data[0].split('-');
        return new Date(data[0], (data[1] - 1), data[2], 0, 0, 0, 0);
    },

    // Captura o click do botão de abrir a finalização
    onClickBotaoFinalizar: () => {
        $('#finalizar-reserva-botao').on('click', (event) => {
            ReservaCtrl.modalAcomps();
        });
    },

    // Captura o click do botão de finalizar os acompanhantes
    onClickBotaoFinalizarAcomps: () => {
        $('#salvar-acompanhantes').on('click', (event) => {
            ReservaCtrl.salvarAcomps();
        });
    },

    // Captura o click do botão de finalizar os campos
    onClickBotaoFinalizarCampos: () => {
        $('#salvar-campos').on('click', (event) => {
            ReservaCtrl.salvarCampos();
        });
    },

    modalAcomps: (event) => {

        let id_reserva = $('#finalizar-reserva-botao').attr('reserva_id');
        let url_base = $('#link-finalizacao').text();
        let link = `${url_base}?reserva_id=${id_reserva}`;

        let response = axios.get(link).then((response) => {
            let data = response.data;
            ReservaCtrl.finalizacao.data = data;
            ReservaCtrl.abrirModalAcomps(data.quantidades);
        });
    },

    // Abre a modal de preenchimento dos acompanhantes
    abrirModalAcomps: (quantidades) => {

        if(ReservaCtrl.finalizacao.data.servico.info_clientes != "SOLICITA_INFO_CLIENTES") {
            ReservaCtrl.changeAcompToCampoToFinish();
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
                App.vanillaMask();
            }
        }

        modal_acompanhantes.jqmodal();
    },

    /* Pega os dados dos inputs e salva os acompanhantes dentro da variavel global acompanhantes */
    salvarAcomps: () => {

        /* Variavel com as quantidades (Variações) */
        let quantidades = ReservaCtrl.finalizacao.data.quantidades;

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
                if(ReservaCtrl.isNomeOuDocumentoAcompValido(acomp.nome) == false) {
                    return ReservaCtrl.alerta("Nome inválido", `O nome do acompanhante ${contador + 1} deve conter pelo menos 5 caracteres.`);
                }

                /* Salva o documento do acomp */
                acomp.documento = $(`#documento_${contador}`).val();

                /* Verifica se o documento é valido ou retorna */
                if(ReservaCtrl.isNomeOuDocumentoAcompValido(acomp.documento) == false) {
                    return ReservaCtrl.alerta("Documento inválido", `O documento do acompanhante ${contador + 1} deve conter pelo menos 5 caracteres.`);
                }

                /* Salva a data de nascimento do acomp */
                acomp.nascimento = $(`#nascimento_${contador}`).val();

                /* Verifica se a data de nascimento é valida ou retorna */
                if(ReservaCtrl.isNascimentoAcompValido(acomp.nascimento) == false) {
                    return ReservaCtrl.alerta("Data de nascimento inválida", `A data de nascimento do acompanhante ${contador + 1} deve ser válida.`);
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
        ReservaCtrl.finalizacao.acompanhantes = acomps;

        /* Salva os dados dos acompanhantes no preenchimento automatico */
        // window.DadosFactory.saveDadosAcompanhantes(acompanhantes);

        /* Verifica se tem campos adicionais para ser preenchidos
        Caso tenha: Abre a modal de preenchimento
        Caso não tenha: Submete a requisição */
        ReservaCtrl.changeAcompToCampoToFinish();
    },

    /* Verifica se é necessário preencher os campos adicionais, ou se pode submeter */
    changeAcompToCampoToFinish: () => {

        /* Salva os campos adicionais do serviço em uma variavel */
        let campos_adicionais = ReservaCtrl.finalizacao.data.servico.campos_adicionais_ativos;

        /* Verifica se tem campos
        Caso tenha: Abre a modal para preencher
        Caso não tenha: Subteme o formulario */
        if(campos_adicionais.length > 0) {
            ReservaCtrl.abrirModalCampo();
        } else {
            ReservaCtrl.submeterInformacoes();
        }
    },

    // Abre a modal de preenchimento dos campos adicionais
    abrirModalCampo: () => {

        let campos_adicionais = ReservaCtrl.finalizacao.data.servico.campos_adicionais_ativos;

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
                        <input type="hidden" id="adr-${i}" name="adicionais[${i}][campo_adicional_servico_id]" value="${ReservaCtrl.finalizacao.data.reserva.id}">
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
            if(ReservaCtrl.isCampoAdicionalValido(campo.informacao) == false) {
                return ReservaCtrl.alerta("Campo adicional inválido", `O(s) campo(s) adicionais devem conter pelo menos 4 caracteres.`);
            }

            /* Pega as informações de campo_id e reserva_id e salva no objeto campo */
            campo.campo_adicional_servico_id = campo_adicional_id.val();
            campo.reserva_pedido_id = campo_adicional_reserva_id.val();

            /* Coloca o objeto campo dentro da lista de campos */
            campos.push(campo);

            /* Coloca a lista de campos na variavel global campos_adicionais_lista */
            ReservaCtrl.finalizacao.campos_adicionais_lista = campos;
        }

        /* Chama a função para submeter as informações */
        ReservaCtrl.submeterInformacoes();
    },

    /* Serve para subtemer e registrar os dados informados pelo cliente */
    submeterInformacoes: () => {

        /* Abre a modal de carregamento */
        App.loader.show();

        /* Monta um objeto de requisião com as informações coletadas */
        let req = {
            acompanhantes: ReservaCtrl.finalizacao.acompanhantes,
            campos_adicionais: ReservaCtrl.finalizacao.campos_adicionais_lista,
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
        if(nascimento.length == 10 && App.isValidDate(nascimento)) {
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

    forcarIntegracao: () => {
        $('#forcar-integracao-btn').on('click', () => {

            let reserva_id = $('#forcar-integracao-btn').attr('reserva_id');
            let url = $('#forcar-integracao-btn').attr('data-url');

            swal({
                title: "Forçar integração",
                text: "Tem certeza que deseja forçar a integração ? Haverá cobrança do parque!",
                icon: "warning",
                dangerMode: true,
                buttons: ["Não", "Sim"],
            }).then((confirm) => {
                if(confirm) {

                    // Caso o usuário clicar em sim, mostra o loader
                    App.loader.show();

                    let payload = {
                        reserva_id: reserva_id
                    };
                    
                    axios.post(url, payload).then((response) => {

                        let data = response.data;

                        if(data.status) {
                            App.loader.hide();
                            swal({
                                title: 'Reserva integrada',
                                text: data.info,
                                icon: 'success'
                            }).then(() => {
                                window.location.reload();
                            });

                        } else {
                            App.loader.hide();
                            swal({
                                title: 'Erro na integração',
                                text: data.info,
                                icon: 'error'
                            })
                        }
                    })
                }
            })
        });
    },
};
