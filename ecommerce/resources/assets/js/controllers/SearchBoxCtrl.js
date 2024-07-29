let SearchBox = {

    // Referencia do body
    $body: "",

    // Caixa de pesquisa
    $box: "",

    // Botao para fechar
    $btnClose: "",

    // Status atual da caixa de pesquisa
    _status: false,

    // Inicializacao do controller
    init: () => {

        // Salva as referencias
        SearchBox.$body = $("body");
        SearchBox.$box = $(".search-box");

        // Caso tenha a caixa de pesquisa
        if(SearchBox.$box) {
            // Botao de fechar
            SearchBox.$btnClose = SearchBox.$box.find("[data-action='close-search']");

            // Bind para botoes abrirem a caixa de pesquisa
            SearchBox.bindOnClickOpen();

            // Bind para botoes fecharem a caixa de pesquisa
            SearchBox.bindOnClickClose();
        }

        // Pesquisa no site
        SearchBox.onSearchInput();
    },

    // Click para abrir a caixa de pesquisa
    bindOnClickOpen: () => {
        $("[data-action='open-search-box']").on('click', (event) => {
            event.preventDefault();
            SearchBox.open();
        });
    },

    // Click para fechar a caixa de pesquisa
    bindOnClickClose: () => {
        // Fecha no botao
        SearchBox.$btnClose.on('click', (event) => {
            event.preventDefault();
            SearchBox.close();
        });
        // Fecha no ESC
        $(document).on('keydown', (event) => {
            if (event.which === 27 && SearchBox._status) SearchBox.close();
        });
    },

    // Abre a caixa de pesquisa
    open: () => {
        if(SearchBox._status) return;
        // Abre a caixa
        SearchBox.$box.fadeIn();
        SearchBox._status = true;
        // Remove o scroll da pagina
        let scrollWidth = window.innerWidth - $(document).width();
        SearchBox.$body.css('padding-right', `${scrollWidth}px`);
        SearchBox.$body.css('overflow', 'hidden');
        // Focus input
        SearchBox.$box.find("input").focus();
    },

    // Fecha a caixa de pesquisa
    close: () => {
        if(! SearchBox._status) return;
        // Fecha a caixa
        SearchBox.$box.fadeOut();
        SearchBox._status = false;
        // Coloca o scroll da pagina
        setTimeout(() => {
            SearchBox.$body.css('padding-right', '');
            SearchBox.$body.css('overflow', '');
        }, 200);
    },

    // Pesquisa no site
    onSearchInput: () => {
        let $list_res = $('#autocomplete-list');
        let $input_search = $("input[data-action='search']");
        let base_url = window.location.protocol + "//" + window.location.host + "/";
        // Evento ao digitar no campo
        $input_search.on('keyup', window.Helpers.debounce(() => {
            let pesquisa = $input_search.val().trim();
            let list_html = "";
            // Verifica se existe algo digitado
            if (pesquisa.length > 0) {
                pesquisa = window.Helpers.removeAcentos(pesquisa).toLowerCase();
                let pesquisa_length = pesquisa.length;
                let result = $.getJSON(`${$input_search.attr('data-route')}?q=${pesquisa}`);
                result.done((response) => {
                    let servicos = response, length = servicos.length;
                    if(length) {
                        for (let i = 0; i < length; i++) {
                            let servico = servicos[i], highlight,
                                nome_servico = window.Helpers.removeAcentos(servico['nome']).toLowerCase(),
                                index_pesquisa = nome_servico.indexOf(pesquisa), total_length = index_pesquisa + pesquisa_length;

                            if (index_pesquisa < 0) {
                                highlight = servico['nome'];
                            } else {
                                highlight = `${servico['nome'].substring(0,index_pesquisa)}<b>${servico['nome'].substring(index_pesquisa, total_length)}</b>${servico['nome'].substring(total_length, servico['nome'].length)}`;
                            }

                            list_html += `
                                    <div class="autocomplete-items">
                                        <a href="${base_url + servico.url}" title="${servico.nome}">
                                            <img src="${servico.image}" alt="${servico.nome}"> ${highlight} <br>
                                            <span class="small">${servico.cidade} - R$ ${servico.valor_venda}</span>
                                        </a>
                                    </div>`;
                        }

                        $list_res.html(list_html);
                        $list_res.show();
                    } else {

                        $list_res.html(`<div class="autocomplete-items no-results">Nenhum resultado encontrado para <strong>${pesquisa}</strong>...</div>`);
                        $list_res.show();
                    }
                });
            } else {
                $list_res.hide()
            }
        }, 150));

        // Mostra a lista ao entrar no campo e fecha ao sair
        $input_search.focus((e) => {
            let pesquisa = $(e.currentTarget).val().trim();
            if(pesquisa.length > 0) $list_res.show();
        }).blur(() => {
            setTimeout(() => {
                $list_res.hide()
            }, 150);
        })
    }
};
