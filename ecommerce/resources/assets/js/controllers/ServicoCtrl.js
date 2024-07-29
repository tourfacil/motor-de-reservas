let ServicoCtrl = {

    // Dados do servico
    _dadosServico: {},

    // Response da pesquisa de disponibilidade
    _response: [],

    // Datas disponiveis no calendario
    _events: [],

    // Payload que será enviado para o carrinho
    _payload: [],

    // Informacoes da data
    _agenda: [],

    // Caixa de aviso na modal
    _avisoModal: {},

    _sendToCart: false,

    // Inicializacao do controller
    init: (page) => {

        // Salva a referencia da caixa de aviso na modal
        ServicoCtrl._avisoModal = $("#comprar .info-error");

        // Recupera as informacoes do servico
        ServicoCtrl.loadInfoServico(page);

        // Slider das fotos
        ServicoCtrl.setupGallerySlider();

        // Slider dos servicos relacionados
        ServicoCtrl.setupSliderRelacionados();

        // Click no botao de comprar
        ServicoCtrl.onClickButtonComprar();

        // Click no botao de adicionar pessoa
        ServicoCtrl.onClickAddPessoa();

        // Click no botao de remover pessoa
        ServicoCtrl.onClickRemoverPessoa();

        // Bind para as modais
        ServicoCtrl.onCloseModals();

        // Click no botao para adicionar ao carrinho
        ServicoCtrl.onClickButtonAdicionarCarrinho();

        // Envio do formulário de acompanhantes
        ServicoCtrl.onSubmitFormPersons();

        // Envio do formulário de dados adicionais
        ServicoCtrl.onSubmitFormAdditional();

        // Click no botao voltar do navegador
        ServicoCtrl.onClickBackButtonNavegador();

        // Configura o horario de atendimento e botoes mostrar mais
        ServicoCtrl.setupWrapper();

        // Click nos botoes de mostrar mais
        ServicoCtrl.onClickButtonMostrarMais();

        // Click no botao para ver a galeria
        ServicoCtrl.onClickButtonOpenGallery();

        ServicoCtrl.verMaisAvaliacao();

        ServicoCtrl.verMenosAvaliacao();

        ServicoCtrl.carregarMaisAvaliacoes();
    },

    /** Click no botao para ver a galeria */
    onClickButtonOpenGallery: () => {
        $('button[data-action="openGallery"]').on('click', (event) => {
            event.preventDefault();
            // Clica na primeira fotop
            $(".owl-gallery a:first").click();
        });
    },

    /** Configura o horario de atendimento e botoes mostrar mais */
    setupWrapper: () => {

        // Dias de funcionamento
        let $lists_attendance = $(".service-details .atendimento + ul"),
            length_attendance = $lists_attendance.length;
        for (let i = 0; i < length_attendance; i++) {
            let $list = $($lists_attendance[i]);
            let $lis = $list.find("li"), lis_length = $lis.length;
            for (let j = 0; j < lis_length; j++) {
                let $li = $($lis[j]);
                let $attendance = $li.text().split("|");
                if (typeof $attendance[1] !== "undefined") {
                    let second = $attendance[1].trim();
                    let classSecond = (second.toLowerCase() === "fechado") ? "text-closed" : "text-hour";
                    let html = `${$attendance[0].trim()} <span class="${classSecond}">${second}</span>`;
                    $li.html(html);
                }
            }
        }

        // Blocos de mostrar mais
        let $read_more = $(".service-details .ler-mais"),
            length_read = $read_more.length, byCopy = ["P", "UL"];
        for (let i = 0; i < length_read; i++) {
            let $read_block = $($read_more[i]);
            let $current = $read_block;
            let $next = $current.next();
            let currentTag = $next.get(0).tagName;

            // ID do bloco
            let id = parseInt(window.Helpers.getRandomArbitrary(5, 50));

            // Div para o conteudo
            let $read = $(`<div id='read_${id}' class='readmore'/>`);

            // Recupera os proximos p para colocar dentro do readme
            while (jQuery.inArray(currentTag, byCopy) !== -1) {
                $current = $next;
                $next = $next.next();
                currentTag = $current.get(0).tagName;
                if (jQuery.inArray(currentTag, byCopy) !== -1) {
                    $read.append($current.remove());
                }
            }

            // Adiciona o bloco de ler mais
            $read_block.after($read);

            // Caso o tamanho do texto fique mais ou igual o limite colocar o botao
            if ($read.height() >= parseInt($read.css("max-height"))) {
                $read.addClass("has-overlay");
                let $button = $(`<button data-expand="read_${id}" title="Mostrar mais" class='btn btn-link btn-readme'>Mostrar mais +</button>`);
                $read.after($button);
            }
        }
    },

    /** Click nos botoes de mostrar mais */
    onClickButtonMostrarMais: () => {
        $(document).on("click", "button[data-expand].btn-readme", (event) => {
            let $this = $(event.currentTarget);
            let $target = $(`#${$this.attr('data-expand')}`);
            let offset = window.pageYOffset;
            if (typeof $target !== "undefined") {
                if ($target.hasClass('readmore')) {
                    $target.removeClass('readmore');
                    $this.html("Mostrar menos").attr('title', "Mostrar menos");
                } else {
                    $target.addClass('readmore');
                    $this.html("Mostrar mais +").attr('title', "Mostrar mais");
                }
                // Prevent scroll
                window.scroll(0, offset);
            }
        })
    },

    /** Loader */
    loaderDatas: {
        show: (text = 'Carregando datas...', error = false) => {
            let $modal = $("#loader-datas"),
                $loader = $modal.find(".spinner-border"),
                $icon = $modal.find(".iconify.text-danger");
            if (error) {
                $icon.removeClass('d-none');
                $loader.addClass('d-none');
            } else {
                $icon.addClass('d-none');
                $loader.removeClass('d-none');
            }
            $modal.find("p").html(text);
            $modal.jqmodal({ showClose: error });
        }
    },

    /** Configura o slider das fotos do servico */
    setupGallerySlider: () => {
        $('.owl-gallery').owlCarousel({
            loop: false,
            lazyLoad: true,
            margin: 0,
            dots: false,
            nav: true,
            rewind: true,
            responsiveClass: true,
            lazyLoadEager: 2,
            navText: ['<i class="iconify" data-icon="jam:chevron-left"></i>', '<i class="iconify" data-icon="jam:chevron-right"></i>'],
            responsive: {
                0: { items: 1, },
                600: { items: 2, },
                848: { items: 3, },
                1000: { items: 3, }
            }
        })
    },

    /** Configura o slider dos servicos relacionados */
    setupSliderRelacionados: () => {
        $('.owl-relacionados').owlCarousel(window.App.sliderConfig.withButtons)
    },

    /** Recupera as informações do servico */
    loadInfoServico: (page) => {
        let result = $.getJSON(page.getAttribute('data-servico'));
        result.done((response) => {
            ServicoCtrl._dadosServico = response;
            // Recupera as acoes na URL
            ServicoCtrl.readActionInUrl();
            // Envia visualização do servico
            ServicoCtrl.sendViewProduct();
        });
    },

    /** Ações na URL */
    readActionInUrl: () => {
        let action = window.Helpers.getURLParameter('alterar');
        // Verifica se possui alguma alteracao na URL
        if (action === "quantidade" || action === "data") {
            $("[data-action='comprar']").trigger('click');
        }
    },

    /** Visualiação do servico trackers */
    sendViewProduct: () => {

        // Envia a visualização para o Facebook
        window.Facebook.sendViewContent(ServicoCtrl._dadosServico['uuid'], ServicoCtrl._dadosServico['valor_venda']);

        // Envia a visualização para o GTM
        window.Google.sendProductDetails({
            'id': ServicoCtrl._dadosServico['uuid'],
            'name': ServicoCtrl._dadosServico['nome'],
            'price': ServicoCtrl._dadosServico['valor_venda'],
            'category': `${ServicoCtrl._dadosServico['categoria']['nome']} / ${ServicoCtrl._dadosServico['destino']['nome']}`,
            'google_business_vertical': 'custom' // Google ADS
        });
    },

    /** Click no botao voltar do navegador */
    onClickBackButtonNavegador: () => {
        window.addEventListener('popstate', () => {
            let $modal = $.jqmodal.getCurrent();
            // Fecha a modal atual
            if ($modal !== null) $modal.close();
        });
    },

    /** Eventos ao fechar as modais */
    onCloseModals: () => {
        $("#modal-acompanhantes").on($.jqmodal.CLOSE, () => {
            if (ServicoCtrl._sendToCart) return;
            ServicoCtrl.openModalComprar();
        });
        $("#modal-campo-adicional").on($.jqmodal.CLOSE, () => {
            if (ServicoCtrl._sendToCart) return;
            // Verifica se precisa dos dados dos acompanhantes
            if (ServicoCtrl._response['necessita_identificacao']) {
                ServicoCtrl.openModalAcompanhantes(false);
            } else {
                // Abre a modal de datas
                ServicoCtrl.openModalComprar();
            }
        });
        $("#comprar").on($.jqmodal.CLOSE, () => {
            $('#whatsapp').show();
        });
    },

    /** Abre a modal de compra */
    openModalComprar: () => {
        // Abre a modal de compra
        $("#comprar").jqmodal();
        // Push State
        ServicoCtrl.pushStateModal("datas");
    },

    /**
     * Push State Modal
     *
     * @param modal
     */
    pushStateModal: (modal) => {
        let url = window.location.href.replace("#datas", "")
            .replace("#acompanhantes", "")
            .replace("#adicionais", "");
        // Push state para a modal
        history.pushState(null, null, url + "#" + modal);
    },

    /** Click no botao comprar */
    onClickButtonComprar: () => {
        // Coloca o novo bind
        $("[data-action='comprar'], [data-action='edit-servico']").on('click', (e) => {
            e.preventDefault();
            $('#whatsapp').hide();
            let $this = $(e.currentTarget);
            let $modal_compra = $("#comprar");
            let agenda_carrinho = null;

            // Loader das datas
            ServicoCtrl.loaderDatas.show();
            ServicoCtrl._sendToCart = false;

            // URL do calendario (para edicao passa carrinho=true)
            let url = ($this.attr('data-action') === "edit-servico")
                ? $this.attr('data-route') + "?carrinho=true" : ServicoCtrl._dadosServico['urls']['calendario'];

            // Recupera as datas para o calendario
            let result = $.getJSON(url);
            result.done((response) => {

                // Verifica se retornou disponibilidade
                if (response['events'].length > 0) {

                    // Resetamos o calendario
                    let calendar_container = $('#calendar-container');
                    calendar_container.html("");

                    // Salvamos os dados de retorno
                    ServicoCtrl._response = response;

                    // Salvamos a agenda do serviço
                    ServicoCtrl._agenda = $.extend(true, {}, response['disponibilidade']);

                    // Prepara as datas para o calendario
                    ServicoCtrl.prepareDatesForCalendar(response['events']);

                    // Carrega as informacoes dos acompanhantes
                    if (response['necessita_identificacao']) {
                        window.DadosFactory.loadAcompanhantes();
                    }

                    // Recuperamos o primeiro evento disponivel
                    let first_event = ServicoCtrl._events[0],
                        first_agenda = $.extend(true, {}, response['disponibilidade'][0]);

                    /**
                     * Data selecionada no calendario
                     * Quando nao houver data ja selecionada no carrinho utiliza a primeira data
                     * Senao utiliza a data que foi selecionada anteriormente (atualiza mais abaixo ↓↓)
                     */
                    let selected_date = first_event['date'];

                    // Caso for edicao do servico no carrinho
                    if ($this.attr('data-action') === "edit-servico") {
                        // Recupera os dados do carrinho
                        ServicoCtrl._dadosServico = $.extend(true, {}, response['carrinho']);
                        ServicoCtrl._dadosServico['id'] = ServicoCtrl._dadosServico['gtin'];
                        ServicoCtrl._dadosServico['nome'] = ServicoCtrl._dadosServico['nome_servico'];
                        ServicoCtrl._dadosServico['foto_servico'] = ServicoCtrl._dadosServico['foto_principal'];
                        ServicoCtrl._dadosServico['urls']['servico'] = ServicoCtrl._dadosServico['url'];
                        ServicoCtrl._dadosServico['categoria'] = { 'nome': ServicoCtrl._dadosServico['categoria'] };
                        ServicoCtrl._dadosServico['destino'] = { 'nome': ServicoCtrl._dadosServico['destino'] };
                        // Retira index desnecessarias
                        delete ServicoCtrl._dadosServico["_token"];
                        delete ServicoCtrl._dadosServico["agenda_selecionada"];
                        delete ServicoCtrl._dadosServico["acompanhantes"];
                        delete ServicoCtrl._dadosServico["adicionais"];
                        // Salva a agenda ja selecionada anteriormente
                        agenda_carrinho = $.extend(true, {}, response['carrinho']['agenda_selecionada']);
                        // Altera o texto do botao na modal
                        $modal_compra.find("[data-action='sendToCart']").html("Atualizar carrinho");
                    }

                    // Payload para o carrinho
                    ServicoCtrl._payload = {
                        gtin: ServicoCtrl._dadosServico['id'],
                        uuid: ServicoCtrl._dadosServico['uuid'],
                        nome_servico: ServicoCtrl._dadosServico['nome'],
                        integracao: ServicoCtrl._dadosServico['integracao'],
                        foto_principal: ServicoCtrl._dadosServico['foto_servico'],
                        categoria: ServicoCtrl._dadosServico['categoria']['nome'],
                        destino: ServicoCtrl._dadosServico['destino']['nome'],
                        url: ServicoCtrl._dadosServico['urls']['servico'],
                        cidade: ServicoCtrl._dadosServico['cidade'],
                        localizacao: ServicoCtrl._dadosServico['localizacao'],
                        horario: ServicoCtrl._dadosServico['horario'],
                        com_bloqueio: 0,
                        sem_bloqueio: 0,
                        valor_total: 0,
                        agenda_selecionada: {
                            data_servico_id: first_agenda['data_servico_id'],
                            data: first_agenda['data'],
                            disponibilidade: first_agenda['disponibilidade'],
                            valor_venda: first_agenda['valor_venda'],
                            valor_venda_brl: first_agenda['valor_venda_brl'],
                            variacoes: first_agenda['variacoes']
                        }
                    };

                    // Caso esteja com data selecionado anteriormente no carrinho
                    if (agenda_carrinho !== null) {
                        ServicoCtrl._payload['agenda_selecionada'] = agenda_carrinho;
                        ServicoCtrl._payload['com_bloqueio'] = parseInt(ServicoCtrl._dadosServico['com_bloqueio']);
                        ServicoCtrl._payload['sem_bloqueio'] = parseInt(ServicoCtrl._dadosServico['sem_bloqueio']);
                        ServicoCtrl._payload['valor_total'] = parseFloat(ServicoCtrl._dadosServico['valor_total']);
                        // ALTERA A DATA SELECIONADA NO CALENDARIO
                        selected_date = window.Helpers.dataToIE(agenda_carrinho['data']);
                    }

                    // Inicializa o calendario
                    new Datepickk({
                        container: calendar_container[0],
                        maxSelections: 1,
                        minDate: first_event['date'],
                        maxDate: ServicoCtrl._events[ServicoCtrl._events.length - 1]['date'],
                        startDate: selected_date,
                        inline: true,
                        today: false,
                        tooltips: ServicoCtrl._events,
                        onSelect: function (checked) {
                            if (checked) ServicoCtrl.onChangeCalendarDate(this);
                        }
                    }).selectDate(selected_date);

                    // Monta a listagem das opções
                    ServicoCtrl.updateListOptions();

                    // Total do servico
                    let total_servico = window.Helpers.formataValor(ServicoCtrl._payload['valor_total'], 2);

                    // Atualiza o valor da compra HTML
                    $modal_compra.find("p#total-modal").html(`R$ ${total_servico}`);

                    // Atualiza o valor da compra mobile HTML
                    $modal_compra.find("p#total-modal-mobile").html(`R$ ${total_servico}`);

                    // Abre a modal para compra
                    ServicoCtrl.openModalComprar();

                } else {
                    // Aviso que não encontrou a disponibilidade
                    ServicoCtrl.loaderDatas.show("Ops!<br>Para a compra deste produto,<br>Fale com uma de nossas atendentes.", true);
                }
            });
            // Mensagem de erro
            result.fail(() => {
                // Aviso que não encontrou a disponibilidade
                ServicoCtrl.loaderDatas.show("Não foi possível recuperar as <br> informações do serviço. Tente novamente!", true);
            })
        });
    },

    /**
     * Arruma os eventos para o calendario
     *
     * @param events
     */
    prepareDatesForCalendar: (events) => {

        let length = events.length,
            new_events = [];

        for (let i = 0; i < length; i++) {

            // Recupera o event
            let event = events[i];

            // Cria um novo array com as datas
            new_events.push({
                date: window.Helpers.dataToIE(event['date']),
                text: event['text']
            });
        }

        // Salva os eventos do calendario
        ServicoCtrl._events = new_events;
    },

    /** Atualiza a lista de opções do serviço */
    updateListOptions: () => {

        let html = "";
        let variacoes = ServicoCtrl._payload['agenda_selecionada']['variacoes'],
            length = variacoes.length;

        // Template das opções
        let html_people = `
                <div class="d-flex align-items-center justify-content-between item-age pb-4">
                    <div class="text-truncate">
                        <p class="m-0 font-weight-medium h5" style="white-space: normal;">{{ variacao }}</p>
                        <span class="text-muted" style="white-space: normal;">{{ descricao }}</span>
                        <span class="option-price">R$ {{ valor }}</span>
                    </div>
                    <div class="amount-age">
                        <button data-variacao="{{ variacao_id }}" data-index="{{ index }}" data-remove class="btn btn-rounded" title="Remover">
                            <i class="iconify" data-icon="jam:minus"></i>
                        </button>
                        <strong data-variacao="{{ variacao_id }}" data-quantidade>{{ quantidade }}</strong>
                        <button data-variacao="{{ variacao_id }}" data-index="{{ index }}" data-add class="btn btn-rounded" title="Adicionar">
                            <i class="iconify" data-icon="jam:plus"></i>
                        </button>
                    </div>
                </div>
        `;

        for (let i = 0; i < length; i++) {

            // Recupera os dados da variacao e a quantidade selecionada
            let variacao = variacoes[i],
                quantidade_selecionada = variacoes[i]['quantidade'] || 0;

            // Altera as variaveis no html
            html += html_people.replace(/{{ variacao }}/g, variacao['variacao'])
                .replace(/{{ descricao }}/g, variacao['descricao'])
                .replace(/{{ valor }}/g, variacao['valor_venda_brl'])
                .replace(/{{ variacao_id }}/g, variacao['variacao_id'])
                .replace(/{{ quantidade }}/g, quantidade_selecionada)
                .replace(/{{ index }}/g, i);
        }

        // Lista de variacoes
        let $list_variacoes = $("div.list-variacoes");

        // Coloca a lista das variacoes
        $list_variacoes.html(html);
    },

    /** Click no botão para adicionar uma pessoa */
    onClickAddPessoa: () => {
        $(document).on("click", "button[data-add]", (event) => {
            event.preventDefault();
            let $this = $(event.currentTarget),
                variacao_id = $this.attr('data-variacao'),
                index = $this.attr("data-index");

            // Disponibilidade máxima do dia
            let max_disponibilidade = parseInt(ServicoCtrl._payload['agenda_selecionada']['disponibilidade']);

            // Recuperamos os dados da variacao
            let variacao = ServicoCtrl._payload['agenda_selecionada']['variacoes'][index],
                bloqueio_consumido = parseInt(ServicoCtrl._payload['com_bloqueio']),
                sem_bloqueio = parseInt(ServicoCtrl._payload['sem_bloqueio']);

            // Caso o valor esteja em string (utilizado na edicao do servico no carrinho)
            if (typeof variacao['valor_total'] === "string") {
                ServicoCtrl._payload['agenda_selecionada']['variacoes'][index]['valor_total'] = parseFloat(ServicoCtrl._payload['agenda_selecionada']['variacoes'][index]['valor_total']);
                ServicoCtrl._payload['agenda_selecionada']['variacoes'][index]['valor_venda'] = parseFloat(ServicoCtrl._payload['agenda_selecionada']['variacoes'][index]['valor_venda']);
            }

            // Valor da compra atual
            let valor_total_atual = parseFloat(ServicoCtrl._payload['valor_total']);

            // Verifica se consome bloqueio
            if (variacao['bloqueio'] === "SIM") {

                // Verifica se a quantidade atual é menor que a disponibilidade
                if (bloqueio_consumido < max_disponibilidade) {

                    // Adiciona uma quantidade na variação
                    if (ServicoCtrl._payload['agenda_selecionada']['variacoes'][index]['quantidade'] === undefined) {
                        ServicoCtrl._payload['agenda_selecionada']['variacoes'][index]['quantidade'] = 1;
                        ServicoCtrl._payload['agenda_selecionada']['variacoes'][index]['valor_total'] = parseFloat(variacao['valor_venda']);
                    } else {
                        // Atualiza a quantidade e valor total selecionado
                        ServicoCtrl._payload['agenda_selecionada']['variacoes'][index]['quantidade']++;
                        ServicoCtrl._payload['agenda_selecionada']['variacoes'][index]['valor_total'] += parseFloat(variacao['valor_venda']);
                    }

                    // Soma ao total que consome bloqueio
                    ServicoCtrl._payload['com_bloqueio'] = bloqueio_consumido + 1;

                    // Atualiza o novo valor da compra
                    ServicoCtrl._payload['valor_total'] = valor_total_atual + parseFloat(variacao['valor_venda']);

                    // Remove o aviso da tela
                    ServicoCtrl._avisoModal.addClass("d-none");

                } else {
                    // Alerta de quando nao tem mais disponibilidade
                    ServicoCtrl._avisoModal.find("p").html(`Só há ${max_disponibilidade} lugares disponíveis nesta data!`);
                    ServicoCtrl._avisoModal.removeClass("d-none");
                    return;
                }

            } else {

                // Verifica o limite de pessoas que não consome bloqueio
                if (sem_bloqueio < 5) {

                    // Adiciona uma quantidade na opção
                    if (ServicoCtrl._payload['agenda_selecionada']['variacoes'][index]['quantidade'] === undefined) {
                        ServicoCtrl._payload['agenda_selecionada']['variacoes'][index]['quantidade'] = 1;
                        ServicoCtrl._payload['agenda_selecionada']['variacoes'][index]['valor_total'] = parseFloat(variacao['valor_venda']);
                    } else {
                        // Caso o valor esteja em string (utilizado na edicao do servico no carrinho)
                        ServicoCtrl._payload['agenda_selecionada']['variacoes'][index]['quantidade']++;
                        ServicoCtrl._payload['agenda_selecionada']['variacoes'][index]['valor_total'] += parseFloat(variacao['valor_venda']);
                    }

                    // Soma ao total que não consome bloqueio
                    ServicoCtrl._payload['sem_bloqueio'] = sem_bloqueio + 1;

                    // Atualiza o novo valor da compra
                    ServicoCtrl._payload['valor_total'] = valor_total_atual + parseFloat(variacao['valor_venda']);

                    // Remove o aviso da tela
                    ServicoCtrl._avisoModal.addClass("d-none");

                } else {

                    // Alerta de quando nao tem mais disponibilidade
                    ServicoCtrl._avisoModal.find("p").html(`O limite para ${variacao['variacao'].toLowerCase()} é de 5 lugares!`);
                    ServicoCtrl._avisoModal.removeClass("d-none");
                    return;
                }
            }

            // Atualiza a listagem de opções e valor na tela
            ServicoCtrl.updateQuantidadeOpcaoHtml(variacao_id, ServicoCtrl._payload['agenda_selecionada']['variacoes'][index]['quantidade']);
        });
    },

    /**
     * Atualiza a quantidade no html da variação
     *
     * @param variacao_id
     * @param quantidade
     */
    updateQuantidadeOpcaoHtml: (variacao_id, quantidade) => {
        let total_servico = window.Helpers.formataValor(ServicoCtrl._payload['valor_total'], 2);
        $("strong[data-variacao='" + variacao_id + "']").html(quantidade);
        // Atualiza o valor da compra HTML
        $("#comprar p#total-modal").html(`R$ ${total_servico}`);
        // Atualiza o valor da compra HTML
        $("#comprar p#total-modal-mobile").html(`R$ ${total_servico}`);
    },

    /** Click no botão para remover uma pessoa */
    onClickRemoverPessoa: () => {
        $(document).on("click", "button[data-remove]", (event) => {
            event.preventDefault();
            let $this = $(event.currentTarget),
                variacao_id = $this.attr('data-variacao'),
                index = $this.attr("data-index");

            // Recuperamos os dados da variacao
            let variacao = ServicoCtrl._payload['agenda_selecionada']['variacoes'][index],
                bloqueio_consumido = parseInt(ServicoCtrl._payload['com_bloqueio']),
                sem_bloqueio = parseInt(ServicoCtrl._payload['sem_bloqueio']);

            // Caso o valor esteja em string (utilizado na edicao do servico no carrinho)
            if (typeof variacao['valor_total'] === "string") {
                ServicoCtrl._payload['agenda_selecionada']['variacoes'][index]['valor_total'] = parseFloat(ServicoCtrl._payload['agenda_selecionada']['variacoes'][index]['valor_total']);
                ServicoCtrl._payload['agenda_selecionada']['variacoes'][index]['valor_venda'] = parseFloat(ServicoCtrl._payload['agenda_selecionada']['variacoes'][index]['valor_venda']);
            }

            // Valor da compra atual
            let valor_total_atual = parseFloat(ServicoCtrl._payload['valor_total']);

            // Verifica se existe quantidade para a opção clicada
            if (variacao['quantidade'] !== undefined) {

                // Remove o aviso da tela
                ServicoCtrl._avisoModal.addClass("d-none");

                // Caso tenha alguma pessoa na opção para não ficar negativo
                if (variacao['quantidade'] > 0) {

                    // Diminui a quantidade e valor
                    ServicoCtrl._payload['agenda_selecionada']['variacoes'][index]['quantidade']--;
                    ServicoCtrl._payload['agenda_selecionada']['variacoes'][index]['valor_total'] -= parseFloat(variacao['valor_venda']);

                    // Verifica se consome bloqueio ou não
                    if (variacao['bloqueio'] === "SIM") {
                        ServicoCtrl._payload['com_bloqueio'] = bloqueio_consumido - 1;
                    } else {
                        ServicoCtrl._payload['sem_bloqueio'] = sem_bloqueio - 1;
                    }

                    // Atualiza o novo valor da compra
                    ServicoCtrl._payload['valor_total'] = valor_total_atual - parseFloat(variacao['valor_venda']);

                    // Atualiza a listagem de opções e valor na tela
                    ServicoCtrl.updateQuantidadeOpcaoHtml(variacao_id, ServicoCtrl._payload['agenda_selecionada']['variacoes'][index]['quantidade']);
                }
            }
        });
    },

    /**
     * Função chamada quando altera a data no calendario
     * @param date
     * @returns {boolean}
     */
    onChangeCalendarDate: (date) => {

        // Remove avisos na modal
        ServicoCtrl._avisoModal.addClass("d-none");

        // Recupera a data para formato americano
        let novaDataSelecionada =
            date.getFullYear() + "-" +
            ("0" + (date.getMonth() + 1)).substr(-2) + "-" +
            ("0" + date.getDate()).substr(-2);

        /*
         === Quando clica para abrir o calendario ele chama a função novamente
         === Este if impede que seja atualizado os valores quando é a mesma data
         */
        if (novaDataSelecionada === ServicoCtrl._payload['agenda_selecionada']['data']) {
            return false;
        }

        // Nova data
        let data_selecionada = {};

        // Recupera as informações da data selecionada
        for (let agenda in ServicoCtrl._agenda) {
            if (ServicoCtrl._agenda.hasOwnProperty(agenda)) {
                if (ServicoCtrl._agenda[agenda]['data'] === novaDataSelecionada) {
                    data_selecionada = $.extend(true, {}, ServicoCtrl._agenda[agenda]);
                    break;
                }
            }
        }

        // Salva os dados da data anterior
        let data_anterior = ServicoCtrl._payload['agenda_selecionada'];

        // Verifica se a disponibilidade da data selecionada é maior que o bloqueio consumido
        if (data_selecionada['disponibilidade'] >= ServicoCtrl._payload['com_bloqueio']) {

            // Verifica se o valor base é diferente da data anterior
            // if(parseFloat(data_anterior['valor_venda']) !== parseFloat(data_selecionada['valor_venda'])) {

            // Recupera as variações do serviço com o valor anterior
            let variacoes_anterior = data_anterior['variacoes'],
                length = variacoes_anterior.length,
                novo_valor_total = 0;

            for (let i = 0; i < length; i++) {

                // Dados da variação servico anterior
                let antiga_variacao = variacoes_anterior[i],
                    quantidade = antiga_variacao['quantidade'] || 0;

                // Dados da antiga variação servico
                let nova_opcao = data_selecionada['variacoes'].filter((object) => {
                    return (parseInt(object['variacao_id']) === parseInt(antiga_variacao['variacao_id']));
                });

                // Remove casa o filter
                nova_opcao = nova_opcao[0];

                // Novo valor da variação servico
                let novo_valor = nova_opcao['valor_venda'] * parseInt(quantidade);

                // Atualiza os dados com a nova variação com valores diferentes
                ServicoCtrl._payload['agenda_selecionada']['variacoes'][i]['valor_total'] = novo_valor;
                ServicoCtrl._payload['agenda_selecionada']['variacoes'][i]['valor_venda'] = nova_opcao['valor_venda'];
                ServicoCtrl._payload['agenda_selecionada']['variacoes'][i]['valor_venda_brl'] = nova_opcao['valor_venda_brl'];

                // Novo valor total
                novo_valor_total += novo_valor;
            }

            // Atualiza o valor total da compra
            ServicoCtrl._payload['valor_total'] = novo_valor_total;
            // }

            // Informações para carrinho de compras
            ServicoCtrl._payload['agenda_selecionada']['data_servico_id'] = data_selecionada['data_servico_id'];
            ServicoCtrl._payload['agenda_selecionada']['disponibilidade'] = data_selecionada['disponibilidade'];
            ServicoCtrl._payload['agenda_selecionada']['valor_venda_brl'] = data_selecionada['valor_venda_brl'];
            ServicoCtrl._payload['agenda_selecionada']['valor_venda'] = data_selecionada['valor_venda'];
            ServicoCtrl._payload['agenda_selecionada']['data'] = data_selecionada['data'];

        } else {

            // Mostra a caixa de mensagem
            ServicoCtrl._avisoModal.find("p").html(`Essa data possui somente ${data_selecionada['disponibilidade']} lugares disponíveis!`);
            ServicoCtrl._avisoModal.removeClass('d-none');

            // Zera a quantidade de pessoas pois a data não tem disponibilidade total
            ServicoCtrl._payload['com_bloqueio'] = 0;
            ServicoCtrl._payload['sem_bloqueio'] = 0;
            ServicoCtrl._payload['valor_total'] = 0;
            ServicoCtrl._payload['agenda_selecionada'] = data_selecionada;
        }

        // Atualiza a lista de opções
        ServicoCtrl.updateListOptions();

        // Atualiza o valor da compra HTML
        let total_servico = window.Helpers.formataValor(ServicoCtrl._payload['valor_total'], 2);
        $("#comprar p#total-modal").html(`R$ ${total_servico}`);
        $("#comprar p#total-modal-mobile").html(`R$ ${total_servico}`);
    },

    /** No clique do botão de adicionar ao carrinho */
    onClickButtonAdicionarCarrinho: () => {
        // Coloca o novo bind
        $("button[data-action='sendToCart']").on('click', (e) => {
            e.preventDefault();

            // Verifica se foi selecionado alguma variação
            if (ServicoCtrl._payload['com_bloqueio'] > 0 && ServicoCtrl._payload['valor_total'] > 0) {

                // Inicia a validação da quantidade mínima necessária de passageiros
                let totalMinPax = 0;
                let totalSelecionado = 0;
                ServicoCtrl._payload['agenda_selecionada']['variacoes'].forEach((variacao) => {
                    totalMinPax += variacao.min_pax;
                    totalSelecionado += variacao.quantidade || 0; // Usa 0 como fallback se não estiver definido
                });

                // Verifica se a quantidade total selecionada é menor que a quantidade mínima total necessária
                if (totalSelecionado < totalMinPax) {
                    // Exibe a mensagem de erro
                    ServicoCtrl._avisoModal.find("p").html(`A quantidade mínima de pessoas para esse produto é ${totalMinPax}. Você selecionou ${totalSelecionado}.`);
                    ServicoCtrl._avisoModal.removeClass("d-none");
                    return false; // Interrompe a execução aqui se não atender ao mínimo necessário
                }

                // Verifica se precisa dos dados dos acompanhantes
                if (ServicoCtrl._response && ServicoCtrl._response['necessita_identificacao']) {
                    ServicoCtrl.openModalAcompanhantes();
                } else if (ServicoCtrl._response && ServicoCtrl._response['campos_adicionais'].length > 0) {
                    // Abre a modal dos campos adicionais
                    ServicoCtrl.openModalCamposAdicionais();
                } else {
                    // Envia as informações para o carrinho
                    ServicoCtrl.sendToCart(true);
                }
            } else {
                // Alerta de que deve selecionar uma quantidade
                ServicoCtrl._avisoModal.find("p").html(`Informe uma quantidade para continuar...`);
                ServicoCtrl._avisoModal.removeClass("d-none");
                return false;
            }
            setTimeout(function () {
                $('#whatsapp').hide();
            }, 200); // Correção para garantir que o temporizador esteja em milissegundos
        });
    },

    /**
     * Modal para informar os dados dos acompanhantes
     *
     * @param update
     */
    openModalAcompanhantes: (update = true) => {

        // Recupera as informacoes dos acompanhantes
        let acompanhantes = ServicoCtrl._payload['agenda_selecionada']['variacoes'],
            length = acompanhantes.length, html = "", quantidade = 0;

        // Abre a modal e coloca o formulario
        let $modal_acompanhantes = $("#modal-acompanhantes");

        // Caso seja para atualizar o formulario
        if (update) {
            // Monta a lista para preencher
            for (let i = 0; i < length; i++) {
                let acomp = acompanhantes[i];
                for (let j = 0; j < acomp['quantidade']; j++) {
                    html +=
                        `<div class="list-acompanhantes mb-2" data-acompanhante="${acomp['variacao_id']}">
                            <h6 class="font-weight-medium h5">${++quantidade}° Acompanhante - ${acomp['variacao']}</h6>
                            <div class="row">
                                <div class="form-group nome_acompanhante col-sm-12 col-lg-4">
                                    <label for="nome_${quantidade}">Nome completo*</label>
                                    <input type="text" class="form-control" id="nome_${quantidade}" name="acompanhantes[${quantidade}][nome]" data-auto-capitalize data-nome-completo="true" required data-min="5" data-required placeholder="Nome e sobrenome" autocomplete="off" title="Nome completo" data-list="nomes_acom" data-callback="nome">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="form-group doc_acompanhante col-sm-12 col-md-6 col-lg-4">
                                    <label for="documento_${quantidade}">N° do documento*</label>
                                    <input type="text" class="form-control" id="documento_${quantidade}" name="acompanhantes[${quantidade}][documento]" required data-min="5" data-required placeholder="Número CPF ou RG" title="Documento" autocomplete="off" data-list="doc_acom" data-callback="documento">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="form-group nasc_acompanhante col-sm-12 col-md-6 col-lg-4">
                                    <label for="nascimento_${quantidade}">Nascimento*</label>
                                    <input type="tel" class="form-control vanillaMask" id="nascimento_${quantidade}" name="acompanhantes[${quantidade}][nascimento]" required data-min="9" data-required placeholder="DD/MM/AAAA" data-mask="date" title="Nascimento" autocomplete="off" maxlength="10" autocomplete="off" data-list="data_acom" data-callback="nascimento">
                                    <div class="invalid-feedback"></div>
                                    <input type="hidden" name="acompanhantes[${quantidade}][variacao_servico_id]" value="${acomp['variacao_id']}">
                                </div>
                            </div>
                        </div>`;
                }
            }

            $modal_acompanhantes.find(".fields-input").html(html);

            // Auto capitalize
            window.Helpers.capitalizeInput();
            // Mascaras para os campos
            window.Helpers.vanillaMask();
        }

        // Abre a modal
        $modal_acompanhantes.jqmodal();
        // Push State
        ServicoCtrl.pushStateModal("acompanhantes");
    },

    /**
     * Modal para informações adicionais
     *
     * @param update
     */
    openModalCamposAdicionais: (update = true) => {
        // Recupera as informacoes dos campos
        let campos_adicionais = ServicoCtrl._response['campos_adicionais'],
            length = campos_adicionais.length, html = "";

        // Abre a modal e coloca o formulario
        let $modal_adicionais = $("#modal-campo-adicional");

        // Caso seja para atualizar a modal
        if (update) {
            // Monta a lista para preencher
            for (let i = 0; i < length; i++) {
                let campo = campos_adicionais[i];
                let required = "", info_required = "";
                let classGrid = (i === 0) ? "col-sm-12" : "col-sm-12 col-md-6";
                if (campo['obrigatorio'] === "SIM") {
                    required = "data-required required"; info_required = "*";
                }
                html +=
                    `<div class="form-group ${classGrid}">
                        <label for="adicional_${campo['id']}">${campo['campo']}${info_required}</label>
                        <input type="text" class="form-control" id="adicional_${campo['id']}" name="adicionais[${i}][informacao]" data-min="1" ${required} placeholder="${campo['placeholder']}" title="${campo['campo']}">
                        <span class="invalid-feedback"></span>
                        <input type="hidden" name="adicionais[${i}][campo_adicional_servico_id]" value="${campo['id']}">
                    </div>`;
            }
            $modal_adicionais.find(".fields-input").html(html);
        }

        // Botão voltar
        if (ServicoCtrl._response['necessita_identificacao']) {
            $modal_adicionais.find("span.text-btn").html('Acompanhantes');
        } else {
            $modal_adicionais.find("span.text-btn").html('Ver datas');
        }

        // Abre a modal
        $modal_adicionais.jqmodal();
        // Push State
        ServicoCtrl.pushStateModal("adicionais");
    },

    // Envio do formulário de acompanhantes
    onSubmitFormPersons: () => {
        $("form#acompanhantes").on("submit", (event) => {
            event.preventDefault();
            let $this = $(event.currentTarget);
            let $erroBox = $("#modal-acompanhantes .info-error");
            $erroBox.addClass("d-none");
            // valida o formulario
            if (window.App.validateForm($this)) {
                // Mostra o loader
                window.Helpers.loaderInsideModal.show();
                // Salva os dados dos acompanhantes
                let result = $.post($this.attr('action'), $this.serialize());
                result.done((response) => {
                    // Verifica o retorno
                    if (typeof response['acompanhantes'] === "object") {
                        // Salva os dados dos acompanhantes
                        ServicoCtrl._payload['acompanhantes'] = response['acompanhantes'];
                        // Salva os dados do localstorage
                        window.DadosFactory.saveDadosAcompanhantes(response['acompanhantes']);
                        // Verifica se tem campo adicional
                        if (ServicoCtrl._response['campos_adicionais'].length > 0) {
                            // Remove o loader
                            window.Helpers.loaderInsideModal.hide();
                            // Abre a modal dos campos adicionais
                            ServicoCtrl.openModalCamposAdicionais();
                        } else {
                            // Envia as informacoes para o carrinho
                            ServicoCtrl.sendToCart();
                        }
                    } else {
                        // Remove o loader
                        window.Helpers.loaderInsideModal.hide();
                        // Aviso que nao conseguiu adicionar no carrinho
                        $erroBox.find("p").html("Não foi possível salvar os dados dos acompanhantes. Tente novamente!");
                        $erroBox.removeClass("d-none");
                    }
                });
                // Caso falhe a requisicao
                result.fail(() => {
                    // Remove o loader
                    window.Helpers.loaderInsideModal.hide();
                    // Aviso que nao conseguiu adicionar no carrinho
                    $erroBox.find("p").html("Não foi possível salvar os dados dos acompanhantes. Tente novamente!");
                    $erroBox.removeClass("d-none");
                });
            }
        });
    },

    // Envio do formulário de dados adicionais
    onSubmitFormAdditional: () => {
        $("form#adicionais").on("submit", (event) => {
            event.preventDefault();
            let $this = $(event.currentTarget);
            let $erroBox = $("#modal-campo-adicional .info-error");
            $erroBox.addClass("hide");
            // valida o formulario
            if (window.App.validateForm($this)) {
                // Mostra o loader
                window.Helpers.loaderInsideModal.show();
                // Salva os dados dos acompanhantes
                let result = $.post($this.attr('action'), $this.serialize());
                result.done((response) => {
                    // Verifica o retorno
                    if (typeof response['adicionais'] === "object") {
                        // Salva os dados dos acompanhantes
                        ServicoCtrl._payload['adicionais'] = response['adicionais'];
                        // Envia as informacoes para o carrinho
                        ServicoCtrl.sendToCart();
                    } else {
                        // Remove o loader
                        window.Helpers.loaderInsideModal.hide();
                        // Aviso que nao conseguiu adicionar no carrinho
                        $erroBox.find("p").html("Não foi possível salvar os dados adicionais. Tente novamente!");
                        $erroBox.removeClass("d-none");
                    }
                });
                // Caso falhe a requisicao
                result.fail(() => {
                    // Remove o loader
                    window.Helpers.loaderInsideModal.hide();
                    // Aviso que nao conseguiu adicionar no carrinho
                    $erroBox.find("p").html("Não foi possível salvar os dados adicionais. Tente novamente!");
                    $erroBox.removeClass("d-none");
                });
            }
        });
    },

    /**
     * Envia as informações do servico para o carrinho
     *
     * @param show_loader
     */
    sendToCart: (show_loader) => {
        ServicoCtrl._sendToCart = true;
        // Salva a referencia da modal
        let $modal = $.jqmodal.getCurrent().$elm, $errorBox = $modal.find('.info-error');
        $errorBox.addClass('d-none');
        // Caso tenha que mostrar o loader
        if (show_loader) {
            // Fecha as modais
            $.jqmodal.close();
            // Loader das datas
            ServicoCtrl.loaderDatas.show('Adicionando ao carrinho...');
        }
        // Recupera o token laravel
        ServicoCtrl._payload['_token'] = window.Helpers.getTokenLaravel();
        // POST para adicionar o servico no carrinho
        let result = $.post(ServicoCtrl._dadosServico['urls']['carrinho'], ServicoCtrl._payload);
        result.done((response) => {
            // Verifica o retorno do carrinho
            if (response['adicionar']) {
                // Envia os detalhes do servico para o Google
                window.Google.sendAddToCard({
                    'id': ServicoCtrl._dadosServico['uuid'],
                    'name': ServicoCtrl._dadosServico['nome'],
                    'price': ServicoCtrl._dadosServico['valor_venda'],
                    'category': `${ServicoCtrl._dadosServico['categoria']['nome']} / ${ServicoCtrl._dadosServico['destino']['nome']}`
                });
                // Envia a adicao ao carrinho para o Facebook
                window.Facebook.sendAddToCard(ServicoCtrl._dadosServico['uuid'], ServicoCtrl._dadosServico['valor_venda']);
                // Redireciona para o carrinho de compras
                setTimeout(() => {
                    window.location.href = response['route'];
                }, 200);
            } else {
                // Remove o loader
                window.Helpers.loaderInsideModal.hide();
                // Aviso que nao conseguiu adicionar no carrinho
                $modal.jqmodal();
                $errorBox.find('p').html("Não foi possível adicionar o seu serviço no carrinho. Tente novamente!");
                $errorBox.removeClass('d-none');
            }
        });
        // Caso de falha na conexao
        result.fail(() => {
            // Remove o loader
            window.Helpers.loaderInsideModal.hide();
            // Aviso que nao conseguiu adicionar no carrinho
            $modal.jqmodal();
            $errorBox.find('p').html("Não foi possível adicionar o seu serviço no carrinho. Tente novamente!");
            $errorBox.removeClass('d-none');
        });
    },

    // Função para abrir a avaliação completa quando ela for muito grande e esteja limitada para não quebrar layout
    verMaisAvaliacao: () => {
        $(".btn-ver-mais").click((event) => {
            event.preventDefault();
            let botao = event.target;
            let key = botao.getAttribute('key');

            let texto_resumo = $(`#avaliacao-resumo-${key}`);
            let texto_completo = $(`#avaliacao-completo-${key}`);
            botao = $(`#ver-mais-avaliacao-${key}`);
            let botao_ver_menos = $(`#ver-menos-avaliacao-${key}`);

            botao.css('display', 'none');
            botao_ver_menos.css('display', 'block');
            texto_resumo.css('display', 'none');
            texto_completo.css('display', 'block');
        })
    },

    // Responsavel por ouvir os cliques em botões de fechar a avaliaçao muito grande
    verMenosAvaliacao: () => {
        $(".btn-ver-menos").click((event) => {
            event.preventDefault();
            let botao = event.target;
            let key = botao.getAttribute('key');

            let texto_resumo = $(`#avaliacao-resumo-${key}`);
            let texto_completo = $(`#avaliacao-completo-${key}`);
            botao = $(`#ver-menos-avaliacao-${key}`);
            let botao_ver_mais = $(`#ver-mais-avaliacao-${key}`);

            botao.css('display', 'none');
            botao_ver_mais.css('display', 'block')
            texto_resumo.css('display', 'block');
            texto_completo.css('display', 'none');
        })
    },

    // Função responsavel por carregar mais avaliações
    carregarMaisAvaliacoes: () => {
        $("#carregar-mais-avaliacoes").click(() => {
            let quantidade_maxima = parseInt($("#avaliacao-qtd").text());
            let quantidade_impressa = parseInt($("#avaliacao-qtd-mostrada").text());
            let link = $("#avaliacao-link").text();

            console.log(`Quantidade máxima = ${quantidade_maxima} | Quantidade impressa = ${quantidade_impressa}`);

            link += `?limite=${quantidade_impressa + 2}&inicial=${quantidade_impressa}`;

            quantidade_impressa = quantidade_impressa + 2;

            if (quantidade_impressa >= quantidade_maxima) {
                $("#carregar-mais-avaliacoes").css('display', 'none');
            }

            $("#avaliacao-qtd-mostrada").text(quantidade_impressa);

            axios.get(link).then((response) => {

                let avaliacoes = response.data.avaliacoes;
                let next_key = $("#avaliacao-next-key");

                for (let avaliacao_index in avaliacoes) {
                    let avaliacao = avaliacoes[avaliacao_index];

                    let html = `<div class='avaliacao-${next_key.text()}'>
                                    <span><b>${avaliacao.nome}</b></span>
                                    <br>
                                    ${ServicoCtrl.desenharEstrelas(avaliacao.nota)}
                                    <br>`;
                    if (avaliacao.avaliacao.length <= 280) {
                        html += `<p style='text-align: justify;'>${avaliacao.avaliacao}</p>`
                    } else {
                        html += `<p id='avaliacao-resumo-${next_key.text()}' style='text-align: justify; margin-bottom: 0px;'> ${avaliacao.avaliacao.substr(0, 280)}...</p>
                                                 <p id='avaliacao-completo-${next_key.text()}' style='text-align: justify; display: none;'> ${avaliacao.avaliacao} </p>
                                                 <a id='ver-mais-avaliacao-${next_key.text()}' key='${next_key.text()}' class='btn-ver-mais' href='#'>Ver mais<a/>
                                                 <a style='display: none;' id='ver-menos-avaliacao-${next_key.text()}' key='${next_key.text()}' class='btn-ver-menos' href='#'>Ver menos<a/>`;
                    }
                    html += `<p>${avaliacao.data}</p>`;

                    let section = $("#section-avaliacoes");
                    section.append(html);

                    ServicoCtrl.verMaisAvaliacao();
                    ServicoCtrl.verMenosAvaliacao();

                    next_key.text(parseInt(next_key.text()) + 1);
                }
            });
        });
    },

    // Função responsavel por desenhar as estrelas no site quando as avaliações forem carregadas pelo botão
    desenharEstrelas: (quantidade) => {

        let codigo_star = $("#avaliacao-star").text();
        let html = '';

        for (let i = 1; i <= 5; i++) {
            if (i <= quantidade) {
                html += `<img id='star1' style='padding:5px;' src='${codigo_star}'>`;
            }
        }

        return html;
    },
};
