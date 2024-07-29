let DadosFactory = {

    // Chave para os dados dos acompanhantes
    _indexAcompanhantes: "acompanhantes",

    /**
     * Salva um item no localstorage
     *
     * @param key
     * @param data
     */
    setItem: (key, data) => {
        localStorage.setItem(key, JSON.stringify(data));
    },

    /**
     * Retorna o item salvo no localstorage
     *
     * @param key
     * @returns {any}
     */
    getItem: (key) => {
        let data = localStorage.getItem(key);
        return (data === null) ? [] : JSON.parse(data);
    },

    /** Limpa todos os dados do localstorage */
    clearData: () => {
        // Clear all items
        localStorage.clear();
    },

    toArray: (object) => {
        return Object.keys(object).map((key) => {return object[key]});
    },

    /**
     * Salva a lista de acompanhantes
     *
     * @param new_acomp
     */
    saveDadosAcompanhantes: (new_acomp) => {
        // Transforma em array
        new_acomp = DadosFactory.toArray(new_acomp);
        // Recupera os acompanhantes anteriores
        let acompanhantes = DadosFactory.toArray(DadosFactory.getItem(DadosFactory._indexAcompanhantes));
        let length = new_acomp.length;
        let lowercase = (prop) => {return prop.trim().replace(" ", "").toLowerCase()};
        // Monta o novo array de acompanhantes
        for(let i = 0 ; i < length; i++) {
            let index_acomp = 0;
            let acompanhante = new_acomp[i];
            // Verifica se ja tem acompanhante com este nome
            let exists = acompanhantes.filter((acomp, index) => {
                if(lowercase(acomp['nome']) === lowercase(acompanhante['nome'])) {
                    index_acomp = index; return true
                }
            });
            (exists.length) ? $.extend(acompanhantes[index_acomp], acompanhante) : acompanhantes.push(acompanhante);
        }

        // Salva os dados
        DadosFactory.setItem(DadosFactory._indexAcompanhantes, acompanhantes);
    },

    /**
     * Lista dos acompanhantes salvos na sessao
     *
     * @returns {*|any[]}
     */
    getDadosAcompanhantes: () => {
        return DadosFactory.toArray(DadosFactory.getItem(DadosFactory._indexAcompanhantes));
    },

    /** Retorna os dados separados dos acompanhantes */
    getDataOptionsAcompanhantes: () => {
        let acompanhantes = DadosFactory.getDadosAcompanhantes();
        let length = acompanhantes.length;
        let html = {nomes: "", documentos: "", nascimento: ""};
        // Monta a lista com os dados
        for(let i = 0; i < length; i++) {
            let acomp = acompanhantes[i];
            html.nomes += `<li data-index="${i}">${acomp['nome']} <small>${acomp['documento']} - ${acomp['nascimento']}</small></li>`;
            html.documentos += `<li data-index="${i}">${acomp['documento']}</li>`;
            html.nascimento += `<li data-index="${i}">${acomp['nascimento']}</li>`;
        }
        return html;
    },

    /** Carrega as informações dos acompanhantes */
    loadAcompanhantes: () => {
        let $body = $('body');
        // Cria as listas de ajuda
        let acompanhantes = window.DadosFactory.getDataOptionsAcompanhantes();
        if(acompanhantes['nomes'].length) {
            $body.append(`<ul class="list-help" id="nomes_acom">${acompanhantes['nomes']}</ul>`);
            $body.append(`<ul class="list-help" id="doc_acom">${acompanhantes['documentos']}</ul>`);
            $body.append(`<ul class="list-help" id="data_acom">${acompanhantes['nascimento']}</ul>`);
        }
    }
};

module.exports = DadosFactory;
