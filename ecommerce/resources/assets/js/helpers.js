let Helpers = {

    /** Bind para abrir as modais */
    bindOpenModal: () => {
        $('[data-modal]').on('click', (event) => {
            event.preventDefault();
            $($(event.currentTarget).attr('data-modal')).jqmodal();
        });
    },

    /**
     * Retorna o token do laravel para as requisições
     * @returns {*}
     */
    getTokenLaravel: () => {
        let token = document.head.querySelector('meta[name="csrf-token"]');
        if(token) {
            return token.content;
        } else {
            console.error("Token do laravel não configurado no header");
        }
    },

    /** BUG IE, Safari, iPhone, iPad e etc */
    dataToIE: (date) => {
        let data = date.split(' ');
        data = data[0].split('-');
        return new Date(data[0], (data[1] - 1), data[2], 0, 0, 0, 0);
    },

    /**
     * Numero randomico
     * @param min
     * @param max
     * @returns {*}
     */
    getRandomArbitrary: (min, max) => {
        return Math.random() * (max - min) + min;
    },

    // Carrega arquivo JS
    loadFileJs: (file, callback) => {
        let d = document, t = 'script',
            o = d.createElement(t),
            s = d.getElementsByTagName(t)[0];
        o.src = (file.indexOf("http") >= 0) ? file : window.location.origin + file;
        if (callback) { o.addEventListener('load', callback, false); }
        o.addEventListener('error', () => {
            alert(`Não foi possível carregar o script ${file} atualize a página e tente novamente!`);
            // Recarrega a tela
            location.reload();
        }, false);
        s.parentNode.insertBefore(o, s);
    },

    /**
     * Carrega o sweet alert
     *
     * @param callback
     */
    loadSweetAlert: (callback) => {
        // https://sweetalert.js.org/guides/#installation
        if(typeof window.swal === "undefined") {
            window.Helpers.loadFileJs("/js/sweetalert.js?v=2.0", callback);
        } else {
            callback();
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
     * Data por extenso
     * @param data
     * @returns {string}
     */
    dataExtenso: (data) => {
        let day = ["Domingo", "Segunda-feira", "Terça-feira", "Quarta-feira", "Quinta-feira", "Sexta-feira", "Sábado"][data.getDay()];
        let date = ("0" + data.getDate()).substr(-2);
        let month = ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"][data.getMonth()];
        let year = data.getFullYear();
        return `${day}, Dia ${date} de ${month} de ${year}`;
    },

    /**
     * Remove acentuacao de uma string
     *
     * @param str
     * @returns {string}
     */
    removeAcentos: (str) => {
        return str.normalize('NFD').replace(/[\u0300-\u036f]/g, "");
    },

    /**
     * Debounce function
     *
     * @param func
     * @param wait
     * @param immediate
     * @returns {function(...[*]=)}
     */
    debounce: (func, wait, immediate) => {
        let timeout;
        return () => {
            let context = this, args = arguments;
            let later = () => {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            let callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    },

    /** Autocapitalize Input */
    capitalizeInput: () =>  {

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

    /**
     * Mascaras usando o Vanilla Mask
     * mascaras pré definidas
     */
    vanillaMask: () => {
        // Mascaras para moedas
        VMasker(document.querySelectorAll(".vanillaMaskMoney")).maskMoney();
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
                cvv: '9999',
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
                    let telMask = ['(99) 9 9999-9999'];
                    // Bind para trocar as mascaras
                    $campo.addEventListener('input', inputHandler.bind(undefined, telMask, 17), false);
                    // Valida o numero do telefone ao sair do campo
                    $($campo).on('blur', (event) => {
                        let $this = $(event.currentTarget), telefone = $this.val().trim(),
                            $invalid_feedback = $this.siblings(".invalid-feedback");
                        if(telefone.length <= 0) return;
                        // Valida o telefone
                        if(window.Helpers.isValidPhone(telefone) === false) {
                            $invalid_feedback.html("Número de telefone inválido. Verifique!");
                            $this.addClass('is-invalid');
                        }
                    });
                } else {
                    // Max length no campo conforme o parttenrs
                    $campo.maxLength = mascaras[mask_pattern].length;
                    // Coloca a mascara
                    VMasker($campo).maskPattern(mascaras[mask_pattern]);
                    // Validacao das datas
                    if(mask_pattern === "date") {
                        // Bind para validacao ao sair do campo de data
                        $($campo).on('blur', (event) => {
                            let $this = $(event.currentTarget),
                                data = $this.val().trim(),
                                invalid_feedback = $this.siblings(".invalid-feedback");

                            // Verifica se o campo não está vazio
                            if(data.length > 0) {
                                // Valida a data usando o moment
                                let is_valid = Helpers.isValidDate(data);
                                if(is_valid === false) {
                                    invalid_feedback.html("Data inválida, verifique!");
                                    $this.addClass('is-invalid');
                                } else {
                                    // Recupera o ano informado
                                    let year = parseInt(data.split("/")[2]);
                                    let current_year = new Date().getFullYear();
                                    // Verifica se o ano informado é maior que o ano atual
                                    if(year > current_year) {
                                        invalid_feedback.html(`Ano maior que ${current_year}!`);
                                        $this.addClass('is-invalid');
                                    }
                                }
                            }
                        });
                    }
                    // Validacao para CPF
                    if(mask_pattern === "cpf") {
                        $($campo).on('blur', (event) => {
                            let $this = $(event.currentTarget), cpf = $this.val().trim(),
                                $invalid_feedback = $this.siblings(".invalid-feedback");
                            if(cpf.length <= 0) return;
                            // Valida o CPF
                            if(window.validateCPF(cpf) === false) {
                                $invalid_feedback.html("Número de CPF inválido. Verifique");
                                $this.addClass('is-invalid');
                            }
                        });
                    }
                }
            }
        }
    },

    /**
     * Valida a data
     *
     * @param dateStr
     * @returns {*|boolean}
     */
    isValidDate: (dateStr) => {
        // First check for the pattern
        if(!/^\d{1,2}\/\d{1,2}\/\d{4}$/.test(dateStr))
            return false;

        // Parse the date parts to integers
        let parts = dateStr.split("/");
        let day = parseInt(parts[0], 10);
        let month = parseInt(parts[1], 10);
        let year = parseInt(parts[2], 10);

        // Check the ranges of month and year
        if(year < 1000 || year > 3000 || month === 0 || month > 12)
            return false;

        let monthLength = [ 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31 ];

        // Adjust for leap years
        if(year % 400 === 0 || (year % 100 !== 0 && year % 4 === 0))
            monthLength[1] = 29;

        // Check the range of the day
        return day > 0 && day <= monthLength[month - 1];
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

    /**
     * Add parameter to URL
     *
     * @param key
     * @param value
     * @param url
     * @returns {string}
     */
    addURLParameter: (key, value, url = null) => {
        key = encodeURIComponent(key);
        value = encodeURIComponent(value);
        url = (url == null) ? document.location.search : url;

        // kvp looks like ['key1=value1', 'key2=value2', ...]
        let kvp = url.substr(1).split('&');
        let length = kvp.length;
        let i = 0;

        for(; i < length; i++){
            if (kvp[i].startsWith(key + '=')) {
                let pair = kvp[i].split('=');
                pair[1] = value;
                kvp[i] = pair.join('=');
                break;
            }
        }

        if(i >= length){
            kvp[length] = [key,value].join('=');
        }

        // can return this or...
        return kvp.join('&');
    },

    /**
     * Remove um parametro da URL
     *
     * @param url
     * @param parameter
     * @returns {string|*}
     */
    removeURLParameter: (url, parameter) => {
        //prefer to use l.search if you have a location/link object
        let urlparts = url.split('?');
        if (urlparts.length >= 2) {
            let prefix = encodeURIComponent(parameter) + '=';
            let pars = urlparts[1].split(/[&;]/g);
            let length = pars.length;
            //reverse iteration as may be destructive
            for (let i = length; i-- > 0;) {
                //idiom for string.startsWith
                if (pars[i].lastIndexOf(prefix, 0) !== -1) {
                    pars.splice(i, 1);
                }
            }
            return urlparts[0] + (length > 0 ? '?' + pars.join('&') : '');
        }
        return url;
    },

    /**
     * Recupera um item na query string da URL
     *
     * @param parameter
     * @param url
     * @returns {*}
     */
    getURLParameter: (parameter, url = null) => {
        url = url || window.location.href;
        let results = new RegExp('[\?&]' + parameter + '=([^&#]*)').exec(url);
        if (results == null) { return null;}
        return decodeURI(results[1]) || null;
    },

    /**
     * Retorna somente a primeira letra como maiuscula
     *
     * @param string
     * @returns {string}
     */
    ucFirst: (string) => {
        return string.charAt(0).toUpperCase() + string.slice(1);
    },

    /** Loader dentro das modais */
    loaderInsideModal: {
        show: () => { $(".modal-loader").addClass('d-flex').removeClass("d-none") },
        hide: () => { $(".modal-loader").addClass('d-none').removeClass("d-flex") }
    },

    /** Loader */
    loader: {
        hide: () => { $("#preloader").addClass("d-none") },
        show: () => { $("#preloader").removeClass("d-none") },
    },

    /**
     * Retorna se o numero do cartao é valido
     *
     * @param number
     * @returns {{valid: boolean, brand: null}|{valid: boolean, brand: (*|{brand: null}|{brand: string})}}
     */
    isValidCreditCartNumber: (number) => {

        // Retira pontos ou espaços
        number = number.replace(/[^0-9]/g, '');
        let total = 0, arr = [], length = number.length;

        // Valida o numero
        for(let i = 0; i < length; i++){
            if( i % 2 === 0 ){
                let dig = number[i] * 2;
                if(dig > 9){
                    let dig1 = dig.toString().substr(0,1);
                    let dig2 = dig.toString().substr(1,1);
                    arr[i] = parseInt(dig1)+parseInt(dig2);
                } else {
                    arr[i] = parseInt(dig);
                }
                total += parseInt(arr[i]);
            } else {
                arr[i] =parseInt(number[i]);
                total += parseInt(arr[i]);
            }
        }

        if(total % 10 === 0) {
            let brand = window.creditCardType(number);
            if(typeof brand[0] != "undefined") {
                return {valid: true, brand: brand[0]['type']}
            }
        }

        return {valid: false, brand: null}
    },

    /**
     * Retorna se o numero de telefone está valido
     *
     * @param telefone
     * @returns {boolean}
     */
    isValidPhone: (telefone) => {
        let phone = telefone.replace("(", "");
        phone = phone.replace(")", "");
        phone = phone.replace("-", "");
        phone = phone.replace(" ", "");
        phone = phone.replace(" ", "");

        return ! (phone === '0000000000' ||
            phone === '00000000000' ||
            phone === "1111111111" ||
            phone === "11111111111" ||
            phone === "2222222222" ||
            phone === "22222222222" ||
            phone === "3333333333" ||
            phone === "33333333333" ||
            phone === "4444444444" ||
            phone === "44444444444" ||
            phone === "5555555555" ||
            phone === "55555555555" ||
            phone === "6666666666" ||
            phone === "66666666666" ||
            phone === "7777777777" ||
            phone === "77777777777" ||
            phone === "8888888888" ||
            phone === "88888888888" ||
            phone === "9999999999" ||
            phone === "99999999999" ||
            phone.length < 10);
    },

    // 6 Primeiros digitos do campo
    getBindCreditCard: ($field) => {
        return $field.val().replace(/[^0-9]/g, '').substring(0,6);
    },

    /** Polyfill String.prototype.normalize.js */
    normalizeString: () => {
        if(typeof String.prototype.normalize === "undefined") {
            String.prototype.normalize = function() {
                let translate = {
                    'à':'a', 'á':'a', 'â':'a', 'ã':'a', 'ä':'a', 'å':'a', 'æ':'a', 'ç':'c', 'è':'e', 'é':'e', 'ê':'e', 'ë':'e',
                    'ì':'i', 'í':'i', 'î':'i', 'ï':'i', 'ð':'d', 'ñ':'n', 'ò' :'o', 'ó':'o', 'ô':'o', 'õ':'o', 'ö':'o', 'ø':'o',
                    'ù':'u', 'ú':'u', 'û':'u', 'ü':'u', 'ý':'y', 'þ':'b', 'ß' :'s', 'à':'a', 'á':'a', 'â':'a', 'ã':'a', 'ä':'a',
                    'å':'a', 'æ':'a', 'ç':'c', 'è':'e', 'é':'e', 'ê':'e', 'ë' :'e', 'ì':'i', 'í':'i', 'î':'i', 'ï':'i', 'ð':'d',
                    'ñ':'n', 'ò':'o', 'ó':'o', 'ô':'o', 'õ':'o', 'ö':'o', 'ø' :'o', 'ù':'u', 'ú':'u', 'û':'u', 'ý':'y', 'ý':'y',
                    'þ':'b', 'ÿ':'y', 'ŕ':'r', 'ŕ':'r'
                }, translate_re = /[àáâãäåæçèéêëìíîïðñòóôõöøùúûüýþßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŕŕ]/gim;
                return (this.replace(translate_re, function(match) {
                    return translate[ match ];
                }));
            };
        }
    }
};

module.exports = Helpers;
