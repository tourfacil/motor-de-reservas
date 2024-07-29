let AgendaCtrl = {

    // Inicialização do controller
    init: (page) => {

        // Desabilita os campos das outras tabs no cadastro
        AgendaCtrl.onChangeTabs();

        // Carrega as datas do calendario
        AgendaCtrl.loadCalendarDates(page.getAttribute("data-calendar"));

        // Setup dos datepickers
        AgendaCtrl.setupDatePickers();

        // Change no select para opção de edição das datas
        AgendaCtrl.onChangeSelectEditDates();

        // Click no botao para editar a substituicao agenda
        AgendaCtrl.onClickButtonEditarSubstituicao();

        // Click no botao para deletar a substituicao
        AgendaCtrl.onClickButtonDeleteSubstituicao();
    },

    // Click no botao para editar a substituicao agenda
    onClickButtonEditarSubstituicao: () => {
        $("[data-action='edit-substituicao']").on('click', (event) => {
            event.preventDefault();
            let $this = $(event.currentTarget);
            let $modal = $("#edit-alteracao-agenda");
            // Mosta o loader
            App.loader.show();
            // Recupera as informacoes da data
            axios.get($this.attr('href')).then((response) => {
                response = response['data'];
                // Reseta o formulario
                $modal.find("form").trigger('reset');
                // Coloca os valores no form
                $modal.find("input#edit_alteracao_from").val(window.App.formataValor(response['from'], 2));
                $modal.find("input#edit_alteracao_to").val(window.App.formataValor(response['to'], 2));
                $modal.find("input#edit_alteracao_agenda").val(response['tipo_alteracao']);
                $modal.find("input[name='tipo_alteracao']").val(response['tipo']);
                $modal.find("input[name='index']").val(response['from']);
                // Retira o loader
                App.loader.hide();
                // Abre a modal
                $modal.modal();
            });
        });
    },

    // Click no botao para deletar a substituicao
    onClickButtonDeleteSubstituicao: () => {
        $("[data-action='delete_substituicao']").on('click', (event) => {
            event.preventDefault();
            // Recupera o input de delete
            let $input = $("input[name='delete_substituicao']");
            // Coloca o valor no input para deletar
            $input.val("on");
            // Envia o formulario
            $input.parents("form").trigger('submit');
        });
    },

    // Desabilita os campos das outras tabs no cadastro
    onChangeTabs: () => {
        $("#tabs_agenda li a").on('click', (event) => {
            let $this = $(event.currentTarget),
                $tabs = $("ul#tabs_agenda li a:not([href='" + $this.attr('href') + "'])"),
                length = $tabs.length;
            // Funcao para desativar os inputs
            let toggleInputs = ($tab, toggle) => {
                let $inputs = $($tab.attr('href')).find("input, select"),
                    input_length = $inputs.length;
                // Percorre os inputs
                for(let i = 0; i < input_length; i++) {
                    let $input = $($inputs[i]);
                    // Input de busca dentro do boostrap select
                    if($input.attr('aria-label') === "Search") return;
                    // Se é para desativar
                    if(toggle) {
                        $input.attr("disabled", true);
                        $input.removeAttr("required");
                        $input.removeAttr("data-required");
                    } else {
                        $input.removeAttr("disabled");
                        $input.attr("required", true);
                        $input.attr("data-required", true);
                    }

                    // Caso for select
                    if($input.hasClass('boostrap-select-custom')) {
                        $input.selectpicker('refresh');
                    }
                }
            };

            // Habilita os campos da tab selecionada
            toggleInputs($this, false);

            // Percorre as tabs
            for(let i = 0; i < length; i++) {
                let $tab = $($tabs[i]);
                // Desativa os inputs das tabs
                toggleInputs($tab, true);
            }
        });
    },

    // Carrega as datas do calendario
    loadCalendarDates: (url) => {
        if(url) {
            // Recupera as datas
            axios.get(url).then((response) => {
                response = response.data;
                AgendaCtrl.setupCalendar(response);
            });
        }
    },

    // Configura as datas no calendario
    setupCalendar: (events_date) => {
        // Carrega os scripts do calendario
        Plugins.loadLibCalendar(() => {
            // Remove o loader
            $(".loader-calendar").addClass("hide");
            // Configura o calendario
            $("#service-calendar").fullCalendar({
                defaultView: "month",
                monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
                dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'],
                header: {left: "title", right: "today prev,next"},
                buttonText: {today: "Hoje"},
                events: events_date,
                eventRender: AgendaCtrl.onRenderDateCalendar,
                eventClick: AgendaCtrl.onClickDateCalendar,
            })
        });
    },

    // Configura os pickers de data
    setupDatePickers: () => {
        Plugins.loadLibDateRanger(() => {
            // Configurações padrões
            let config = {
                minDate: new Date(),
                singleDatePicker: true,
                autoUpdateInput: false,
                locale: {
                    format: 'DD/MM/YYYY',
                    daysOfWeek: ["Do", "2º", "3º", "4º", "5º", "6º", "Sá"],
                    monthNames: ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"],
                }
            };
            // Calendarios
            let $calendarios = $(".datepicker"),
                length = $calendarios.length;
            // Percorre os calendarios
            for(let i = 0; i < length; i++) {
                let $calendar = $($calendarios[i]),
                    $range = $($calendar.attr("data-range"));
                // Data de inicio
                $calendar.daterangepicker(config);
                // Data de termino
                $range.daterangepicker(config);
                // Evento ao selecionar a data
                $calendar.on('apply.daterangepicker', (ev, picker) => {
                    // Coloca a data no input
                    $(ev.currentTarget).val(picker.startDate.format(config.locale.format));
                    // Limita o calendario a data selecionada
                    $range.data('daterangepicker').setMinDate(picker.startDate);
                });
                // Evento ao selecionar a data
                $range.on('apply.daterangepicker', (ev, picker) => {
                    // Coloca a data no input
                    $(ev.currentTarget).val(picker.startDate.format(config.locale.format));
                    // Limita o calendario a data selecionada
                    $calendar.data('daterangepicker').setMaxDate(picker.startDate);
                });
            }
        });
    },

    /** Change no select para opção de edição das datas */
    onChangeSelectEditDates: () => {
        $("select#select_edit_option").on('change', (event) => {
            let $this = $(event.currentTarget),
                $div = $("[data-option='" + $this.val() + "']"),
                $fields = $(".fields-edit .field").not($div);
            // Desabilita os outros campos
            $fields.find("input").removeAttr('required').removeAttr('data-required').attr('disabled', true);
            $fields.addClass('hide');
            // Habilita mostra o campo e habilita
            $div.find("input").removeAttr('disabled').attr('required', true).attr('data-required', true);
            $div.removeClass('hide');
        });
    },

    // Função chamado pelo calendario ao criar as infos nas datas
    onRenderDateCalendar: (c, b,f) => {
        let month = f.title.replace(/[0-9]/g, '').trim();
        let html = `<p class='fc-text mt-0'>Consumido <span class="pull-right">${c.consumido}</span></p>`;
        html += `<p class='fc-text'>Disponível <span class="pull-right">${c.disponivel}</span></p>`;
        html += `<p class='fc-text mt-3'>Tarifa <span class="pull-right">${c.valor_net}</span></p>`;
        //html += `<p class='fc-text'>Venda <span class="pull-right">${c.valor_venda}</span></p>`;
        html += `<p class='fc-info-date'>${c.title.replace('Data ', '')}</p>`;
        b.find(".fc-content").append(html);
        if(month !== c.mes) {
            b.removeClass('fc-bg-red-cad fc-bg-orange-cad fc-bg-green-cad').addClass("fc-bg-other-month");
        }
    },

    /**
     * Evento quando clica em uma data
     * @param event
     */
    onClickDateCalendar: (event) => {
        let $modal = $("#modal-view-event");
        // Mosta o loader
        App.loader.show();
        // Recupera as informacoes da data
        axios.get(event['view']).then((response) => {
            response = response['data'];
            // Coloca os valores da data
            $modal.find(".event-title").html(response['title']);
            $modal.find("#view_date_net").val(response['valor_net']);
            $modal.find("#view_date_venda").val(response['valor_venda']);
            $modal.find("#view_date_qtd").val(response['disponivel']);
            $modal.find("[name='data_id']").val(response['data_id']);
            // html das tabs
            let tab_html = AgendaCtrl.createHtmlTabs(response['servicos']);
            // Coloca o html das tabs
            $(".list-valores-servicos").html(tab_html);
            // Ativa as tavs
            let $tabs = $("#data_servicos");
            $tabs.tab();
            $tabs.find("li:first-child a").tab('show');
            // Esconde o loader
            App.loader.hide();
            // Abre a modal
            $modal.modal();
        });
    },

    // Cria o html das tabs no detalhe do serviço
    createHtmlTabs: (variacoes) => {
        let html = "<div class='tab-content'>",
            length = variacoes.length;
        // cabeçalho das tabs
        let html_tab_header = `<ul id="data_servicos" class="nav nav-tabs nav-tab-header">`;
        // Percorre cada serviço para montar as html das tabs
        for(let i = 0; i < length; i++) {
            let variacao = variacoes[i],
                length_var = variacao['variacoes'].length;
            let html_variacoes = "";
            // Cria o HTML das variacoes
            for(let j = 0; j < length_var; j++) {
                let variacao_servico = variacao['variacoes'][j];
                html_variacoes +=
                    `<tr>
                        <td>${variacao_servico['variacao']} <sup>${variacao_servico['comissao']}%</small></td>
                        <td class="text-right">${variacao_servico['valor_net']}</td>
                        <td class="text-right">${variacao_servico['valor_venda']}</td>
                    </tr>`;
            }
            // Tabs
            html_tab_header +=
                `<li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#serv_${variacao.servico_id}">${variacao.nome_tab}</a>
                </li>`;
            // Html de cada servico
            html +=
                `<div class="tab-pane" id="serv_${variacao.servico_id}">
                    <div class="servico">
                        <p class="header-table m-0">${variacao.servico}</p>
                        <div class="table-responsive">
                            <table class="table table-border-bottom mb-0">
                                <thead>
                                <tr>
                                    <th class="pt-2 pb-2">Variação</th>
                                    <th class="pt-2 pb-2 text-right">Tarifa net</th>
                                    <th class="pt-2 pb-2 text-right">R$ Venda</th>
                                </tr>
                                </thead>
                                <tbody>${html_variacoes}</tbody>
                            </table>
                        </div>
                    </div>
                </div>`

        }
        // Fecha a div
        html += "</div>";
        // Fecha a ul
        html_tab_header += "</ul>";
        // Junta os html
        html_tab_header += html;

        return html_tab_header;
    }
};
