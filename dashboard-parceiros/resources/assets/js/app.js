/**
 * First, we will load all of this project's Javascript utilities and other
 * dependencies. Then, we will be ready to develop a robust and powerful
 * application frontend using useful Laravel and JavaScript libraries.
 */

require('./bootstrap');

/** Load Plugins **/
window.Plugins = require('./plugins');

// Aplicação WEB
window.App = {
    _loadedDateRangePicker: false,
    // Inicializacao da aplicação
    init: () => {
        // Controllers
        App.initControllers();
        // Capitalize inputs
        App.capitalizeInput();
        // Bind Input validate
        App.bindValidateInput();
        // Validação dos formulários
        App.autoValidateForm();
        // Change no select para filtro de datas
        App.onChangeSelectFilterPeriodo();
        // Mascaras
        App.vanillaMask();
        // Modal com filtro para relatorio dos fornecedores
        App.filterRelatorioFornecedor();
        // Plugins
        Plugins.niceScroll();
        // Boostrap Select
        Plugins.boostrapSelect();
        // Datatables
        Plugins.dataTables();
        // Lazyloader
        Plugins.lazyLoad();
        // Save state tabs
        Plugins.saveStateTabs();
        // Esconde o loader
        App.loader.hide();
    },

    /** Inicia os controllers */
    initControllers: () => {
        let $pages_controller = $("[data-controller]"),
            length = $pages_controller.length;
        // Chama todos os controllers necessários
        for (let i = 0; i < length; i++) {
            let page = $pages_controller[i],
                controller = page.getAttribute('data-controller');
            // Inicia o controller
            if ($.isPlainObject(window[controller]) && $.isFunction(window[controller].init)) {
                window[controller].init(page);
            } else {
                console.error(`Nenhum controller definido como ${controller}`);
            }
        }
    },

    /** Loader */
    loader: {
        hide: () => {
            $("#preloader").addClass("hide")
        },
        show: () => {
            $("#preloader").removeClass("hide")
        },
    },

    /** BUG IE, Safari, iPhone, iPad e etc */
    dataToIE: (date) => {
        let data = date.split(' ');
        data = data[0].split('-');
        return new Date(data[0], (data[1] - 1), data[2], 0, 0, 0, 0);
    },

    /**
     * Mascaras usando o Vanilla Mask
     * mascaras pré definidas
     */
    vanillaMask: () => {
        let $input_money = document.querySelectorAll(".vanillaMaskMoney"),
            length_money = $input_money.length;
        // Mascaras para moedas
        for(let i = 0; i < length_money; i++) {
            VMasker($input_money[i]).maskMoney();
            // Fix Input and mak ok = R$ 1,50 Output = R$ R$ 0,150
            $($input_money[i]).on('blur', (event) => {
                let $this = $(event.currentTarget);
                let value = $this.val().trim();
                if(value.length) {
                    value = value.replace(".", "").replace(",", ".");
                    value = parseFloat(value).toFixed(2);
                    if (value) $this.val(VMasker.toMoney(value, {
                        precision: 2, separator: ',', delimiter: '.',
                    }));
                }
            });
        }
        // Mascaras para porcentagem até 100
        VMasker(document.querySelectorAll(".vanillaPorcentage")).maskMoney({
            separator: '.',
        });
        // Mascaras normais
        let $campos = $(".vanillaMask"),
            length = $campos.length,
            mascaras = {
                cpf: '999.999.999-99',
                cnpj: '99.999.999/9999-99',
                phone: '(99) 9 9999-9999',
                date: '99/99/9999',
                cartao: '9999 9999 9999 9999',
                cvv: '999',
                cep: '99999-999',
                porcent: '9.9',
                markup: '9.99999',
            };

        for (let i = 0; i < length; i++) {
            let $campo = $campos[i], mask_pattern = $campo.getAttribute('data-mask');
            if(typeof mascaras[mask_pattern] !== "undefined") {
                // Mascaras para telefone
                if(mask_pattern === "phone") {
                    // Função para telefones fixos e celular
                    function inputHandler(masks, max, event) {
                        let c = event.target;
                        let v = c.value.replace(/\D/g, '');
                        let m = c.value.length > max ? 1 : 0;
                        VMasker(c).unMask();
                        VMasker(c).maskPattern(masks[m]);
                        c.value = VMasker.toPattern(v, masks[m]);
                    }
                    // mascaras de telefone
                    let telMask = ['(99) 9999-9999', '(99) 9 9999-9999'];
                    // Bind para trocar as mascaras
                    $campo.addEventListener('input', inputHandler.bind(undefined, telMask, 14), false);
                } else {
                    // Max length no campo conforme o parttenrs
                    $campo.maxLength = mascaras[mask_pattern].length;
                    // Coloca a mascara
                    VMasker($campo).maskPattern(mascaras[mask_pattern]);
                }
            }
        }
    },

    /**
     * Valida o formulario
     * @param element
     * @returns {boolean}
     */
    validateForm: (element) => {
        let $this = $(element);
        let $fields = $this.find("[data-required]:not(:disabled)");
        let length = $fields.length;
        let erros = 0;
        for(let index = 0; index < length; index++) {
            let $element = $($fields[index]);
            let label = $element.attr('title');
            let value = $element.val().trim();
            let invalid_feedback = $element.siblings(".invalid-feedback");
            label = (label === null) ? $element.attr('placeholder').trim() : label.trim();
            // Separa os selects
            if($element.attr('type') !== undefined) {
                let minValue = parseInt($element.attr('data-min'));
                // Verifica se o campo não está em branco
                if(value.length === 0) {
                    invalid_feedback.html("O campo " + label.toLowerCase() + " não pode ser vazio!");
                    $element.addClass('invalid');
                    erros++;
                    continue;
                }
                // Verifica os campos do tipo email
                if($element.attr('type') === 'email' && (! App.isMail(value))) {
                    invalid_feedback.html("O e-mail deve ser um endereço de e-mail válido!");
                    $element.addClass('invalid');
                    erros++;
                    continue;
                }
                // Verifica se o campo esta com erro
                if($element.hasClass('has-danger')) {
                    let concat = ($element.attr('type') === 'email') ? "" : " por favor verifique!";
                    // Mensagem para data de nascimento
                    if($element.attr('data-mask') === "data") {
                        invalid_feedback.html("Formato da data de nascimento inválida. Por favor verifique! Formato aceito: DD/MM/AAAA");
                        $element.addClass('invalid');
                        erros++;
                        continue;
                    }
                }
                // Verifica se o campo tem os caracteres minimos
                if(minValue && value.length < minValue) {
                    invalid_feedback.html("O campo " + label.toLowerCase() + ", deve ter mais de " + minValue + " caracteres!");
                    $element.addClass('invalid');
                    erros++;
                }
            }
        }

        return (erros === 0);
    },

    /** Auto valida o formulario */
    autoValidateForm: () => {
        $("form[data-validate-post]").on("submit", (event) => {
            let $this = $(event.currentTarget);
            if(App.validateForm($this)) {
                App.loader.show();
            } else {
                event.preventDefault();
            }
        });

        // Formulário enviado via ajax
        $("form[data-validate-ajax]").on("submit", (event) => {
            event.preventDefault();
            let $this = $(event.currentTarget);
            // Valida o formulário
            if(App.validateForm($this)) {
                // Mostra o loader
                App.loader.show();
                // Dados do form
                let data = $this.serialize();
                // POST
                axios.post($this.attr('action'), data)
                    .then((response) => {
                        // Remove o loader
                        App.loader.hide();
                        // Caso deu ok retorno padrão
                        if(response['data']['action']['result']) {
                            swal(response['data']['action']['title'], response['data']['action']['message'], "success").then(() => {
                                // Mostra o loader
                                App.loader.show();
                                // Verifica se existe URL para redirecionamento
                                if(typeof response['data']['action']['url'] !== "undefined") {
                                    window.location.href = response['data']['action']['url'];
                                } else {
                                    // Recarrega a tela
                                    location.reload();
                                }
                            });
                        } else {
                            swal(response['data']['action']['title'], response['data']['action']['message'], "error");
                        }
                    })
                    .catch((error) => {App.captureErrorAjax(error)})
            }
        });
    },

    /** Bind on focus input validate */
    bindValidateInput: () => {
        // Remove a classe de erro nos inputs
        $(document).on("focus", "input.invalid", (event) => {
            $(event.currentTarget).removeClass('invalid valid');
        });
        // Remove a classe de erro nos selects
        $(document).on("change", "select.invalid", (event) => {
            $(event.currentTarget).removeClass('invalid valid');
        });
    },

    /**
     * Função para capturar os erros AJAX
     * @param error
     */
    captureErrorAjax: (error) => {
        // Remove o loader
        App.loader.hide();
        if (error.response) {
            // Caso falhe nas validacoes da requisição
            if(parseInt(error['response']['status']) === 422) {
                let errors = error['response']['data']['errors'];
                // Percorre os erros 422
                for (let erro in errors) {
                    if(errors.hasOwnProperty(erro)) {
                        let $input = $("[name='" + erro + "']");
                        let invalid_feedback = $input.siblings(".invalid-feedback");
                        // Notifica o usuário
                        App.noty.error(errors[erro][0]);
                        // Coloca o campo em formato de erro
                        if($input) {
                            $input.addClass('invalid');
                            invalid_feedback.html(errors[erro][0]);
                        }
                    }
                }
            } else {
                let message = error.response.data['message'] || "Erro na requisição!";
                swal("Op's erro " + error.response.status, message, "error");
            }
        } else {
            swal("Op's ocorreu um erro!", "Informe o pessoal do TI sobre este erro.", "error");
        }
    },

    /**
     * Formata valor
     * @param number
     * @param decimals
     * @param dec_point
     * @param thousands_sep
     * @returns {string}
     */
    formataValor: (number, decimals, dec_point, thousands_sep) => {
        number = (number+'').replace(',', '').replace(' ', '');
        let n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? '.' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? ',' : dec_point,
            s = '',
            toFixedFix = (n, prec) => {
                let k = Math.pow(10, prec);
                return '' + Math.round(n * k) / k;
            };
        // Fix for IE parseFloat(0.55).toFixed(0) = 0;
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    },

    /**
     * Retorna se o email á valido
     *
     * @param mail
     * @returns {boolean}
     */
    isMail: (mail) => {
        let regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(mail);
    },

    /** Autocapitalize Input */
    capitalizeInput: () => {

        let $inputs = document.querySelectorAll("input[data-auto-capitalize]"),
            length = $inputs.length,
            teclas_permitidas = [
                37, 38, 39, 40, 8, 35, 36, 46, 16, 17
            ];

        let ucfirst = (str) => {
            return str.replace(/(\b)([a-zA-Z])/,
                (firstLetter) => {
                    return firstLetter.toUpperCase();
                });
        };

        // Percorre todos os campos com o atributo
        for (let index = 0; index < length; index++) {
            // Bind do evento ao digitar
            $($inputs[index]).on("keyup", (event) => {

                // Teclas como backspace nao entra na funcao
                if(teclas_permitidas.indexOf(event.which) !== -1) {
                    return false;
                }

                // Teclas como backspace nao entra na funcao CrossBrowser
                if(teclas_permitidas.indexOf(event.keyCode) !== -1) {
                    return false;
                }

                let $this = event.target;
                let texto = $this.value;
                let $return = [];
                let ficar_minusculo = /^([dn]?[aeiou][s]?|em|com|para|nossa|nosso)$/i;
                let words = texto.toLowerCase();
                let ss = event.target.selectionStart;
                let se = event.target.selectionEnd;
                words = words.replace('  ', '').split(' ');

                // Guarda a primeira palavra com letra maiuscula
                $return.push(ucfirst(words[0]));

                // Deleta a primeira palavra
                delete words[0];

                // Verifica se existe uma segunda palavra
                if(words[1] !== undefined) {
                    // Percore as palavras
                    for(let key = 1; key < words.length; key++) {
                        // Se a palavra não bater com a expressão ele colca maiusculo
                        if(ficar_minusculo.exec(words[key]) == null) {
                            $return.push(ucfirst(words[key]));
                        } else {
                            $return.push(words[key]);
                        }
                    }
                }

                // Retorna o valor para o input
                if(words[1] !== undefined) {
                    $this.value = $return.join(' ');
                    $this.selectionStart = ss;
                    $this.selectionEnd = se;
                } else {
                    $this.value = $return;
                    $this.selectionStart = ss;
                    $this.selectionEnd = se;
                }
            });
        }
    },

    /** Cookie */
    cookie: {
        create: (name, value, days) => {
            let expires = "";
            if (days) {
                let date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = "; expires=" + date.toGMTString();
            }
            document.cookie = encodeURIComponent(name) + "=" + encodeURIComponent(value) + expires + "; path=/";
        },
        read: (name) => {
            let nameEQ = encodeURIComponent(name) + "=";
            let ca = document.cookie.split(';');
            for (let i = 0; i < ca.length; i++) {
                let c = ca[i];
                while (c.charAt(0) === ' ') c = c.substring(1, c.length);
                if (c.indexOf(nameEQ) === 0) return decodeURIComponent(c.substring(nameEQ.length, c.length));
            }
            return null;
        }
    },

    /** Notificação */
    noty: {
        /**
         * Notificação de erro
         * @param message
         */
        error: (message) => {
            return App.noty.notify(message, "error");
        },
        /**
         * Notificação de aviso
         * @param message
         */
        warning: (message) => {
            return App.noty.notify(message, "warning");
        },
        /**
         * Notificação de aviso
         * @param message
         */
        info: (message) => {
            return App.noty.notify(message, "info");
        },
        /**
         * Notificação de sucesso
         * @param message
         */
        sucesso: (message) => {
            return App.noty.notify(message, "success");
        },
        /**
         * Notificação
         * @param message
         * @param type
         * @param timeout
         */
        notify: (message, type, timeout = 3000) => {
            return new Noty({
                type: type,
                layout: "topRight",
                text: message,
                progressBar: true,
                timeout: timeout,
                animation:{open:"animated bounceInRight", close:"animated bounceOutRight"}
            }).show()
        }
    },

    /** Estados do Brasil */
    estados : {
        "AC": "Acre", "AL": "Alagoas", "AP": "Amapá", "AM": "Amazonas", "BA": "Bahia", "CE": "Ceará", "DF": "Distrito Federal", "ES": "Espírito Santo", "GO": "Goiás",
        "MA": "Maranhão", "MT": "Mato Grosso", "MS": "Mato Grosso do Sul", "MG": "Minas Gerais", "PA": "Pará", "PB": "Paraíba", "PR": "Paraná", "PE": "Pernambuco",
        "PI": "Piauí", "RJ": "Rio de Janeiro", "RN": "Rio Grande do Norte", "RS": "Rio Grande do Sul", "RO": "Rondônia", "RR": "Roraima", "SC": "Santa Catarina",
        "SP": "São Paulo", "SE": "Sergipe", "TO": "Tocantins",
    },

    /**
     * Returns whether the file extension is within the valid list
     * @param name_file
     * @param exts
     * @returns {boolean}
     */
    validExtensionFile: (name_file, exts) => {
        name_file = name_file.trim().toLowerCase();
        return (new RegExp('(' + exts.join('|').replace(/\./g, '\\.') + ')$')).test(name_file);
    },

    /** Change no select para filtro de datas */
    onChangeSelectFilterPeriodo: () => {
        $("select#periodo_vendas_filtro").on('change', (event) => {
            event.preventDefault();
            let $this = $(event.currentTarget),
                periodo = $this.val(),
                route = $this.attr('data-route');
            // Verifica se é um periodo preset
            if(periodo !== "custom") {
                App.loader.show();
                window.location.href = `${route}?periodo=${periodo}`;
            } else {
                let $modal = $("#filtro-periodo-modal");
                // Verifica se já está configurado os inputs
                if(window.App._loadedDateRangePicker === false) {
                    window.App.setupCalendarioFiltroPeriodo($modal);
                }
                $modal.modal('show');
            }
        });
    },

    // Select para puxar os serviços do fornecedor
    filterRelatorioFornecedor: () => {
        // Click no checkbox para marcar todos os servicos
        $(document).on("change", ".list-check input#select-all-servicos", (event) => {
            let $this = $(event.currentTarget);
            $(".list-check input").prop('checked', $this.prop('checked'));
        });

        // Modal com o filtro
        let $modal_filtro = $("#filtro-relatorio-fornecedor");
        // Verifica se a modal está na pagina
        if(typeof $modal_filtro[0] === "object") {
            window.App.setupCalendarioFiltroPeriodo($modal_filtro);
        }
    },

    // Configura os inputs na modal para filtro de datas
    setupCalendarioFiltroPeriodo: ($modal) => {
        Plugins.loadLibDateRanger(() => {
            // Variavel para nao dar duplo bind nos inputs
            window.App._loadedDateRangePicker = true;
            let minDate = new Date(), maxDate = new Date(), startDate = new Date();
            minDate.setFullYear(minDate.getFullYear() - 1);
            maxDate.setFullYear(maxDate.getFullYear() + 1);
            startDate.setMonth(startDate.getMonth() - 3);
            // Configurações padrões
            let config = {
                autoApply: true,
                minDate: minDate,
                maxDate: maxDate,
                autoUpdateInput: false,
                locale: {
                    format: 'DD/MM/YYYY',
                    daysOfWeek: ["Do", "2º", "3º", "4º", "5º", "6º", "Sá"],
                    monthNames: ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"],
                }
            };
            // Calendario de pesquisa
            let $calendario = $modal.find(".date_range");
            let $data_inicio = $modal.find("input[name='inicio']");
            let $data_final = $modal.find("input[name='final']");
            // Configura o datepicker
            $calendario.daterangepicker(config);
            $calendario.on('apply.daterangepicker', (ev, picker) => {
                let inicio = picker.startDate.format(config.locale.format);
                let final = picker.endDate.format(config.locale.format);
                // Coloca a data no input
                $(ev.currentTarget).val(inicio + " - " + final);
                $data_inicio.val(picker.startDate.format("DD-MM-YYYY"));
                $data_final.val(picker.endDate.format("DD-MM-YYYY"));
            });
        });
    }
};

(function(){
    App.init();
}(window));
