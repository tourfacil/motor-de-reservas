let VendaInternaCtrl = {

    servico: [],
    servico_informacao: [],
    data_selecionada: [],
    variacoes: [],

    init: (page) => {

        Plugins.loadLibDateRanger();

        VendaInternaCtrl.onSelectServico();
        VendaInternaCtrl.onSelectStatusPagamento();
        VendaInternaCtrl.onClickBotaoCadastrarCliente();
        VendaInternaCtrl.adicionarReserva();
        VendaInternaCtrl.consultarEmailTitularNoBanco();
        VendaInternaCtrl.loadAcompanhantesSeTitularPreenchido();
        VendaInternaCtrl.setCupomDesconto();
        VendaInternaCtrl.unsetCupomDesconto();
    },

    // Quando o usuário clica no + para aumentar o número de pessoas
    onAddPessoa: () => {

        $(".i-adicionar").on('click', (event) => {

            let valor = event.target.getAttribute('valor');

            // Seta uma variavel para controlar a disponibilidade
            if(VendaInternaCtrl.data_selecionada.disponibilidade_usada == undefined) {
                VendaInternaCtrl.data_selecionada.disponibilidade_usada = 0;
            }

            // Verifica se tal data ainda tem disponibilidade
            if(VendaInternaCtrl.data_selecionada.disponibilidade - VendaInternaCtrl.data_selecionada.disponibilidade_usada <= 0) {
                alert('Sem disponibilidade');
                return;
            }

            // Aumenta o valor interno das qauntidades
            if(VendaInternaCtrl.variacoes[valor].quantidade == undefined) {
                VendaInternaCtrl.variacoes[valor].quantidade = 1;
            } else {
                VendaInternaCtrl.variacoes[valor].quantidade++;
            }

            // Verifica se consome bloqueio. Caso sim, ele registra para controlar disponibilidade
            if(VendaInternaCtrl.variacoes[valor].bloqueio == "SIM") {
                VendaInternaCtrl.data_selecionada.disponibilidade_usada++;
            }

            // Atualiza o texto do contador
            let contador = $(`#contador-${valor}`);
            contador.text(VendaInternaCtrl.variacoes[valor].quantidade);

            VendaInternaCtrl.setValorTotal();
        })
    },

    // Quando o usuário clica no - para remover clientes
    onRemovePessoa: () => {
        $(".i-remover").on('click', (event) => {

            let valor = event.target.getAttribute('valor');

            // Seta uma variavel para controlar a disponibilidade
            if(VendaInternaCtrl.data_selecionada.disponibilidade_usada == undefined) {
                VendaInternaCtrl.data_selecionada.disponibilidade_usada = 0;
            }

            // Verifica se tal data ainda tem disponibilidade
            if(VendaInternaCtrl.variacoes[valor].quantidade == 0) {
                return;
            }

            // Deiminui o valor interno das qauntidades
            if(VendaInternaCtrl.variacoes[valor].quantidade == undefined) {
                VendaInternaCtrl.variacoes[valor].quantidade = 0;
            } else {
                VendaInternaCtrl.variacoes[valor].quantidade--;
            }

            // Verifica se consome bloqueio. Caso sim, ele registra para controlar disponibilidade
            if(VendaInternaCtrl.variacoes[valor].bloqueio == "SIM") {
                VendaInternaCtrl.data_selecionada.disponibilidade_usada--;
            }

            // Atualiza o texto do contador
            let contador = $(`#contador-${valor}`);
            contador.text(VendaInternaCtrl.variacoes[valor].quantidade);

            VendaInternaCtrl.setValorTotal();
        })

    },

    // Quando selecionar o serviço o sistema consulta o mesmo e puxa as datas disponiveis.

    onSelectServico: () => {
        $('#select-servico').on('change', (event) => {

            App.loader.show()

            VendaInternaCtrl.clearTelaVariacoes();
            VendaInternaCtrl.clearTelaValorTotal();
            VendaInternaCtrl.clearTelasInformacoes();

            let calendar = $("#new_date").val('');

            let servico_id = event.target.value;

            let url_get_servico = `${event.target.getAttribute("data-url")}?servico_id=${servico_id}`;

            let response_servico = axios.get(url_get_servico);

            response_servico.then((response_servico) => {

                VendaInternaCtrl.servico = response_servico.data.servico;

                url_get_servico = `${$('#new_date').attr('data-url')}?uuid=${VendaInternaCtrl.servico.uuid}`;

                let response_dates = axios.get(url_get_servico);

                response_dates.then((response_dates) => {

                    VendaInternaCtrl.dates_calendario = response_dates.data.events;
                    VendaInternaCtrl.dates_disponibilidade = response_dates.data.disponibilidade;
                    VendaInternaCtrl.servico_informacao = response_dates.data;

                    VendaInternaCtrl.setDatePicker(response_dates);

                    App.loader.hide();
                })
            })
        })
    },

    setDatePicker: (response) => {

        // Carrega a LIB do Datepicker


        // Seta o campo do calendario em uma variavel
        let calendar = $("#new_date");

        calendar.daterangepicker({
            minDate: VendaInternaCtrl.dataToIE(response['data']['events'][0]['date']),
            singleDatePicker: true,
            autoUpdateInput: false,
            isCustomDate: VendaInternaCtrl.filterDateCalendar,
            locale: {
                format: 'DD/MM/YYYY',
                daysOfWeek: ["Do", "2º", "3º", "4º", "5º", "6º", "Sá"],
                monthNames: ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"],
            }
        });

        calendar.on('apply.daterangepicker', VendaInternaCtrl.onSelectDateCalendar);
    },

    onSelectDateCalendar: (ev, picker) => {

        let id_nova_data = "";
        // Coloca a data no input
        $(ev.currentTarget).val(picker.startDate.format("DD/MM/YYYY"));
        // Data selecionada no calendario
        let data_selecionada = picker.startDate.format("YYYY-MM-DD");
        let length_disponibilidade = VendaInternaCtrl.dates_disponibilidade.length;
        // Recupera o id da data selecionada
        for(let i = 0; i < length_disponibilidade; i++) {
            let dados_data = VendaInternaCtrl.dates_disponibilidade[i];
            // Caso seja a mesma data recupera o ID da data
            if(dados_data['data'] === data_selecionada) {
                id_nova_data = dados_data['data_servico_id'];
                VendaInternaCtrl.data_selecionada = dados_data;
                VendaInternaCtrl.variacoes = dados_data.variacoes
            }
        }

        VendaInternaCtrl.setTelaVariacoes()
        VendaInternaCtrl.onAddPessoa();
        VendaInternaCtrl.onRemovePessoa();

        // Coloca o id da nova data no campo
        $("input[name='data_agenda_id']").val(id_nova_data);
    },

    setTelaVariacoes: () => {

        VendaInternaCtrl.clearTelaVariacoes();
        VendaInternaCtrl.setTelaValorTotal();

        let element_title = $('#variacoes-reserva-titulo').css('display', 'block')

        let variacoes = VendaInternaCtrl.data_selecionada.variacoes
        let container = $('#variacoes-reserva')

        for(let i = 0; i < variacoes.length; i++) {
            let variacao = variacoes[i]

            container.append(
                `
                <div class="row">
                    <div class="col-xl-8">
                        <label class="form-control-label" style="margin-bottom:0px;">${variacao.variacao} <small>${variacao.descricao}</small></label>
                        <br>
                        <label style="color: #097a27; font-weight: bold; font-size: large;">R$${variacao.valor_venda_brl}</label>
                    </div>
                    <div class="col" style="text-align:center;">
                        <span style="font-size: 25px; color: #2c304d;">
                            <i class="la la-minus add-remove-icon noselect i-remover" valor="${i}"></i>
                            <span class="noselect" id="contador-${i}">0</span>
                            <i class="la la-plus add-remove-icon noselect i-adicionar" valor="${i}"></i>
                        </span>
                    </div>
                </div>
                `
            )
        }
    },

    setTelaValorTotal: () => {
        let element = $("#inside-column-valor-total").css('display', 'block');
        let element_text = $("#valor_total").text("R$ 0,00");
    },

    clearTelaValorTotal: () => {
        let element = $("#inside-column-valor-total").css('display', 'none');
    },

    setValorTotal: () => {

        let valor_total = 0;

        for(let i = 0; i < VendaInternaCtrl.variacoes.length; i++) {

            if(VendaInternaCtrl.variacoes[i].quantidade != undefined) {
                valor_total += VendaInternaCtrl.variacoes[i].valor_venda * VendaInternaCtrl.variacoes[i].quantidade;
            }
        }

        let element = $('#valor_total')

        element.text(`${(valor_total).toLocaleString('pt-BR', {style: 'currency', currency: 'BRL'})}`)
    },

    clearTelaVariacoes: () => {
        let container = $('#variacoes-reserva')
        container.empty()

        let element_title = $('#variacoes-reserva-titulo').css('display', 'none')
    },

    filterDateCalendar: (date) => {
        let length = VendaInternaCtrl.dates_calendario.length;
        let data_calendario = date.format("YYYY-MM-DD") + " 00:00:00";

        for(let i = 0; i < length; i++) {
            let data = VendaInternaCtrl.dates_calendario[i]['date'];
            if(data === data_calendario) return "";
        }

        return "disabled";
    },

    onSelectStatusPagamento: () => {
        $("#select-pagamento").on('change', (event) => {

            let element = $("#select-pagamento");
            let botao_link = $("#botao-adicionar-servico");
            let botao_clientes = $("#botao-cadastrar-cliente");

            if(element.val() == "PAGO") {

                // Desabilitar o botão de GERAR LINK
                botao_link.css('display', 'none');

                // Habilitar botão de cadastrar clientes
                botao_clientes.css('display', 'block');

            }
            else if(element.val() == "PENDENTE") {

                // Desabilitar o botao de cadastrar clientes
                botao_clientes.css('display', 'none');

                // Habilitar o botão de GERAR LINK
                botao_link.css('display', 'block');

                VendaInternaCtrl.clearTelaCadastrarClientes();
            }

        });
    },

    clearTelaCadastrarClientes: () => {
        $("#botao-cadastrar-cliente-post").css("display", "none");
        $("#cad-cli").css('display', 'none');
        $("#container-clientes").empty()
    },

    clearTelaCadastrarTitular: () => {
        $("#cad-titular").css('display', 'none');
    },

    clearTelaCadastrarCamposAdicionais: () => {
        $("#container-row-campos-adicionais").css('display', 'none');
        $("#container-campos-adicionais").empty();
    },

    clearTelasInformacoes: () => {
        VendaInternaCtrl.clearTelaCadastrarClientes();
        VendaInternaCtrl.clearTelaCadastrarTitular();
        VendaInternaCtrl.clearTelaCadastrarCamposAdicionais();
    },

    // Retorna o número de clientes que o usuário selecionou
    getQuantidadeClientesSelecionados: () => {

        // Quantidade para armazenar os totais
        let quantidade = 0;

        // Busca as variações
        let variacoes = VendaInternaCtrl.variacoes;

        // Percorre todas as variações
        for(let i = 0; i < variacoes.length; i++) {

            // Coloca a variação em que estamos no for no momento em uma variavel
            let variacao = variacoes[i];

            // Verifica se esta variação tem quantidade alocada no momento
            if(variacao.quantidade != undefined) {

                // Soma as quantidades alocadas ao total
                quantidade += variacao.quantidade;
            }
        }

        return quantidade;
    },

    // Adicionar clientes que devem ser cadastrados
    onClickBotaoCadastrarCliente: () => {

        // Aciona a impressão de clientes no momento em que se clicar no botão de cadastrar clientes
        $("#botao-cadastrar-cliente").on('click', (event) => {

            // Verifica se o número de clientes selecionados é maior que 0
            if(VendaInternaCtrl.getQuantidadeClientesSelecionados() <= 0) {
                return alert("Selecione a quantidade de clientes");
            }

            // Caso ja tivesse campos de clientes impressos na tela, ele limpa
            VendaInternaCtrl.clearTelaCadastrarClientes();

            // Faz o campo de pagamento aparecer
            $('#cad-pagamento').css('display', 'block');

            // Faz o campo de titular aparecer
            $("#cad-titular").css('display', 'block');

            // Verifica se este serviço precisa de cadastro de clientes
            if(VendaInternaCtrl.servico.info_clientes == 'SOLICITA_INFO_CLIENTES') {

                // Variavel para guardar as variações
                let variacoes = VendaInternaCtrl.variacoes;
                // Variavel para guardar o container onde vamos imprimir os campos
                let container = $("#container-clientes")

                // Faz o container de clientes aparecer
                $("#cad-cli").css('display', 'block');

                // Caso tenha campos adicionais, ele imprime um botão para continuar o cadastro
                if(VendaInternaCtrl.servico_informacao.campos_adicionais.length == 0) {
                    $("#botao-cadastrar-cliente-post").css("display", "block");
                }

                // Contador para imprimir os campos
                let contador = 0;

                // Passa por todas as variações de idade
                for(let i = 0; i < variacoes.length; i++) {
                    let variacao = variacoes[i];

                    // Se a variação estiver vazia, constara como undefined.
                    // Apenas uma proteção para não bugar
                    if(variacao.quantidade != undefined) {

                        // Imprime o número de campos necessário para essa variação
                        for(let j = 0; j < variacao.quantidade; j++) {
                            container.append(`
                                <div class="row">
                                    <strong class="info-terminal">${contador + 1}° Acompanhante - ${variacao.variacao}</strong>
                                </div>
                                <div class="row list-acompanhantes">
                                    <div class="col-xl-4 mb-3">
                                        <label for="nome_acop_${contador}" class="form-control-label">Nome completo</label>
                                        <div class="input-group">
                                            <span class="input-group-addon addon-secondary">
                                                <i class="la la-user"></i>
                                            </span>
                                            <input id="nome_acop_${contador}" type="text" class="form-control disable-action" placeholder="Nome completo do acompanhante" required}}
                                                data-required data-min="5" title="Nome do acompanhante" data-auto-capitalize name="nome" autocomplete="off" data-list="nomes_acom" data-callback="nome">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>

                                    <div class="col mb-3">
                                        <label for="documento_acop_${contador}" class="form-control-label">Núm. documento </label>
                                        <div class="input-group">
                                            <span class="input-group-addon addon-secondary">
                                                <i class="la la-file-text"></i>
                                            </span>
                                            <input id="documento_acop_${contador}" type="text" class="form-control disable-action" placeholder="CPF ou RG do acompanhante" required
                                                    data-required data-min="1" title="Documento do acompanhante" name="documento"  autocomplete="off" data-list="doc_acom" data-callback="documento">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>

                                    <div class="col mb-3">
                                        <label for="nascimento_acop_${contador}" class="form-control-label">Nascimento</label>
                                        <div class="input-group">
                                            <span class="input-group-addon addon-secondary">
                                                <i class="la la-calendar"></i>
                                            </span>
                                            <input id="nascimento_acop_${contador}" type="tel" class="form-control vanillaMask disable-action" placeholder="DD/MM/AAAA" required data-mask="date"
                                                    data-required data-min="5" title="Nascimento do acompanhante" name="nascimento"  autocomplete="off"  data-list="data_acom" data-callback="nascimento">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>
                            `);
                            contador++;
                        }
                    }

                    // Mostra o campo de clientes (Scrolla a página para baixo)
                    window.location.href = "#botao-cadastrar-cliente"

                }

                // Aplica mascara nos campos recem impressos.
                App.vanillaMask();
            } else {

                // Caso não tenha campos adicionais. Será impresso o botão de finalizar no titular
                if(VendaInternaCtrl.servico_informacao.campos_adicionais.length == 0) {
                    $('#container-botao-cadastrar-titular-post').css('display', 'block');
                }
            }

            // Imprime o container de campos adicionais
            VendaInternaCtrl.setTelaCamposAdicionais();
        })
    },

    setTelaCamposAdicionais: () => {

        let campos_adicionais = VendaInternaCtrl.servico_informacao.campos_adicionais;
        let container = $("#container-campos-adicionais");
        let contador = 0;
        let coluna = 0;

        // Caso não tenha campos adicionais só retorna
        if(campos_adicionais.length == 0) {
            return;
        }

        $("#container-row-campos-adicionais").css('display', 'block');

        for(let i = 0; i < campos_adicionais.length; i++) {

            container.append(`
                <div class="form-group row mb-3" id="campos-coluna-${coluna}">
                </div>
            `)

            let container_coluna = $(`#campos-coluna-${coluna}`);

            for(let j = 0; j < 2; j++) {

                let campo_adicional = campos_adicionais[contador];

                container_coluna.append(`
                    <div class="col-auto mb-3">
                        <label for="adicional_${contador}" class="form-control-label">${campo_adicional.campo}</label>
                        <input id="campo_${contador}_texto" titulo="${campo_adicional.campo}" type="text" class="form-control disable-action" placeholder="${campo_adicional.placeholder}" data-min="2">
                        <input id="campo_${contador}_campo_adicional_id" type="hidden" name="adicionais[][campo_adicional_id]" value="${campo_adicional.id}">
                        <div class="invalid-feedback"></div>
                    </div>
                `)

                contador++;
            }
            container_coluna++;
        }
    },

    // Função responsavel por submeter e adicionar ao carrinho
    adicionarReserva: () => {

        $("#botao-adicionar-servico").on('click', (event) => {
            let dados = VendaInternaCtrl;
            let requisicao = {
                servico: dados.servico,
                agenda: dados.data_selecionada,
                variacoes: dados.variacoes,
                tipo_pagamento: $("#select-pagamento").val()
            };

            let link_req = $("#link-carrinho-adicionar").attr('link')

            axios.post(link_req, requisicao).then((req) => {
                window.location.reload()
            })
        });

        $("#botao-cadastrar-cliente-post").on('click', (event) => {

            VendaInternaCtrl.postAddReserva();

        });

        $("#botao-cadastrar-campo-adicional-post").on('click', (event) => {

            VendaInternaCtrl.postAddReserva();

        });

        $("#botao-cadastrar-titular-post").on('click', (event) => {

            VendaInternaCtrl.postAddReserva();

        });
    },

    postAddReserva: () => {

        if(VendaInternaCtrl.validarPagamento() == false) {
            return;
        }

        if(VendaInternaCtrl.validarTitularPedido() == false) {
            return;
        }

        if(VendaInternaCtrl.validarClientesReserva() == false) {
            return;
        }

        if(VendaInternaCtrl.validarCamposAdicionais() == false) {
            return;
        }

        // Faz aparecer um loader
        App.loader.show();

        // Monta um objeto para a requisição
        let dados = VendaInternaCtrl;
        let requisicao = {
            servico: dados.servico,
            agenda: dados.data_selecionada,
            variacoes: dados.variacoes,
            tipo_pagamento: $("#select-pagamento").val()
        };

        // Busca o link para adicionar no carrinho
        let link_req = $("#link-carrinho-adicionar").attr('link')

        // Contador e lista para salvar os acompanhantes
        let contador = 0;
        let acompanhantes = [];

        // Verifica se o serviço precisa de cadastro de clientes
        if(VendaInternaCtrl.servico.info_clientes == 'SOLICITA_INFO_CLIENTES') {

            // Passa por todas as variações
            for(let i = 0; i < dados.variacoes.length; i++) {

                let variacao = dados.variacoes[i];

                // Verifica se a quantidade da variação não é undefined
                if(variacao.quantidade != undefined) {

                    // Passa dentro da variação o numero de clientes que devem ser buscados nos campos
                    for(let j = 0; j < variacao.quantidade; j++) {

                        // Busca os valores no campo
                        let nome = $(`#nome_acop_${contador}`).val();
                        let documento = $(`#documento_acop_${contador}`).val();
                        let nascimento = $(`#nascimento_acop_${contador}`).val();

                        // Puxa o acompanhante para dentro da lista de acompanhantes
                        acompanhantes.push({
                            nome: nome,
                            documento: documento,
                            nascimento: nascimento,
                            variacao_servico_id: variacao.variacao_id,
                        })

                        contador++
                    }
                }
            }
        }

        let titular_email = $('#titular-email').val();

        DadosFactory.saveDadosAcompanhantes(acompanhantes, titular_email);

        // Método que busca busca os dados adicionais caso tenha
        let campos_adicionais = [];
        for(let i = 0; i < VendaInternaCtrl.servico_informacao.campos_adicionais.length; i++) {

            let campo_adicional = {
                campo_adicional_servico_id: $(`#campo_${i}_campo_adicional_id`).val(),
                informacao: $(`#campo_${i}_texto`).val(),
            };

            campos_adicionais.push(campo_adicional);
        }

        // Recupera dados do titular
        let titular_pedido = {
            nome: $("#titular-nome").val(),
            email: $("#titular-email").val(),
            telefone: $("#titular-telefone").val(),
            documento: $("#titular-documento").val(),
            nascimento: $("#titular-nascimento").val(),
            endereco: $("#titular-endereco").val(),
        }

        let pagamento = {
            meio_pagamento: $('#select-meio-pagamento').val(),
            metodo_pagamento: $('#select-metodo-pagamento').val()
        }

        // Coloca os todos os dados necessários dentro do array de requisição
        requisicao.titular_pedido = titular_pedido;
        requisicao.acompanhantes = acompanhantes;
        requisicao.campos_adicionais = campos_adicionais;
        requisicao.pagamento = pagamento;


        axios.post(link_req, requisicao).then((req) => {
            window.location.reload()
        })
    },

    validarTitularPedido: () => {

        // Recupera dados do titular
        let titular = {
            nome: $("#titular-nome").val(),
            email: $("#titular-email").val(),
            telefone: $("#titular-telefone").val(),
            documento: $("#titular-documento").val(),
            nascimento: $("#titular-nascimento").val(),
            endereco: $("#titular-endereco").val(),
        }

        if(titular.nome.length < 5) {
            swal('Nome do titular inválido', 'O nome do titular deve conter pelo menos 5 caracteres.', 'warning');
            return false;
        }

        if(App.isMail(titular.email) == false) {
            swal('E-mail do titular inválido', 'O e-mail do titular deve ser válido.', 'warning');
            return false;
        }

        if(titular.telefone.length != 16) {
            swal('Telefone do titular inválido', 'O telefone do titular deve ser válido.', 'warning');
            return false;
        }

        if(titular.documento.length != 14) {
            swal('Documento do titular inválido', 'O documento do titular deve ser válido.', 'warning');
            return false;
        }

        if(titular.nascimento.length != 10 || !App.isValidDate(titular.nascimento)) {
            swal('Data de nascimento do titular inválida', 'A data de nascimento do titular deve ser válida.', 'warning');
            return false;
        }

        // Comentado devido a não ser necessário informar o endereço para a venda interna
        // if(titular.endereco.length < 5) {
        //     swal('Endereço do titular inválido', 'O endereço do titular deve ser válido.', 'warning');
        //     return false;
        // }

    },

    validarClientesReserva: () => {
        let dados = VendaInternaCtrl;

        // Caso a reserva não tenha clientes para preencher. Retorna true
        if(dados.servico.info_clientes != "SOLICITA_INFO_CLIENTES") {
            return ;
        }

        let contador = 0;

         // Passa por todas as variações
         for(let i = 0; i < dados.variacoes.length; i++) {

            let variacao = dados.variacoes[i];

            // Verifica se a quantidade da variação não é undefined
            if(variacao.quantidade != undefined) {

                // Passa dentro da variação o numero de clientes que devem ser buscados nos campos
                for(let j = 0; j < variacao.quantidade; j++) {

                    // Busca os valores no campo
                    let nome = $(`#nome_acop_${contador}`).val();
                    let documento = $(`#documento_acop_${contador}`).val();
                    let nascimento = $(`#nascimento_acop_${contador}`).val();

                    if(nome.length < 5) {
                        swal('Nome do cliente inválido', `O nome do cliente ${contador + 1} deve conter pelo menos 5 caracteres.`, 'warning');
                        return false;
                    }

                    if(documento.length < 5) {
                        swal('Documento do cliente inválido', `O documento do cliente ${contador + 1} deve conter pelo menos 5 caracteres.`, 'warning');
                        return false;
                    }

                    if(nascimento.length != 10 || App.isValidDate(nascimento) == false) {
                        swal('Data de nascimento do cliente inválida', `A data de nascimento do cliente ${contador + 1} deve ser válida.`, 'warning');
                        return false;
                    }

                    contador++;
                }
            }
        }

        return true;
    },

    validarCamposAdicionais: () => {

        for(let i = 0; i < VendaInternaCtrl.servico_informacao.campos_adicionais.length; i++) {
            let elemento = $(`#campo_${i}_texto`);
            let valor = elemento.val();
            let nome_campo = elemento.attr('titulo');

            if(valor.length < 4) {
                swal(`Campo adicional inválido`, `O(A) ${nome_campo} deve conter pelo menos 4 caracteres.`, 'warning');
                return false;
            }

        }

        return true;
    },

    validarPagamento: () => {

        let meio_pagamento = $('#select-meio-pagamento').val();
        let metodo_pagamento = $('#select-metodo-pagamento').val();

        if(meio_pagamento == '') {
            swal('Meio de pagamento inválido', 'Selecione o meio de pagamento do pedido', 'warning');
            return false;
        }

        if(metodo_pagamento == '') {
            swal('Método de pagamento inválido', 'Selecione o método de pagamento do pedido', 'warning');
            return false;
        }
    },

    consultarEmailTitularNoBanco: () => {
        $('#titular-email').on('blur', (event) => {

            App.loader.show()

            let link_req = $("#link-consultar-email").attr('link');
            let email = $('#titular-email').val();

            let req = {
                email: email
            };

            axios.post(link_req, req).then((response) => {

                let cliente = response.data.cliente;

                if(cliente != null) {
                    let campo_nome = $("#titular-nome");
                    let campo_telefone = $("#titular-telefone");
                    let campo_documento = $("#titular-documento");
                    let campo_nascimento = $("#titular-nascimento");
                    let campo_endereco = $("#titular-endereco");

                    VendaInternaCtrl.setEdicaoCamposTitular(true);

                    campo_nome.val(cliente.nome);
                    campo_telefone.val(cliente.telefone);
                    campo_documento.val(cliente.cpf);

                    let date = new Date(cliente.nascimento);
                    let date_text = VendaInternaCtrl.formatarDateJSParaTexto(date);
                    campo_nascimento.val(date_text);

                    DadosFactory.loadAcompanhantes(email);

                } else {
                    let campo_nascimento = $("#titular-nascimento");
                    VendaInternaCtrl.setEdicaoCamposTitular(false);
                }

                App.loader.hide();
            });
        });
    },

    setEdicaoCamposTitular: (status) => {
        let campo_nome = $("#titular-nome");
        let campo_telefone = $("#titular-telefone");
        let campo_documento = $("#titular-documento");
        let campo_nascimento = $("#titular-nascimento");
        let campo_endereco = $("#titular-endereco");

        campo_nome.prop('disabled', status);
        campo_telefone.prop('disabled', status);
        campo_documento.prop('disabled', status);
        campo_nascimento.prop('disabled', status);
        campo_endereco.prop('disabled', status);
    },

    setCupomDesconto: () => {

        $('#cupom-add').on('click', () => {
            let link = $('#link-set-cupom-desconto').attr('link');
            let cupom = $('#cupom').val();

            if(cupom == '') {
                return swal('Informe o código do cupom', 'Para adicionar o cupom, insira um valor válido', 'warning');
            }

            axios.post(link, {cupom: cupom}).then((response) =>{
                let data = response.data;
                let status = data.status;
                let cupom = data.cupom;

                if(status == false) {
                    return swal('Cupom inválido', 'O cupom informado é inválido ou não esta mais disponivel.', 'warning');
                }

                return swal('Cupom aplicado', `O cupom ${cupom.nome_interno} foi aplicado!`, 'success').then(() => {
                    window.location.reload();
                });
            })
        });
    },

    unsetCupomDesconto: () => {
        $('#cupom-remove').on('click', () => {
            let link = $('#link-unset-cupom-desconto').attr('link');

            axios.post(link).then((response) => {
                window.location.reload();
            });
        })
    },

    // Quando a página carrega essa função verifica se o e-mail do titular ja vier preenchido (Em caso de montagem de multiplas reservas)
    // Caso vier. Ele carrega os auto completes
    loadAcompanhantesSeTitularPreenchido: () => {
        let email = $(`#titular-email`).val();

        if(email != undefined || email != '') {

            DadosFactory.loadAcompanhantes(email);
        }
    },

    formatarDateJSParaTexto: (date) => {
        let dia = date.getDate();
        let mes = date.getMonth() + 1;
        let ano = date.getFullYear();

        if(dia < 10) {
            dia = `0${dia}`;
        }
        if(mes < 10) {
            mes = `0${mes}`;
        }

        return `${dia}/${mes}/${ano}`;
    },

    /** BUG IE, Safari, iPhone, iPad e etc */
    dataToIE: (date) => {
        let data = date.split(' ');
        data = data[0].split('-');
        return new Date(data[0], (data[1] - 1), data[2], 0, 0, 0, 0);
    },
};
