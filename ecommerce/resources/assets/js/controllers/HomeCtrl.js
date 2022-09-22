let HomeCtrl = {

    _urlDestino: "",

    init: (page) => {
        // Salva a url para detalhes do destino
        HomeCtrl._urlDestino = page.getAttribute('data-destino');

        // Slider dos destinos
        HomeCtrl.owlDestinos();

        // Slider de fotos dos destinos
        HomeCtrl.owlBackgroundDestinos();

        // Slider dos servicos
        HomeCtrl.owlServicos();

        // Click nos botoes para alterar o destino
        HomeCtrl.onClickChangeDestino();

        // Carrega o script do insta feed
        window.Helpers.loadFileJs("/js/instafeed.min.js?v=1.3.2", HomeCtrl.onLoadInstaFeed);
    },

    // Callback apos o carregamento do script
    onLoadInstaFeed() {
        $.instagramFeed({
            'username': 'tourfacilbr',
            'on_error': console.error,
            'callback': (data) => {
                if(typeof data !== "undefined") {
                    let fotos = data.edge_owner_to_timeline_media.edges;
                    let length = fotos.length;
                    let html = "<div class='owl-carousel owl-nav-grey owl-nav-container owl-padding owl-instagram'>";
                    for(let i = 0; i < length; i++) {
                        let foto = fotos[i].node;
                        let className = (i <= 3) ? "lazyload" : "owl-lazy";
                        if(! foto.is_video) {
                            html += `<div class="item"><a href="https://www.instagram.com/p/${foto.shortcode}" rel="noopener" target="_blank"><img src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" class="img-fluid ${className}" data-src="${foto.thumbnail_src}" alt="Foto Instagram"><div class="overlay-insta"><i class="iconify" data-icon="jam:instagram"></i></div></a></div>`;
                        }
                    }
                    html += "</div>";

                    // Coloca o HTML na DIV
                    $("#instagram-feed").html(html);
                    // Ativa o slider para as fotos
                    $('.owl-instagram').owlCarousel(window.App.sliderConfig.withButtons);
                }
            },
        });
    },

    // Slider dos destinos
    owlBackgroundDestinos() {
        $('.owl-destinos-bg').owlCarousel({
            animateOut: 'fadeOut',
            autoWidth: false,
            dots: false,
            mouseDrag: false,
            loop: true,
            lazyLoad: true,
            margin: 0,
            nav: false,
            items: 1,
            autoplay: true
        })
    },

    // Slider dos destinos
    owlDestinos() {
        // slider com as fotos dos destinos
        $('.owl-destinos').owlCarousel(window.App.sliderConfig.withButtons);
    },

    // Slider dos servicos
    owlServicos() {
        $('.owl-servicos').owlCarousel(window.App.sliderConfig.withButtons)
    },

    // Click nos botoes para alterar o destino
    onClickChangeDestino() {
        let $destino_content = $("#destino-content");
        let $destino_replace = $("#destino-replace");
        $("button[data-action='trocarDestino']").on('click', (event) => {
            event.preventDefault();
            let $this = $(event.currentTarget);
            // Scroll
            $('html').animate({scrollTop: $destino_content.position().top - 10}, 500);
            // Nao atualiza caso for o mesmo destino
            if($this.hasClass('active')) return;
            // Animacao do container
            $destino_replace.animate({ opacity: 0.0 }, 350);
            // Get para recupera o novo HTML
            let result = $.get(HomeCtrl._urlDestino + "/" + $this.attr('data-destino'));
            result.done((response) => {
                // Troca o botao ativo
                $destino_content.find("button[data-action='trocarDestino'].active").removeClass('active');
                $this.addClass('active');
                // Destroi o slider
                $('.owl-servicos').trigger('destroy.owl.carousel');
                // Altera o html
                $destino_replace.html(response);
                // Animacao do container
                $destino_replace.animate({ opacity: 1 }, 350);
                // Cria o slider
                HomeCtrl.owlServicos();
            });
            result.catch(() => {
                // Animacao do container
                $destino_replace.animate({ opacity: 1 }, 350);
                alert('Não foi possível alterar o destino, tente novamente!');
            });
        })
    },
};
