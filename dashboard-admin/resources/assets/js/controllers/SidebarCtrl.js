let SidebarCtrl = {
    init: () => {
        // Ativa o menu lateral
        SidebarCtrl.activeMenu();
        // Arruma o scroll da sidebar
        SidebarCtrl.correctScroll();
    },

    // Arruma o scroll da sidebar
    correctScroll: () => {
        // On Click Dropdown
        $(".side-navbar a[data-toggle='collapse']").on('click', () => {
            setTimeout(() => {
                $(".sidebar-scroll").getNiceScroll().resize();
            }, 200);
        });
        // On Mouse Enter
        $(".side-navbar.sidebar-scroll").on('mouseenter', () => {
            $(".sidebar-scroll").getNiceScroll().resize();
        });
    },

    // Ativa os icones do menu lateral
    activeMenu: () => {
        let url = window.location.href,
            links_sidebar = $(".side-navbar a.item-menu"),
            links_length = links_sidebar.length;
        // Percorre os links do menu lateral
        for(let i = 0; i< links_length; i++) {
            let $item = $(links_sidebar[i]);
            let url_menu = $item.attr('href').trim();
            // Se o menu estiver na URL
            if(url.indexOf(url_menu) !== -1) {
                // Caso esteja dentro de um dropdown
                let dropdown = $item.parents("ul").attr("id");
                // Verifica se este no dropdown
                if(typeof dropdown !== "undefined") {
                    let $drop = $("a[href='#" + dropdown + "']");
                    // Ativa o item na sidebar
                    $drop.parent().addClass('active');
                    // A url deve ser a mesma
                    if(url === url_menu) {
                        // Ativa o item dentro do dropdown
                        $item.parent().addClass('active');
                        // Abre o dropdown
                        $drop.click();
                        break;
                    }
                } else {
                    $item.parent().addClass('active');
                    break;
                }
            }
        }
    }
};
