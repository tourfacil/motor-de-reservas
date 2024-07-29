let RelatorioFornecedorCtrl = {

    // Inicialização do controller
    init: () => {

        //  Cria o checkbox para ingressos autenticados na modal de filtro
        RelatorioFornecedorCtrl.createCheckboxFiltroAutenticados();
    },

    /** Cria o checkbox para ingressos autenticados na modal de filtro */
    createCheckboxFiltroAutenticados: () => {
        // Coloca o checkbox na modal do filtro
        $("#filtro-periodo-modal").find(".col-xl-12.mb-2").append(
            `<div class="mt-4">
                <label for="fornecedor_filtro_relatorio" class="form-control-label">Tipo de data</label>
                <div class="input-group">
                    <span class="input-group-addon addon-secondary left"><i class="la la-suitcase"></i></span>
                    <select id="afiliado_filtro_relatorio" name="tipo_data" class="form-control boostrap-select-custom" required data-required title="Selecione um tipo de data">
                        <option value="VENDA">Data da Venda</option>
                        <option value="UTILIZACAO">Data da Utilização</option>
                    </select>
                    <div class="invalid-feedback"></div>
                </div>
                <br>
                <div class="styled-checkbox">
                    <input type="checkbox" name="autenticados" id="filtro-autenticado" value="true">
                    <label for="filtro-autenticado">Somente ingressos autenticados</label>
                </div>
            </div>`
        );
    }
};
