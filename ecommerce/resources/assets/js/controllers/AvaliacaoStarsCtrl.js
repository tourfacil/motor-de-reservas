let AvaliacaoStarsCtrl = {

    star0: {},
    star1: {},
    star_selecionada_hover: 0,
    star_selecionada: 0,

    init(page) {
        AvaliacaoStarsCtrl.onHoverStars();
        AvaliacaoStarsCtrl.onClickStar();
    },

    onLoadPageStartStars: () => {
        AvaliacaoStarsCtrl.star_selecionada = $("#star-contador").val();
        AvaliacaoStarsCtrl.desenharStarsSelecionada();
    },

    // Quando o usuário passa o mouse sobre as estrelas
    // Simula o desenho de como vai ficar a seleção e caso e não clique. Volta para o estado original
    onHoverStars: () => {

        for(let i = 1; i <= 5; i++) {
            $(`#star${i}`).mouseover(() => {
                AvaliacaoStarsCtrl.desenharStars(i)
            });

            $(`#star${i}`).mouseout(() => {
                AvaliacaoStarsCtrl.desenharStarsSelecionada();
            });
        }

        star0 = $("#link-star0").text();
        star1 = $("#link-star1").text();
    },

    // Deixa todas as estrelas sem nada selecionado
    clearStars: () => {
        for(let i = 1; i <= 5; i++) {
            $(`#star${i}`).attr('src', star0);
        }
    },

    // Função que desenha as estrelas
    desenharStars: (quantidade) => {

        if(quantidade < AvaliacaoStarsCtrl.star_selecionada_hover) {
            AvaliacaoStarsCtrl.clearStars();
        }

        AvaliacaoStarsCtrl.star_selecionada_hover = quantidade;

        for(let i = 1; i <= quantidade; i++) {
            $(`#star${i}`).attr('src', star1);
        }
    },

    // Atualiza as estrelas para a quantidade selecionada. Usado quando usuário usa hover mas não seleciona nada.
    desenharStarsSelecionada: () => {
        AvaliacaoStarsCtrl.desenharStars(AvaliacaoStarsCtrl.star_selecionada);
    },

    // Quando o usuário clica em uma estrela
    onClickStar: () => {
        for(let i = 1; i <= 5; i++) {
            $(`#star${i}`).click(() => {
                AvaliacaoStarsCtrl.star_selecionada = i;
                $("#star-contador").val(i);
            });
        }
    },
};
