/***  ===============================================
 **** Inicialização da aplicação
 ================================================  ***/
window.App = {

    // Inicializacao do App
    init: () => {
        // Controllers
        App.initControllers();
        // Normalize string polyfill
        Helpers.normalizeString();
        // Bind para abir as modais
        Helpers.bindOpenModal();
        // Remove as classes de erros dos campos
        App.bindValidateInput();
        // AutoValidate form
        App.autoValidateForm();
        // Mostra a lista de acompanhantes salvos no localstorage
        App.showListAcompanhantes();
        // Cookie
        App.cookieConsent();
        // Scroll navs shadow
        App.onScrollNavShadow();

        window.click_copy = require('clipboard-copy');
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

    /** Bind on focus input validate */
    bindValidateInput: () => {
        // Remove a classe de erro nos inputs
        $(document).on("changeData", "input.is-invalid", (event) => {
            $(event.currentTarget).removeClass('is-invalid valid');
        });
        // Remove a classe de erro nos inputs
        $(document).on("focus", "input.is-invalid", (event) => {
            $(event.currentTarget).removeClass('is-invalid valid');
        });
        // Remove a classe de erro nos selects
        $(document).on("change", "select.is-invalid", (event) => {
            $(event.currentTarget).removeClass('is-invalid valid');
        });
    },

    /** Auto valida o formulario */
    autoValidateForm: () => {
        $("form[data-validate-post]").on("submit", (event) => {
            let $this = $(event.currentTarget);
            if (window.App.validateForm($this)) {
                window.Helpers.loader.show();
            } else {
                event.preventDefault();
            }
        });

        // Formulário enviado via ajax
        $("form[data-validate-ajax]").on("submit", (event) => {
            event.preventDefault();
            let $this = $(event.currentTarget);
            // Valida o formulário
            if (window.App.validateForm($this)) {
                // Mostra o loader
                window.Helpers.loader.show();
                // POST
                let $request = $.post($this.attr('action'), $this.serialize());
                $request.done((response) => App.captureSuccessAjax(response, $this));
                $request.fail(App.captureErrorAjax);
            }
        });
    },

    /**
     * Função para capturar o retorno do AJAX em casos de sucesso
     * @param response
     * @param $form
     */
    captureSuccessAjax(response, $form) {
        window.Helpers.loadSweetAlert(() => {
            // Caso deu ok retorno padrão
            if (response['action']['result']) {
                // Verifica se existe URL para redirecionamento
                if (typeof response['action']['url'] !== "undefined" && $form.attr('data-redirect') !== undefined) {
                    window.location.href = response['action']['url'];
                } else {
                    // Remove o loader
                    window.Helpers.loader.hide();
                    // Mostra a mensagem
                    swal(response['action']['title'], response['action']['message'], "success").then(() => {
                        // Mostra o loader
                        window.Helpers.loader.show();
                        // Caso posua redirecionamento apos a mensagem
                        if (typeof response['action']['url'] !== "undefined") {
                            window.location.href = response['action']['url'];
                        } else {
                            // Recarrega a tela
                            location.reload();
                        }
                    });
                }
            } else {
                // Remove o loader
                window.Helpers.loader.hide();
                // Mostra a mensagem
                swal(response['action']['title'], response['action']['message'], "error");
            }
        });
    },

    /**
     * Função para capturar os erros AJAX
     * @param error
     */
    captureErrorAjax: (error) => {
        window.Helpers.loadSweetAlert(() => {
            // Remove o loader
            window.Helpers.loader.hide();
            let response = error.responseJSON;
            if (response) {
                // Caso falhe nas validacoes da requisição
                if (parseInt(error['status']) === 422) {
                    let errors = response['errors'];
                    // Percorre os erros 422
                    for (let erro in errors) {
                        if (errors.hasOwnProperty(erro)) {
                            let $input = $("[name='" + erro + "']");
                            let invalid_feedback = $input.siblings(".invalid-feedback");
                            // Coloca o campo em formato de erro
                            if ($input) {
                                $input.addClass('is-invalid');
                                invalid_feedback.html(errors[erro][0]);
                            }
                        }
                    }
                } else {
                    let message = response['message'] || "Erro na requisição!";
                    swal("Op's erro " + error['status'], message, "error");
                }
            } else {
                swal("Op's ocorreu um erro!", "Tente novamente, caso o erro persista entre em contato conosco.", "error");
            }
        });
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
        for (let index = 0; index < length; index++) {
            let $element = $($fields[index]);
            let label = $element.attr('title');
            let value = $element.val().trim();
            let invalid_feedback = $element.siblings(".invalid-feedback");
            label = (label === null) ? $element.attr('placeholder').trim() : label.trim();
            // Separa os selects
            if ($element.attr('type') !== undefined) {
                let minValue = parseInt($element.attr('data-min'));
                // Verifica se o campo não está em branco
                if (value.length === 0) {
                    invalid_feedback.html("O campo " + label.toLowerCase() + " não pode ser vazio!");
                    $element.addClass('is-invalid');
                    erros++;
                    continue;
                }
                // Validacao para nome completo
                if ($element.attr('data-nome-completo')) {
                    let nome = value.trim().split(" ");
                    // Verifica se tem sobrenome
                    if (typeof nome[1] === "undefined") {
                        invalid_feedback.html("Por favor insira o nome completo!");
                        $element.addClass('is-invalid');
                        erros++;
                        continue;
                    }
                }
                // Verifica os campos do tipo email
                if ($element.attr('type') === 'email' && (!window.Helpers.isMail(value))) {
                    invalid_feedback.html("O e-mail deve ser um endereço de e-mail válido!");
                    $element.addClass('is-invalid');
                    erros++;
                    continue;
                }
                // Verifica se o campo esta com erro
                if ($element.hasClass('is-invalid')) {
                    // Mensagem para data de nascimento
                    if ($element.attr('data-mask') === "date") {
                        invalid_feedback.html("Formato da data de nascimento inválida. Por favor verifique! Formato aceito: DD/MM/AAAA");
                        $element.addClass('is-invalid');
                        erros++;
                        continue;
                    } else {
                        $element.addClass('is-invalid');
                        erros++;
                        continue;
                    }
                }
                // Verifica se o campo tem os caracteres minimos
                if (minValue && value.length < minValue) {
                    invalid_feedback.html("O campo " + label.toLowerCase() + ", deve ter mais de " + minValue + " caracteres!");
                    $element.addClass('is-invalid');
                    erros++;
                }
            }
        }

        return (erros === 0);
    },

    /**
     * Mostra a lista de acompanhantes salvos no localstorage
     * Ao entrar no campo para preencher os dados
     */
    showListAcompanhantes: () => {
        let close_timeout;
        // List inputs
        $(document).on('focus', 'input[data-list]', (event) => {
            let $this = $(event.currentTarget);
            let $list_help = $(`ul.list-help`);
            if ($list_help.length) {
                // Fecha os outros
                $list_help.hide();
                clearTimeout(close_timeout);
                let position = $this[0].getBoundingClientRect();
                let top = (position.top + position.height + 2);
                let $list = $(`#${$this.attr('data-list')}`);
                // tamanho da tela
                let screeen = ($(window).height() - 50);
                let height_list = $list.height();
                // Verifica se abre pra cima
                if ((top + height_list) > screeen) {
                    top = position.top - height_list - 12;
                }
                $list.css({ top: `${top}px`, left: `${position.left}px` }).attr('data-origin', $this.attr('id')).show();
            }
        });

        // List inputs
        $(document).on('blur', 'input[data-list]', (event) => {
            let $this = $(event.currentTarget);
            close_timeout = setTimeout(() => {
                $(`ul.list-help#${$this.attr('data-list')}`).hide();
            }, 150);
        });

        // Click o documento para fechar a lista de ajuda
        $(document).on('click', (event) => {
            let $this = $(event.currentTarget);
            if ($this[0].activeElement) {
                let not_list = $this[0].activeElement.getAttribute('data-list');
                if (not_list) $(`ul.list-help:not(#${not_list})`).hide();
            }
        });

        // Click na lista de ajuda
        $(document).on('click', "ul.list-help li", (event) => {
            event.preventDefault();
            let $this = $(event.currentTarget);
            let acompanhantes = window.DadosFactory.getDadosAcompanhantes();
            let $ul = $this.parent('ul'), $origin = $(`#${$ul.attr('data-origin')}`);
            let $parent_origin = $origin.parents('.list-acompanhantes');
            let acompanhante = acompanhantes[$this.attr('data-index')] || null;
            // Coloca os dados da pessoa no input
            if (acompanhante !== null) {
                $parent_origin.find("[data-callback='nome']").val(acompanhante['nome'])
                    .trigger('keyup').trigger('changeData').trigger('blur');
                $parent_origin.find("[data-callback='documento']").val(acompanhante['documento'])
                    .trigger('keyup').trigger('changeData').trigger('blur');
                $parent_origin.find("[data-callback='nascimento']").val(acompanhante['nascimento'])
                    .trigger('keyup').trigger('changeData').trigger('blur');
            }
        });
    },

    /** Cookies */
    cookie: {
        create: (name, value, days) => {
            let expires = "";
            if (days) {
                let date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = "; expires=" + date.toGMTString();
            }
            document.cookie = escape(name) + "=" + escape(value) + expires + "; path=/; samesite=lax";
        },
        read: (name) => {
            let nameEQ = escape(name) + "=";
            let ca = document.cookie.split(';');
            for (let i = 0; i < ca.length; i++) {
                let c = ca[i];
                while (c.charAt(0) === ' ') c = c.substring(1, c.length);
                if (c.indexOf(nameEQ) === 0) return unescape(c.substring(nameEQ.length, c.length));
            }
            return null;
        },
        erase: (name) => {
            App.cookie.create(name, "", -1);
        }
    },

    onScrollNavShadow: () => {
        let $parent_scroll = $(".nav-scroll-shadows"),
            length = $parent_scroll.length;
        for (let i = 0; i < length; i++) {
            let $parent = $($parent_scroll[i]);
            $parent.find(".horizontal-scroll").on("scroll", (event) => {
                let $this = $(event.currentTarget);
                let newScrollLeft = $this.scrollLeft(),
                    width = $this.width(),
                    scrollWidth = $this.get(0).scrollWidth;
                // Shadow left
                if (newScrollLeft >= 10) {
                    $parent.addClass('shadow-left');
                } else {
                    $parent.removeClass('shadow-left');
                }
                // Shadow right
                if ((scrollWidth - newScrollLeft - width) <= 10) {
                    $parent.addClass('no-shadow-right');
                } else {
                    $parent.removeClass('no-shadow-right');
                }
            })
        }
    },

    /** Cookie LGPD */
    cookieConsent: () => {
        // Mostra o popout de cookie
        if (App.cookie.read('cookieConsent') !== 'true') {
            $("#cookie-consent").addClass("show");
        }

        // On click set the cookie and remove class to hide consent bar
        $("[data-action='closePopoutCookie']").on('click', (e) => {
            e.preventDefault();
            App.cookie.create('cookieConsent', 'true', 1000);
            $("#cookie-consent").removeClass("show");
        });
    },

    // Configuracoes do slider OWL
    sliderConfig: {
        withButtons: {
            loop: true,
            rewind: true,
            lazyLoad: true,
            margin: 24,
            dots: false,
            nav: true,
            navText: [
                '<i class="iconify" data-icon="jam:chevron-left" aria-hidden="true"></i>',
                '<i class="iconify" data-icon="jam:chevron-right" aria-hidden="true"></i>'
            ],    
            onInitialized: function() {
                $('.owl-prev').attr('aria-label', 'Anterior');
                $('.owl-next').attr('aria-label', 'Próximo');
            },
            responsive: {
                0: { items: 1, stagePadding: 15, },
                375: { items: 1, stagePadding: 25, },
                380: { items: 1, stagePadding: 20, },
                600: { items: 2, },
                768: { items: 2, },
                992: { items: 3, },
                1280: { items: 4, },
                1590: { items: 5, }
            }
        }
    }
};

// Inicializacao do app
(function () {
    window.App.init();
}(window));

$(function() {
    $('.owl-carousel').each(function() {
        $(this).owlCarousel(App.sliderConfig.withButtons);
    });
});

