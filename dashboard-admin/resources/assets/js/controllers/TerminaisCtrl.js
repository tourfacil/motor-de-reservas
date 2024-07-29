let TerminaisCtrl = {

    // Inicializacao do controller
    init: () => {

        // OnClick para editar o usuaario
        TerminaisCtrl.onClickButtonEditUser();

        // OnClick no botao para desativar o usuario
        TerminaisCtrl.onClickButtonDesativarUsuario();

        // OnClick para reativar o usuario
        TerminaisCtrl.onClickButtonAtivarUsuario();

        // OnClick no botao para ver os detalhes do historico
        TerminaisCtrl.onClickButtonViewHistory();

        // OnClick detalhes previsao de pagamento
        TerminaisCtrl.onClickVerReservasPrevisao();
    },

    // OnClick detalhes previsao de pagamento
    onClickVerReservasPrevisao: () => {
        $("[data-action='view-previsao']").on('click', (event) => {
            event.preventDefault();
            let $this = $(event.currentTarget),
                url = $this.attr('href');
            // Loader
            App.loader.show();
            // Recupera os dados do campo
            axios.get(url).then((response) => {
                response = response.data;
                // Loader
                App.loader.hide();
                // Modal
                let $modal = $("#view-comissao");
                // Cria a tabela e coloca os valores na tela
                TerminaisCtrl.createTableVendasComissao(response, $modal);
                // Abre a modal de edição
                $modal.modal('show');
            });
        });
    },

    // Cria o HTML da tabela com as reservas
    createTableVendasComissao: (dados, $modal) => {
        let table = "", quantidade = 0,
            valor_vendido = 0, valor_pendente = 0, valor_cancelado = 0, valor_comissao = 0;
        let length = dados['vendas'].length;

        // Titulo da modal
        $modal.find(".modal-title").html(`Detalhes comissão ${dados['mes']}`);
        // Monta a tabela com os dados das reservas
        for(let i = 0; i < length; i++) {
            let reserva = dados['vendas'][i];
            quantidade++;
            // valores vendidos e comissao
            valor_vendido += parseFloat(reserva['reserva_pedido']['valor_total']);
            // Valores por status de pagamento
            if(reserva['status'] === dados['pago']) {
                valor_comissao += parseFloat(reserva['comissao']);
            } else if(reserva['status'] === dados['cancelado']) {
                valor_cancelado += parseFloat(reserva['comissao']);
            } else if(reserva['status'] === dados['aguardando']) {
                valor_pendente += parseFloat(reserva['comissao']);
            }
            // Data da compra
            let comprado_em = reserva['created_at'].split(' ')[0].split("-");
            let cor_status = dados['cores'][reserva['status']];
            // HTMl da tabela
            table +=
                `<tr>
                    <td class="text-primary">${ i + 1 }</td>
                    <td>
                        <a href="${dados['url_reserva']}/${reserva['reserva_pedido']['voucher']}" target="_blank" class="text-secondary">#${ reserva['reserva_pedido']['voucher'] }</a>
                    </td>
                    <td class="text-primary">(${reserva['reserva_pedido']['quantidade']}x) ${ reserva['reserva_pedido']['servico']['nome'].slice(0, 35)}...</td>
                    <td class="text-primary text-center">R$ ${ window.App.formataValor(reserva['reserva_pedido']['valor_total'], 2) }</td>
                    <td class="text-center"><strong class="text-${ cor_status }">R$ ${ window.App.formataValor(reserva['comissao'], 2) }</strong></td>
                    <td class="text-center"><strong class="text-${ cor_status } text-capitalize">${ reserva['status'].toLowerCase() }</strong></td>
                    <td class="text-center text-primary">${ comprado_em[2] }/${ comprado_em[1] }/${ comprado_em[0] }</td>
                </tr>`
        }

        // Coloca a tabela na modal
        $modal.find("table tbody").html(table);
        // Coloca o valor total vendido
        $modal.find("[data-text='vendido']").html(`R$ ${ window.App.formataValor(valor_vendido, 2) }`);
        $modal.find("[data-text='comissao']").html(`R$ ${ window.App.formataValor(valor_comissao, 2) }`);
        $modal.find("[data-text='comissao_pendente']").html(`R$ ${ window.App.formataValor(valor_pendente, 2) }`);
        $modal.find("[data-text='comissao_cancelada']").html(`R$ ${ window.App.formataValor(valor_cancelado, 2) }`);
        $modal.find("[data-text='reservas']").html(`${quantidade} reserva(s)`);
    },

    // OnClick para reativar o usuario
    onClickButtonAtivarUsuario: () => {
        $("[data-action='activate-user']").on('click', (event) => {
            event.preventDefault();
            let $this = $(event.currentTarget),
                url = $this.attr('href'), usuario_id = $this.attr('data-id');
            // Loader
            App.loader.show();
            // Post para reativar o usuario
            axios.put(url, {usuario_id: usuario_id}).then((response) => {
                // Loader
                App.loader.hide();
                // Verifica o resultado
                if(response['data']['action']['result']) {
                    // Mensagem de sucesso
                    swal(response['data']['action']['title'], response['data']['action']['message'], "success").then(() => {
                        // Loader
                        App.loader.show();
                        // Recarrega a tela
                        location.reload();
                    });
                } else {
                    swal(response['data']['action']['title'], response['data']['action']['message'], "error");
                }
            });
        });
    },

    // Click para editar o usuario
    onClickButtonEditUser: () => {
        $("[data-action='edit-user']").on('click', (event) => {
            event.preventDefault();
            let $this = $(event.currentTarget),
                url = $this.attr('href');
            // Loader
            App.loader.show();
            // Recupera os dados do campo
            axios.get(url).then((response) => {
                // Loader
                App.loader.hide();
                response = response.data;
                // Modal
                let $modal = $("#edit-user");
                // Limpa o formulario
                $modal.find("form").trigger('reset');
                $modal.find("input[type='checkbox']").attr('checked', false);
                // Coloca os valores nos campos
                $modal.find("#edit_nome_usuario").val(response['nome']);
                $modal.find("#edit_email_usuario").val(response['email']);
                $modal.find("input[name='usuario_id']").val(response['id']);
                // Abre a modal de edição
                $modal.modal('show');
            });
        });
    },

    /** Altera o valor do input para desativar o usuario e envia o formulario */
    onClickButtonDesativarUsuario: () => {
        $("button[data-action='desativar']").on('click', (event) => {
            event.preventDefault();
            // Recupera o input de desativar
            let $input = $("input[name='desativar_usuario']");
            // Coloca o valor no input para desativar
            $input.val("on");
            // Envia o formulario
            $input.parents("form").trigger('submit');
        });
    },

    // Funcionalidade do mapa e do autocomplete
    initMap: () => {
        // Mapa do google
        let $map_google = $("#google-map"),
            lat = parseFloat($map_google.attr('data-lat')) || -29.3774968,
            lng = parseFloat($map_google.attr('data-lng')) || -50.8710002;
        let map = new google.maps.Map($map_google[0], {
            center: {lat: lat, lng: lng},
            zoom: 16,
            scrollwheel: false,
            draggable: false,
            mapTypeControl: false,
            disableDefaultUI: false,
            gestureHandling: 'none',
            zoomControl: true,
            scaleControl: false,
            streetViewControl: false
        });

        // Campo para autocomplete
        let $input = $("#search_google");
        // Bind mapa com autocomplete
        let autocomplete = new google.maps.places.Autocomplete($input[0]);

        // Bind the map's bounds (viewport) property to the autocomplete object,
        // so that the autocomplete requests use the current map bounds for the
        // bounds option in the request.
        autocomplete.bindTo('bounds', map);

        // Set the data fields to return when the user selects a place.
        autocomplete.setFields([
            'address_components', 'geometry', 'icon', 'name'
        ]);

        let infowindow = new google.maps.InfoWindow();
        let infowindowContent = document.getElementById('infowindow-content');
        infowindow.setContent(infowindowContent);
        let marker = new google.maps.Marker({
            map: map,
            anchorPoint: new google.maps.Point(0, -29)
        });

        // Retira o autocomplete padrao do campo
        $input.on('focus', () => {$input.attr('autocomplete', 'nope');});

        // Bind para carregar o autocomplete da Google
        autocomplete.addListener('place_changed', function() {

            infowindow.close();
            marker.setVisible(false);
            let place = autocomplete.getPlace();
            if (!place.geometry) {
                // User entered the name of a Place that was not suggested and
                // pressed the Enter key, or the Place Details request failed.
                window.alert("No details available for input: '" + place.name + "'");
                return;
            }

            // If the place has a geometry, then present it on a map.
            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
            } else {
                map.setCenter(place.geometry.location);
                map.setZoom(17);  // Why 17? Because it looks good.
            }
            marker.setPosition(place.geometry.location);
            marker.setVisible(true);

            let address = '';
            let dados_endereco = {localname: place.name};
            if (place.address_components) {
                let length = place.address_components.length;
                for(let i = 0; i < length; i++) {
                    let component = place.address_components[i];
                    if(component['types'][0] === "street_number") dados_endereco.number = component['long_name'] || "00";
                    if(component['types'][0] === "route") dados_endereco.address = component['long_name'];
                    if(component['types'][0] === "sublocality_level_1") dados_endereco.neighboard = component['short_name'];
                    if(component['types'][0] === "administrative_area_level_2") dados_endereco.city = component['long_name'];
                    if(component['types'][0] === "administrative_area_level_1") dados_endereco.estate = component['short_name'];
                    if(component['types'][0] === "postal_code") dados_endereco.zip = component['short_name'];
                    if(component['types'][0] === "country") dados_endereco.country = component['long_name'];
                }
                // Monta o endereço no mapa
                address = `${dados_endereco.address}, ${dados_endereco.number || "00"} -<br>${dados_endereco.neighboard}<br>${dados_endereco.city} - `;
                address += `${dados_endereco.estate}<br>${dados_endereco.zip}<br>${dados_endereco.country}`;
            }

            infowindowContent.children['place-name'].innerHTML = place.name;
            infowindowContent.children['place-address'].innerHTML = address;
            infowindow.open(map, marker);

            // Latitude e longitude do local
            let new_lat = place.geometry.location.lat().toString().substring(0,11);
            let new_lng = place.geometry.location.lng().toString().substring(0,11);

            // Coloca os valores nos inputs
            $("input[name='nome_local']").val(dados_endereco.localname);
            $("input[name='endereco']").val(`${dados_endereco.address}, ${dados_endereco.number || "00"} - ${dados_endereco.neighboard}`);
            $("input[name='cidade']").val(dados_endereco.city);
            $("input[name='cep']").val(dados_endereco.zip);
            $("input[name='geolocation']").val(new_lat + "," + new_lng);

            // Seleciona o estado
            let $select_uf = $("select#estado");
            $select_uf.find("option[data-uf='" + dados_endereco.estate +"']").attr('selected', true);
            $select_uf.selectpicker('refresh');
        });

        // Caso seja na página de edição
        setTimeout(() => {
            if(typeof window.maps !== "undefined") {
                let address = `${window.maps['endereco']}<br>${window.maps['cidade']} - `;
                address += `${window.maps['estado']}<br>${window.maps['cep']}<br>Brasil`;
                // Latitude e longitude do mapa
                let latLng = new google.maps.LatLng(lat, lng);
                // Marker
                infowindowContent.children['place-name'].innerHTML = window.maps['nome_local'];
                infowindowContent.children['place-address'].innerHTML = address;
                // Mostra o marker no mapa
                marker.setPosition(latLng);
                marker.setVisible(true);
                infowindow.open(map, marker);
            }
        }, 500)
    },

    // Click para editar o usuario
    onClickButtonViewHistory: () => {
        $("[data-action='view-history']").on('click', (event) => {
            event.preventDefault();
            let $this = $(event.currentTarget),
                url = $this.attr('href');
            // Loader
            App.loader.show();
            // Recupera os dados do campo
            axios.get(url).then((response) => {
                response = response.data;
                // Loader
                App.loader.hide();
                // Modal
                let $modal = $("#view-history");
                let payload = response['payload'];
                // Coloca os textos na modal
                for (let item in payload) {
                    if(payload.hasOwnProperty(item)) {
                        $("[data-text='" + item + "']").html(payload[item]);
                    }
                }

                // Data que foi realizado a conexao
                let data_historico = response['created_at'].split("-");
                let dia_historico = data_historico[2].split(" ");

                // Data e hora da conexao
                $("[data-text='date']").html(`${dia_historico[0]}/${data_historico[1]}/${data_historico[0]} ${dia_historico[1]}`);

                // Mapa do terminal
                let localizacao = payload['location'];
                let $mapaModal = $modal.find("#mapa-termina-conexao");
                // Mapa do Google
                let map = new google.maps.Map($mapaModal[0], {
                    center: {lat: parseFloat(localizacao['latitude']), lng: parseFloat(localizacao['longitude'])},
                    zoom: 15,
                    scrollwheel: false,
                    draggable: false,
                    mapTypeControl: false,
                    disableDefaultUI: false,
                    gestureHandling: 'none',
                    zoomControl: true,
                    scaleControl: false,
                    streetViewControl: false
                });
                // Marcador
                let marker = new google.maps.Marker({
                    map: map,
                    anchorPoint: new google.maps.Point(0, -29)
                });
                // Posicao do marcador
                marker.setPosition({lat: parseFloat(localizacao['latitude']), lng: parseFloat(localizacao['longitude'])});
                // Abre a modal de detalhes
                $modal.modal('show');
            });
        });
    },
};
