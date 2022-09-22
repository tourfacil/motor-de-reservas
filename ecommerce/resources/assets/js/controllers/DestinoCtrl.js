let DestinoCtrl = {

    // Inicialização do controller
    init: () => {

        // Configura os sliders dos banners
        DestinoCtrl.sliderBanners();

        // Configura os sliders dos servicos e categoria
        DestinoCtrl.sliderServicos();
    },

    // Configura os sliders dos banners
    sliderBanners: () => {
        $('.banner-slider').owlCarousel({
            loop: false,
            rewind: true,
            lazyLoad: true,
            margin: 0,
            nav: true,
            navText: ['<span class="iconify" data-icon="icons8:angle-left" data-inline="false"></span>', '<span class="iconify" data-icon="icons8:angle-right" data-inline="false"></span>'],
            items: 1,
            dots: true,
            responsive: {
                0: {
                    items: 1, nav: false
                },
                848: {
                    nav: true,
                }
            }
        })
    },

    // Sliders dos servicos e categoria
    sliderServicos: () => {
        $('.owl-servicos').owlCarousel(window.App.sliderConfig.withButtons);
    },
};
