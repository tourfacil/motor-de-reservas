let CategoriaCtrl = {

    // Inicializacao do controller
    init: () => {

        // Bind ao alterar a ordenacao no select
        CategoriaCtrl.onChangeSelectOrdenacao();

        // Click no botao para abir o menu de filtros
        CategoriaCtrl.onClickButtonOpenFilters();
    },

    /** Select para ordenacao dos servicos */
    onChangeSelectOrdenacao: () => {
        $("select#listing").on('change', (event) => {
            let $this = $(event.currentTarget);
            window.location.href = $this.val();
        });
    },

    /** Click no botão para remover filtro */
    onClickButtonOpenFilters: () => {
        $("button[data-action='filtros']").on('click', (event) => {
            event.preventDefault();
            let $filtros = $("#filtros");
            if($filtros.hasClass('d-none')) {
                $filtros.css('display', 'none');
                $filtros.removeClass('d-none');
            }
            // Toggle slide
            $filtros.slideToggle();
        });
    },
};
