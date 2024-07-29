module.exports = {

    lazyLoad: () => {
        $(".lazyload").imgLazyLoad();
    },

    niceScroll: () => {
        // ------------------------------------------------------- //
        // Sidebar Scrollbar
        // ------------------------------------------------------ //
        $(".sidebar-scroll").niceScroll({
            cursorcolor: "transparent",
            cursorborder: "transparent",
            cursoropacitymax: 0,
            boxzoom: false,
            autohidemode: "hidden",
            cursorfixedheight: 80
        });
        // ------------------------------------------------------- //
        // Widget Scrollbar
        // ------------------------------------------------------ //
        $(".widget-scroll").niceScroll({
            railpadding: {
                top: 0,
                right: 0,
                left: 0,
                bottom: 0
            },
            scrollspeed: 100,
            zindex: "auto",
            autohidemode: "leave",
            cursorwidth: "4px",
            cursorcolor: "rgba(52, 40, 104, 0.1)",
            cursorborder: "rgba(52, 40, 104, 0.1)"
        });
        // ------------------------------------------------------- //
        // Table Scrollbar
        // ------------------------------------------------------ //
        $(".table-scroll").niceScroll({
            railpadding: {
                top: 0,
                right: 0,
                left: 0,
                bottom: 0
            },
            scrollspeed: 100,
            zindex: "auto",
            autohidemode: "leave",
            cursorwidth: "4px",
            cursorcolor: "rgba(52, 40, 104, 0.1)",
            cursorborder: "rgba(52, 40, 104, 0.1)"
        });
        // ------------------------------------------------------- //
        // Offcanvas Scrollbar
        // ------------------------------------------------------ //
        $(".offcanvas-scroll").niceScroll({
            railpadding: {
                top: 0,
                right: 2,
                left: 0,
                bottom: 0
            },
            scrollspeed: 100,
            zindex: "auto",
            hidecursordelay: 800,
            cursorwidth: "3px",
            cursorcolor: "rgba(52, 40, 104, 0.1)",
            cursorborder: "rgba(52, 40, 104, 0.1)",
            preservenativescrolling: true,
            boxzoom: false
        });
    },

    boostrapSelect: () => {
        let $selects = $("select.boostrap-select-custom"),
            length = $selects.length;
        // Percorre os selects
        for(let i = 0; i < length; i++) {
            let $select = $($selects[i]),
                options = $select.find("option").length,
                placeholder = $select.attr('data-placeholder') || "Procurar registro";
            // Inicializa o select
            $select.selectpicker({
                liveSearch: (options >= 5),
                liveSearchPlaceholder: placeholder
            })
        }
    },

    // Editor markdown
    simpleMarkdown: () => {
        // Recupera os editores
        let $editors = $("textarea.simple-editor"),
            length = $editors.length;
        // Caso possua editor
        if(length > 0) {
            // Carrega o script do editor
            Plugins.loadFileJs("/js/simplemde.min.js", () => {
                // Percorre os textareas para ativar o editor
                for(let i = 0; i < length; i++) {
                    let $textarea = $($editors[i]),
                        value = $textarea.val().trim();
                    // Ativa o editor
                    let simplemde = new SimpleMDE({
                        element: $textarea[0],
                        autoDownloadFontAwesome: false,
                        spellChecker: false,
                        forceSync: true,
                        toolbar: [
                            {
                                name: "bold",
                                action: SimpleMDE.toggleBold,
                                className: "sd-md editor-bold",
                                title: "Negrito",
                            },
                            {
                                name: "italic",
                                action: SimpleMDE.toggleItalic,
                                className: "sd-md editor-italic",
                                title: "Itálico",
                            },
                            {
                                name: "heading-3",
                                action: SimpleMDE.toggleHeading3,
                                className: "sd-md editor-title",
                                title: "Título",
                            },
                            "|",
                            {
                                name: "link",
                                action: SimpleMDE.drawLink,
                                className: "sd-md editor-link",
                                title: "Inserir link",
                            },
                            {
                                name: "unordered-list",
                                action: SimpleMDE.toggleUnorderedList,
                                className: "sd-md editor-list-bulleted",
                                title: "Lista",
                            },

                            {
                                name: "included-list",
                                action: (editor) => editor.codemirror.replaceSelection(" {.included}"),
                                className: "sd-md editor-included-list",
                                title: "Lista com inclusos",
                            },

                            {
                                name: "not-included-list",
                                action: (editor) => editor.codemirror.replaceSelection(" {.not-included}"),
                                className: "sd-md editor-not-included-list",
                                title: "Lista de não inclusos",
                            },

                            {
                                name: "info-list",
                                action: (editor) => editor.codemirror.replaceSelection(" {.info}"),
                                className: "sd-md editor-info-list",
                                title: "Lista informações",
                            },

                            "|",
                            {
                                name: "preview",
                                action: SimpleMDE.togglePreview,
                                className: "sd-md editor-preview-text no-disable",
                                title: "Preview",
                            },
                            {
                                name: "side-by-side",
                                action: SimpleMDE.toggleSideBySide,
                                className: "sd-md editor-vertical-split",
                                title: "Lado a lado",
                            },
                            {
                                name: "fullscreen",
                                action: SimpleMDE.toggleFullScreen,
                                className: "sd-md editor-fullscreen",
                                title: "Tela cheia",
                            },
                        ]
                    });
                    $(document).on('shown.bs.tab', 'a[data-toggle="tab"]', () => {
                        simplemde.codemirror.refresh();
                    });
                }
            });
        }
    },

    // Carrega arquivo JS
    loadFileJs: (file, callback) => {
        let d = document, t = 'script',
            o = d.createElement(t),
            s = d.getElementsByTagName(t)[0];
        o.src = window.location.origin + file;
        if (callback) { o.addEventListener('load', callback, false); }
        o.addEventListener('error', () => {
            swal("Op's erro no carregamento",`Não foi possível carregar o script ${file} atualize a página e tente novamente!`, "error").then(() => {
                // Recarrega a tela
                location.reload();
            });
        }, false);
        s.parentNode.insertBefore(o, s);
    },

    // Data tables
    dataTables: () => {
        // Recupera as tabelas
        let $tables = $("table.data-table"),
            length = $tables.length;
        // Caso possua tabelas
        if(length > 0) {
            // Carrega o script da datatable
            Plugins.loadFileJs("/js/datatables.min.js", () => {
                // Percorre as tabelas
                for (let i = 0; i < length; i++) {
                    let $table = $($tables[i]);
                    $table.DataTable();
                }
            });
        }
    },

    // Carrega o script do Chart JS
    loadChartJs: (callback) => {
        if(typeof Chart === "undefined") {
            Plugins.loadFileJs("/js/chart.min.js", callback);
        } else {
            return callback();
        }
    },

    // Carrega o script moment
    loadMoment: (callback) => {
        if(typeof moment === "undefined") {
            // Carrega o script do calendario
            Plugins.loadFileJs("/js/moment.min.js", callback);
        } else {
            return callback();
        }
    },

    // Carrega os scripts para utilizar os calendarios
    loadLibCalendar: (callback) => {
        Plugins.loadMoment(() => {
            Plugins.loadFileJs("/js/fullcalendar.min.js", callback);
        });
    },

    // Carrega os scripts para utilizar os datepickers
    loadLibDateRanger: (callback) => {
        Plugins.loadMoment(() => {
            if(window.App._loadedDateRangePicker === false) {
                Plugins.loadFileJs("/js/daterangepicker.min.js", callback);
            } else {
                return callback();
            }
        });
    },

    // Salva a aba nas tabs
    saveStateTabs: () => {
        // Salva qual tab foi selecionada no local storage
        $(document).on('shown.bs.tab', 'a[data-toggle="tab"]', (e) => {
            let $this = $(e.currentTarget);
            // Caso nao seja para salvar
            if($this.hasClass('not-save-state')) return;
            // Salva os dados no localstorage
            localStorage.setItem('activeTab', JSON.stringify({
                url: window.location.href,
                tab: $this.attr('href')
            }));
        });
        // Mostra a tab que foi selecionada anteriormente
        $(document).ready(() => {
            let activeTab = localStorage.getItem('activeTab');
            // Verifica se existe a tab
            if(activeTab){
                // Recupera os dados
                activeTab = JSON.parse(activeTab);
                // Verifica se é a tab da URL
                if(activeTab.url === window.location.href) {
                    $('a[href="' + activeTab.tab + '"]:not(.disabled)').tab('show');
                }
            }
        });
    }
};
