let PagamentoCtrl = {

    // URL para verificar o email do cliente
    _urlVerificarEmailCliente: "",

    // Evento de resize da tela
    _resize: null,

    // Inicialização do controller
    init: (page) => {

        // Salva a URL para verificar o email do cliente
        PagamentoCtrl._urlVerificarEmailCliente = page.getAttribute('data-cliente');

        // Salva a URL para gerar qrcode de pagamento pix
        PagamentoCtrl._urlGeracaoQrcodePagamentoPix = page.getAttribute('data-geracao-pix');

        // Salva a URL para consultar o pagamento pix
        PagamentoCtrl._urlConsultaPagamentoPix = page.getAttribute('data-consultar-pix');

        // Salva a URL para gerar o pedido
        PagamentoCtrl._urlGerarPedido = page.getAttribute('data-gerar-pedido');

        // Salva a URL para geração dp pedido com sucesso
        PagamentoCtrl._urlPedidoSucesso = page.getAttribute('data-pagamento-sucesso');

        PagamentoCtrl._urlCancelarPix = page.getAttribute('data-cancelar-pix');

        PagamentoCtrl._urlClienteSessao = page.getAttribute('data-pagamento-cliente-sessao');

        // Mascaras para os campos do formulario
        window.Helpers.vanillaMask();

        // AutoCapitaliza inputs
        window.Helpers.capitalizeInput();

        // Resize da tela
        PagamentoCtrl.onResizeWindow();

        // Change no select das parcelas
        PagamentoCtrl.onChangeSelectParcelas();

        // Click no botao voltar mobile
        CarrinhoCtrl.onClickButtonVoltarMobile();

        // Bandeira do cartao de credito
        PagamentoCtrl.onChangeInputCreditCard();

        // Evento ao sair do campo de email
        PagamentoCtrl.onBlurInputEmailCliente();

        // Send step checkout
        PagamentoCtrl.sendStepCheckout();

        // Reset no carregamento da página
        PagamentoCtrl.onLoadPage();

        // Change no select de forma de pagamento
        PagamentoCtrl.onChangeSelectFormaPagamento();

        // Click no icone de copiar código pix
        PagamentoCtrl.onClickCopyPix();

        // Click para disponibilizar alteração de forma de pagmento
        PagamentoCtrl.onClickChangeFormaPagamento();

        // Quando o user altera os campos, trabalha com o evento
        PagamentoCtrl.onChangeCamposCliente();

        // PagamentoCtrl.liberarSelectParcelas();

        PagamentoCtrl.onChangeCamposCartao();
    },

    /** Change no select das parcelas */
    onChangeSelectParcelas: () => {
        $("select#numero_parcelas").on('change', (event) => {
            let $this = $(event.currentTarget);
            let $option = $this.find('option:selected');
            let $botao_cartao = $("button.btn-pay");
            // Recupera os dados da parcela
            let parcela = $option.val(),
                valor = window.Helpers.formataValor($option.attr('data-valor'), 2);
            // Altera o texto no botão de pagar
            $botao_cartao.text(`Pagar em ${parcela}x de R$ ${valor}`);
        });
    },

    /** OnChange credit card number */
    onChangeInputCreditCard: () => {
        let $field_cart = $("input[data-mask='cartao']");

        // Evento ao digitar no campo
        $field_cart.on('keyup', (event) => {
            let $this = $(event.currentTarget), number_card = $this.val().replace(/[^0-9]/g, ''),
                $callback_bandeira = $($this.attr('data-logo'));
            // Remove as bandeira do callback
            $callback_bandeira.removeClass(Object.keys(window.creditCardsBrand).map(e => window.creditCardsBrand[e]).join(" "));
            // Verifica se o campo não está vazio
            if (number_card.length > 0) {
                // Recupera o bandeira do cartao
                let bandeira = window.creditCardType(number_card);
                if(typeof bandeira[0] != "undefined") {
                    // Adiciona a bandeira no callback
                    $callback_bandeira.addClass(bandeira[0]['type'].toLowerCase());
                }
            }
        });

        // Evento ao sair do campo
        $field_cart.on('blur', (event) => {
            event.preventDefault();
            let $this = $(event.currentTarget),
                number_card = $this.val().trim().replace(/[^0-9]/g, ''),
                $callback = $($this.attr("data-callback")),
                $callback_bandeira = $($this.attr('data-logo')), bandeira = "";
            let $invalid_feedback = $this.siblings(".invalid-feedback");
            if(number_card.length === 0) return;
            // Valida o cartão de crédito
            let valid = window.Helpers.isValidCreditCartNumber(number_card);
            // Remove as bandeira do callback
            $callback_bandeira.removeClass(Object.keys(window.creditCardsBrand).map(e => window.creditCardsBrand[e]).join(" "));
            // Verifica o retorno
            if(valid['valid']) {
                bandeira = valid['brand'].toLowerCase();
                // Adiciona o logo do cartao no callback
                $callback_bandeira.addClass(bandeira);
                $callback.val(bandeira);
                $invalid_feedback.html("");
            } else {
                // Verifica se o cartao é amex
                let brand = window.creditCardType(number_card);
                if(typeof brand[0] != "undefined") {
                    if(brand[0]['type'].toLowerCase() === "american-express") {
                        // Adiciona o logo do cartao no callback
                        $callback_bandeira.addClass("american-express");
                        $callback.val("amex");
                        bandeira = "amex";
                        $invalid_feedback.html("");
                    } else {
                        $callback.val("");
                        $this.addClass('is-invalid');
                        $invalid_feedback.html("Número do cartão inválido, verifique!");
                    }
                } else {
                    $callback.val("");
                    $this.addClass('is-invalid');
                    $invalid_feedback.html("Número do cartão inválido, verifique!");
                }
            }

            // Quantidade de caracteres do CVV
            let max_length = (bandeira === "amex") ? 4 : 3;
            let placeholder = `${max_length} Dígitos`;
            $("[data-mask='cvv']")
                .attr('maxlength', max_length)
                .attr('data-min', max_length)
                .attr('placeholder', placeholder);
        });
    },

    /**
     * Validação do email
     * Verificação se já existe conta para o email informado
     */
    onBlurInputEmailCliente: () => {
        // Valida o email e verifica se já possui conta
        $("input[name='cliente[email]']").on('blur', (event) => {
            event.preventDefault();
            let $this = $(event.currentTarget), email = $this.val().trim(),
                $invalid_feedback = $this.siblings(".invalid-feedback"),
                $loader_input = $this.siblings(".loader-input");
            if($this.hasClass('is-invalid')) return;
            // Caso não esteja vazio
            if(email.length > 0) {
                // valida o email
                if(window.Helpers.isMail(email) === false) {
                    // Mensagem de email invalido
                    $this.addClass("is-invalid");
                    $invalid_feedback.html("E-mail inválido. Verifique!");
                    return false;
                }
                // Loader input
                $loader_input.removeClass('d-none');
                // POST para validar o email se já existe
                let resultado = $.post(PagamentoCtrl._urlVerificarEmailCliente, {email: email, _token: window.Helpers.getTokenLaravel()});
                resultado.done((response) => {
                    // Loader
                    $loader_input.addClass('d-none');
                    // Caso o email já esteja cadastrado
                    if(response['email_exists']) {
                        // Mensagem de email ja utilizado
                        $this.addClass("is-invalid");
                        $invalid_feedback.html("E-mail já utilizado! Por favor faça login.");
                        // Coloca o email no campo de login
                        $("input#email_login").val(email);
                        // Modal de login
                        let $modal_aviso = $("#modal-aviso-login");
                        $modal_aviso.find("h6").html("Este e-mail já está em uso");
                        $modal_aviso.find("p").html("Olá este e-mail já possui uma conta ativa! <br> Por favor faça login.");
                        $modal_aviso.jqmodal('show');
                    }
                });
            }
        });
    },

    /**
     *  Evento disparado quando altera o tamanho da tela
     *  Para desabiltiar e habilitar o campo CVV no mobile e desktop
     */
    onResizeWindow: () => {
        let $input_mobile = $("input#codigo_cartao_mobile"),
            input_desktop = $("input#codigo_cartao");

        // Função para habilitar ou desabilitar os inputs
        let onResize = () => {
            // Caso seja maior que 640 desabilitamos o mobile
            if($(window).width() >= 768) {
                $input_mobile.attr('disabled', true);
                $input_mobile.removeAttr('data-checkout');
                input_desktop.removeAttr('disabled');
                input_desktop.attr('data-checkout', 'securityCode');
            } else {
                input_desktop.attr('disabled', true);
                input_desktop.removeAttr('data-checkout');
                $input_mobile.removeAttr('disabled');
                $input_mobile.attr('data-checkout', 'securityCode');
            }
        };

        // Emite o evento ao carregar a tela
        onResize();

        // Bind ao redimensionar a tela
        $(window).resize(() => {
            clearTimeout(PagamentoCtrl._resize);
            PagamentoCtrl._resize = setTimeout(onResize, 100);
        });
    },

    // Usado para atualizar o campo de código de segurança...
    updateSecutiryCodeType: () => {

        let $input_mobile = $("input#codigo_cartao_mobile"),
            input_desktop = $("input#codigo_cartao");

        if($(window).width() >= 768) {
            $input_mobile.attr('disabled', true);
            $input_mobile.removeAttr('data-checkout');
            input_desktop.removeAttr('disabled');
            input_desktop.attr('data-checkout', 'securityCode');
        } else {
            input_desktop.attr('disabled', true);
            input_desktop.removeAttr('data-checkout');
            $input_mobile.removeAttr('disabled');
            $input_mobile.attr('data-checkout', 'securityCode');
        }
    },

    /** Send step checkout Facebook and GTM */
    sendStepCheckout: () => {
        // Quantidade de itens no carrinho
        let length = window.carrinho.length,
            total_cart = 0, array_facebook = [];

        // Envia Checkout para o Google
        window.Google.sendStepCheckout(window.carrinho, 2);

        // Monta o array para o facebook
        for(let i = 0; i < length; i++) {
            let servico = window.carrinho[i];
            total_cart += parseFloat(servico['price']);
            array_facebook.push({id: servico['id'], quantity: 1});
        }

        // Envia Informacao do pagamento para o Facebook
        window.Facebook.sendAddPaymentInfo(array_facebook, total_cart);
    },

    /** OnLoatPage */
    onLoadPage: () => {
        $("select#forma_pagamento").val("");
    },

    requestPedidoRealizado: () =>{
        let resultado = $.post(PagamentoCtrl._urlPedidoSucesso,
            {_token: window.Helpers.getTokenLaravel()});
        resultado.done((response) => {
           console.log(response);
        });
    },

    /**
     * Função que serve para gerar o pedido quando o o sistema entende que o PIX foi pago
     * A verificação de PIX pago ocorre direto na pagamento.blade
     **/
    requestGerarPedido: () => {

        Helpers.loader.show();

        let cliente = {
            nome: $("input#nome_cadastro").val(),
            email: $("input#email").val(),
            cpf: $("input#cpf_cadastro").val(),
            nascimento: $("input#nascimento_cadastro").val(),
            telefone: $("input#tel_cadastro").val(),
        }

        let link = PagamentoCtrl._urlGerarPedido;
        let req = {
            cliente: cliente,
        };

        let result = axios.post(link, req).then((response) => {
            let data = response.data;
            window.location.href = data.url_redirecionamento;
        });
    },

    /** OnChange credit card number */
    onChangeSelectFormaPagamento: () => {
        $("select#forma_pagamento").on('change', (event) => {
            $this = $(event.currentTarget);
            let option = $this.find('option:selected');
            let option_pix = 'pix';
            let option_cartao_credito = 'cartao_credito';

            if (option.val() == option_pix) {
                $("#btn_gerar_codigo_pix").css('display', 'block');
                $('#' + option_cartao_credito).hide();
                $("#numero_cartao").prop("disabled", true);
                $("#nome_cartao").prop("disabled", true);
                $("#codigo_cartao").prop("disabled", true);
                $("#codigo_cartao_mobile").prop("disabled", true);
                $("#metodo_pagamento_cartao").val('PIX');
                $("select#forma_pagamento").addClass('desconto-pix');
            }

            if (option.val() == option_cartao_credito) {
                $("#btn_gerar_codigo_pix").css('display', 'none');
                $('#' + option_cartao_credito).show();
                $('#' + option_pix).hide();
                $("#alterar_forma_pagamento").hide();
                $("#numero_cartao").prop("disabled", false);
                $("#nome_cartao").prop("disabled", false);
                PagamentoCtrl.updateSecutiryCodeType();
                $("#metodo_pagamento_cartao").val('CREDITO');
                $("select#forma_pagamento").removeClass('desconto-pix');
            }

            if(option.val() == "") {
                $('#' + option_pix).hide();
                $('#' + option_cartao_credito).hide();
                $("#alterar_forma_pagamento").hide();
            }
        });
    },

    // Verifica se os campos necessários para gerar o código PIX estão OK
    isCamposValidosParaPix: () => {

        let continuar = true;

        let cliente_email = $("input#email").val();
        if(!cliente_email){
            $("input#email").siblings(".invalid-feedback").html("O campo Nome Completo não pode ser vazio!");
            $("input#email").addClass('is-invalid');
            continuar = false;
        }

        let cliente_nome = $("input#nome_cadastro").val();
        if(!cliente_nome){
            $("input#nome_cadastro").siblings(".invalid-feedback").html("O campo Nome Completo não pode ser vazio!");
            $("input#nome_cadastro").addClass('is-invalid');
            continuar = false;
        }

        let cliente_cpf = $("input#cpf_cadastro").val();
        if(!cliente_cpf){
            $("input#cpf_cadastro").siblings(".invalid-feedback").html("O campo CPF não pode ser vazio!");
            $("input#cpf_cadastro").addClass('is-invalid');
            continuar = false;
        }

        let cliente_data_nascimento = $("input#nascimento_cadastro").val();
        if(!cliente_data_nascimento){
            $("input#nascimento_cadastro").siblings(".invalid-feedback").html("O campo Data de nascimento não pode ser vazio!");
            $("input#nascimento_cadastro").addClass('is-invalid');
            continuar = false;
        }

        let cliente_telefone = $("input#tel_cadastro").val();
        if(!cliente_telefone){
            $("input#tel_cadastro").siblings(".invalid-feedback").html("O campo Telefone não pode ser vazio!");
            $("input#tel_cadastro").addClass('is-invalid');
            continuar = false;
        }

        if(continuar == false){
            return false;
        }

        return true;
    },

    /**
    * Responsavel por controlar o botão de copiar código PIX
    * */
    onClickCopyPix: () => {
        $("#copiaCodigoPix").on('click', (event) => {

            let copyText = document.getElementById("codigo_pix").innerText;
            click_copy(copyText);
            return swal('Código PIX copiado', 'O código pix foi copiado para a área de transferência.', 'success');
        });
    },

    /**
    * Para quando o usuário clica em trocar a forma de pagamento na tela de PIX
    * */
    onClickChangeFormaPagamento: () => {
        $("#alterar_forma_pagamento").on('click', (event) => {

            Helpers.loader.show();
            let url = PagamentoCtrl._urlCancelarPix;

            axios.post(url, {}).then(() => {
                window.location.reload();
            });
        });
    },

    /**
     * Quando o usuário que não esta logado informa os campos ele salva na sessão.
     * Alem de ajudar o usuário... Isso evita BUGS no PIX caso o usuário atualizar a pagina e perder os dados depois de
     * gerar o código de pagamento.
     **/
    onChangeCamposCliente: () => {

        let reqAtualizarSessao = () => {
            let cliente = {
                nome: $("#nome_cadastro").val(),
                cpf: $("#cpf_cadastro").val(),
                nascimento: $("#nascimento_cadastro").val(),
                telefone: $("#tel_cadastro").val(),
                email: $("#email").val(),
                rua: $("#rua_cadastro").val(),
                numero: $("#numero_cadastro").val(),
                bairro: $("#bairro_cadastro").val(),
                cidade: $("#cidade_cadastro").val(),
                estado: $("#estado_cadastro").val(),
                cep: $("#cep_cadastro").val(),
            };

            let link = PagamentoCtrl._urlClienteSessao;

            let req = axios.post(link, cliente).then((response) => {
                let data = response.data;
            });
        };

        $("#nome_cadastro").on('change', (event) => {
            reqAtualizarSessao();
        });

        $("#cpf_cadastro").on('change', (event) => {
            reqAtualizarSessao();
        });

        $("#nascimento_cadastro").on('change', (event) => {
            reqAtualizarSessao();
        });

        $("#tel_cadastro").on('change', (event) => {
            reqAtualizarSessao();
        });

        $("#email").on('change', (event) => {
            reqAtualizarSessao();
        });

        $("#rua_cadastro").on('change', (event) => {
            reqAtualizarSessao();
        });

        $("#numero_cadastro").on('change', (event) => {
            reqAtualizarSessao();
        });

        $("#bairro_cadastro").on('change', (event) => {
            reqAtualizarSessao();
        });

        $("#cidade_cadastro").on('change', (event) => {
            reqAtualizarSessao();
        });

        $("#estado_cadastro").on('change', (event) => {
            reqAtualizarSessao();
        });

        $("#cep_cadastro").on('change', (event) => {
            reqAtualizarSessao();
        });
    },  

    // Ativa o evento de onBlur nos campos do cartão de crédito
    // Serve para abrir o campo de parcelas somente apos preencher os dados
    onChangeCamposCartao: () => {

        let numero_cartao = $("#numero_cartao");
        let nome_cartao = $("#nome_cartao");
        let codigo_cartao = $("#codigo_cartao");
        let codigo_cartao_mobile = $("#codigo_cartao_mobile");

        let campos = [numero_cartao, nome_cartao, codigo_cartao, codigo_cartao_mobile];

        for(let campo_index in campos) {
            
            let campo = campos[campo_index];
            campo.on('keypress', (event) => {
                PagamentoCtrl.liberarSelectParcelas();
            });
        }
    },

    // Se os dados do cartão de crédito estiverem válidos essa função libera o select de parcelas
    liberarSelectParcelas: () => {

        let numero_cartao = $("#numero_cartao");
        let nome_cartao = $("#nome_cartao");
        let codigo_cartao = $("#codigo_cartao");
        let codigo_cartao_mobile = $("#codigo_cartao_mobile");

        let select_parcelas = $("#numero_parcelas");

        if(nome_cartao.val().length < 2) {
            return false;
        }

        if(numero_cartao.val().length < 16) {
            return false;
        }

        if(numero_cartao.siblings(".invalid-feedback").html() != "") {
            return false;
        }

        if(!codigo_cartao.prop('disabled')) {

            if(codigo_cartao.val().length < 1) {
                return false;
            }

        } else {

            if(codigo_cartao_mobile.val().length < 1){
                return false;
            }

        }



        select_parcelas.prop('disabled', false);
        return true;
    }
};
