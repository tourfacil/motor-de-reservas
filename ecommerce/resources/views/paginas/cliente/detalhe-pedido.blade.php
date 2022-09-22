@extends('template.master')

@section('title', "Pedido #" . $pedido->codigo)

@section('body')

    @php

        function isReservaFinalizada($reserva) {

            $total_pessoas_adquiridas = $reserva->quantidade;
            $total_pessoas_cadastradas = $reserva->dadoClienteReservaPedido->count();

            $is_finalizada = true;

            if($reserva->servico->info_clientes == "SOLICITA_INFO_CLIENTES") {

                if($total_pessoas_adquiridas != $total_pessoas_cadastradas) {
                    $is_finalizada = false;
                }
            }

            $quantidade_campos = $reserva->servico->camposAdicionaisAtivos->count();
            $quantidade_campos_cadastrados = $reserva->campoAdicionalReservaPedido->count();

            if($quantidade_campos != $quantidade_campos_cadastrados) {
                $is_finalizada = false;
            }

            return $is_finalizada;
        }

    @endphp


    {{-- Navbar --}}
    @include('template.navbar')

    <main class="bg-light" data-controller="PedidoCtrl">
        <div class="container pb-5">
            {{-- breadcrumb --}}
            <nav class="custom-bread py-3" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item d-none d-md-flex"><a href="{{ route('ecommerce.index') }}">Tour Fácil</a></li>
                    <li class="breadcrumb-item first-mobile"><a href="{{ route('ecommerce.cliente.pedidos.index') }}">Pedidos realizados</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Pedido: #{{ $pedido->codigo }}</li>
                </ol>
            </nav>
            <div class="p-3 p-sm-4 bg-white shadow-sm radius-10">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h1 class="font-weight-bold h2 mb-1">Resumo do pedido</h1>
                        <p class="line-initial text-muted">Pedido realizado no dia: {{ $pedido->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="d-none d-md-block"><h3>#{{ $pedido->codigo }}</h3></div>
                </div>
                <hr class="blue mt-0 mb-3">
                {{-- Detalhe do pedido realizado no cartao --}}
                @if($pedido->metodo_pagamento == $e_cartao)
                    @include('paginas.cliente.partials.detalhe-cartao')
                @endif
            </div>

            <div id="imprimir" class="p-3 p-sm-4 bg-white shadow-sm radius-10 mt-3">
                <h2 class="font-weight-bold h2 mb-1">Serviços adquiridos</h2>
                <p class="line-initial text-muted">Baixe ou imprima seus vouchers</p>
                <hr class="blue mt-0 mb-3">
                <div class="table-responsive">
                    <table class="table table-striped table-borderless mb-0">
                        <thead>
                        <tr>
                            <th class="text-center font-weight-medium">Reserva</th>
                            <th class="font-weight-medium">Serviço adquirido</th>
                            <th class="text-center font-weight-medium">Utilização</th>
                            <th class="text-center font-weight-medium">Status</th>
                            <th class="text-center font-weight-medium">Informações</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($pedido->reservas as $reserva)
                            @php
                                //dd($reserva);
                            @endphp
                            <tr>
                                <td data-label="#" class="text-center text-primary font-weight-medium">#{{ $reserva->voucher }}</td>
                                <td data-label="Serviço" class="text-center text-xl-left">({{ $reserva->quantidade }}x) {{ $reserva->servico->nome }}</td>
                                <td data-label="Utilização" class="text-center">{{ $reserva->agendaDataServico->data->format('d/m/Y') }}</td>
                                <td data-label="Status" class="text-center text-{{ $reserva->cor_status }} text-uppercase">
                                    <strong class="font-weight-medium">{{ $reserva->status_reserva }}</strong>
                                </td>
                                @if(isReservaFinalizada($reserva))
                                    @if($reserva->status == $e_reserva_ativa)
                                        <td class="text-center pl-0">
                                            {{-- Verifica se é Snowland --}}
                                            @if($reserva->servico->integracao == \TourFacil\Core\Enum\IntegracaoEnum::SNOWLAND)
                                                <button type="button" data-action="print_voucher" class="btn btn-success btn-rounded text-uppercase pb-2 px-4"
                                                        data-url="{{ $reserva->snowlandVoucher->url_voucher ?? "" }}">Imprimir voucher <span class="iconify ml-1" data-icon="jam:printer"></span></button>
                                            @elseif($reserva->servico->integracao == \TourFacil\Core\Enum\IntegracaoEnum::EXCEED_PARK)
                                                <button type="button" data-action="print_voucher" class="btn btn-success btn-rounded text-uppercase pb-2 px-4"
                                                        data-url="{{ $reserva->exceedVoucher->url_voucher ?? "" }}">Imprimir voucher <span class="iconify ml-1" data-icon="jam:printer"></span></button>
                                            @elseif($reserva->servico->integracao == \TourFacil\Core\Enum\IntegracaoEnum::OLIVAS)
                                                <button type="button" data-action="print_voucher" class="btn btn-success btn-rounded text-uppercase pb-2 px-4"
                                                        data-url="{{ $reserva->olivasVoucher->url_voucher ?? "" }}">Imprimir voucher <span class="iconify ml-1" data-icon="jam:printer"></span></button>
                                            @elseif($reserva->servico->integracao == \TourFacil\Core\Enum\IntegracaoEnum::MINI_MUNDO)
                                                <button type="button" data-action="print_voucher" class="btn btn-success btn-rounded text-uppercase pb-2 px-4"
                                                    data-url="{{ $reserva->miniMundoVoucher->url_voucher ?? "" }}">Imprimir voucher <span class="iconify ml-1" data-icon="jam:printer"></span></button>
                                            @else
                                                <button type="button" data-action="print_voucher" class="btn btn-success btn-rounded text-uppercase pb-2 px-4"
                                                        data-url="{{ route('ecommerce.cliente.pedidos.print', $reserva->voucher) }}">Imprimir voucher <span class="iconify ml-1" data-icon="jam:printer"></span></button>
                                            @endif
                                        </td>
                                    @else
                                        <td class="text-center pl-0">
                                            <button class="btn btn-secondary text-uppercase pb-2 px-4 btn-rounded disabled" disabled>Imprimir voucher</button>
                                        </td>
                                    @endif
                                @else
                                    <td class="text-center pl-0">
                                        <button id="finalizar-{{$reserva->id}}" onclick="modalAcomps(event)" style="background-color: #f21729; border-color:#f21729" type="button" class="btn btn-success btn-rounded text-uppercase pb-2 px-4" id_reserva="{{$reserva->id}}">Finalizar reserva <span class="iconify ml-1" data-icon="jam:printer"></span></button>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <th colspan="4" class="text-left">
                                <span class="font-weight-medium">Total de {{ $pedido->reservas->count() }} serviço(s) adquirido(s)</span>
                            </th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </main>

    @include('paginas.modais.lista-acompanhantes-finalizar')
    @include('paginas.modais.campo-adicional-finalizar')
    
    <script>

        let link_informacao_finalizacao = "{{ Route('ecommerce.cliente.pedidos.informacao-finalizacao') }}";
        let data = {};
        let acompanhantes = [];
        let campos_adicionais_lista = [];

        let modalAcomps = (event) => {

            /* Try Catch para garantir que o carregamento do swal não bugue devido a ser chamado várias vezes */
            try {
                Helpers.loadSweetAlert();
            } catch (error) {

            }

            let id_reserva = $(`#${event.target.id}`).attr('id_reserva');
            let link = `${link_informacao_finalizacao}?reserva_id=${id_reserva}`;

            let response = axios.get(link).then((response) => {
                data = response.data;
                abrirModalAcomps(data.quantidades);
            });
        };

        let abrirModalAcomps = (quantidades) => {

            if(data.servico.info_clientes != "SOLICITA_INFO_CLIENTES") {
                changeAcompToCampoToFinish();
                return;
            }

            let modal_acompanhantes = $("#modal-acompanhantes");
            let contador = 0;
            let html = "";

            for(let i = 0; i < quantidades.length; i++) {

                let quantidade = quantidades[i];

                for(let j = 0; j < quantidade.quantidade; j++) {

                    html +=
                    `
                        <div class="list-acompanhantes mb-2">
                            <h6 class="font-weight-medium h5">${contador + 1}° Acompanhante - ${quantidade.variacao_servico.nome}</h6>
                            <div class="row">
                                <div class="form-group nome_acompanhante col-sm-12 col-lg-4">
                                    <label for="nome_${i}">Nome completo*</label>
                                    <input type="text" class="form-control" id="nome_${contador}" name="acompanhantes[${contador}][nome]" data-auto-capitalize data-nome-completo="true" required data-min="5" data-required placeholder="Nome e sobrenome" autocomplete="off" title="Nome completo" data-list="nomes_acom" data-callback="nome">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="form-group doc_acompanhante col-sm-12 col-md-6 col-lg-4">
                                    <label for="documento_${i}">N° do documento*</label>
                                    <input type="text" class="form-control" id="documento_${contador}" name="acompanhantes[${contador}][documento]" required data-min="5" data-required placeholder="Número CPF ou RG" title="Documento" autocomplete="off" data-list="doc_acom" data-callback="documento">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="form-group nasc_acompanhante col-sm-12 col-md-6 col-lg-4">
                                    <label for="nascimento_${i}">Nascimento*</label>
                                    <input type="tel" class="form-control vanillaMask" id="nascimento_${contador}" name="acompanhantes[${contador}][nascimento]" required data-min="9" data-required placeholder="DD/MM/AAAA" data-mask="date" title="Nascimento" autocomplete="off" maxlength="10" autocomplete="off" data-list="data_acom" data-callback="nascimento">
                                    <div class="invalid-feedback"></div>
                                    <input type="hidden" id="variacao_${contador}" name="acompanhantes[${contador}][variacao_servico_id]" value="${quantidade.variacao_servico_id}">
                                    <input type="hidden" id="reserva_${contador}" name="acompanhantes[${contador}][reserva_pedido_id]" value="${quantidade.reserva_pedido_id}">
                                </div>
                            </div>
                        </div>
                    `;

                    contador++;
                    modal_acompanhantes.find(".fields-input").html(html);
                    Helpers.vanillaMask();
                }
            }

            modal_acompanhantes.jqmodal();
        };

        /* Pega os dados dos inputs e salva os acompanhantes dentro da variavel global acompanhantes */
        let salvarAcomps = (event) => {

            /* Variavel com as quantidades (Variações) */
            let quantidades = data.quantidades;

            /* Array para guardar os clientes */
            let acomps = [];

            /* Contador para salvar em qual quantidade (Variação) estamos */
            let contador = 0;

            /* Passa todas as quantidades (Variações) */
            for(let i = 0; i < quantidades.length; i++) {

                /* Atribui a quantidade (Variação atual a uma variavel) */
                let quantidade = quantidades[i];

                /* Repete o número de pessoas que tem com aquela quantidade (Variação) */
                for(let j = 0; j < quantidade.quantidade; j++) {

                    /* Cria um acomp vazio para guardar as informações */
                    let acomp = {};

                    /* Salva o nome do acomp */
                    acomp.nome = $(`#nome_${contador}`).val();

                    /* Verifica se o nome é valido ou retorna */
                    if(isNomeOuDocumentoAcompValido(acomp.nome) == false) {
                        return alerta("Nome inválido", `O nome do acompanhante ${contador + 1} deve conter pelo menos 5 caracteres.`);
                    }

                    /* Salva o documento do acomp */
                    acomp.documento = $(`#documento_${contador}`).val();

                    /* Verifica se o documento é valido ou retorna */
                    if(isNomeOuDocumentoAcompValido(acomp.documento) == false) {
                        return alerta("Documento inválido", `O documento do acompanhante ${contador + 1} deve conter pelo menos 5 caracteres.`);
                    }

                    /* Salva a data de nascimento do acomp */
                    acomp.nascimento = $(`#nascimento_${contador}`).val();

                    /* Verifica se a data de nascimento é valida ou retorna */
                    if(isNascimentoAcompValido(acomp.nascimento) == false) {
                        return alerta("Data de nascimento inválida", `A data de nascimento do acompanhante ${contador + 1} deve ser válida.`);
                    }

                    /* Salva a variação_id */
                    acomp.variacao_servico_id = $(`#variacao_${contador}`).val();

                    /* Salva a reserva_id */
                    acomp.reserva_pedido_id = $(`#reserva_${contador}`).val();

                    /* Guarda o acomp no array */
                    acomps.push(acomp);

                    /* Aumenta o contador */
                    contador++;
                }
            }

            /* Salva na variavel global, acompanhantes todos os acomps registrados na função */
            acompanhantes = acomps;

            /* Verifica se tem campos adicionais para ser preenchidos
            Caso tenha: Abre a modal de preenchimento
            Caso não tenha: Submete a requisição */
            changeAcompToCampoToFinish();
        };

        /* Verifica se é necessário preencher os campos adicionais, ou se pode submeter */
        let changeAcompToCampoToFinish = () => {

            /* Salva os campos adicionais do serviço em uma variavel */
            let campos_adicionais = data.servico.campos_adicionais_ativos;

            /* Verifica se tem campos
            Caso tenha: Abre a modal para preencher
            Caso não tenha: Subteme o formulario */
            if(campos_adicionais.length > 0) {
                abrirModalCampo();
            } else {
                submeterInformacoes();
            }
        };

        let abrirModalCampo = () => {

            let campos_adicionais = data.servico.campos_adicionais_ativos;

            let $modal_adicionais = $("#modal-campo-adicional");
            let html = "";

            for(let i = 0; i < campos_adicionais.length; i++) {

                let campo = campos_adicionais[i];
                let classGrid = (i === 0) ? "col-sm-12" : "col-sm-12 col-md-6";
                let required = "", info_required = "";
                if(campo['obrigatorio'] === "SIM") {
                    required = "data-required required"; info_required = "*";
                }

                html +=
                    `<div class="form-group ${classGrid}">
                        <label for="adicional_${campo['id']}">${campo['campo']}${info_required}</label>
                        <input type="text" class="form-control campos" numero="${i}" id="adicional_${campo['id']}" name="adicionais[${i}][informacao]" data-min="1" ${required} placeholder="${campo['placeholder']}" title="${campo['campo']}">
                        <span class="invalid-feedback"></span>
                        <input type="hidden" id="ad-${i}" name="adicionais[${i}][campo_adicional_servico_id]" value="${campo['id']}">
                        <input type="hidden" id="adr-${i}" name="adicionais[${i}][campo_adicional_servico_id]" value="${data.reserva.id}">
                    </div>`;

                $modal_adicionais.find(".fields-input").html(html);
                $modal_adicionais.jqmodal();
            }
        };

        /* Pega o conteudo dos inputs dos campos adicionais e salva para a req */
        let salvarCampos = () => {

            /* Busca todos os inputs de campos-adicionais */
            let campos_adicionais = $(".campos");

            /* Array para guadar os campos que serão retirados dos inputs */
            let campos = [];

            /* Roda todos os camos */
            for(let i = 0; i < campos_adicionais.length; i++) {

                /* Objeto de campo vazio para colocar os dados */
                let campo = {};

                /* Atribui o campo para uma variavel */
                let campo_adicional = campos_adicionais.eq(i);

                /* Atribui o campo com o campo_adicional_id a uma variavel */
                let campo_adicional_id = $(`#ad-${campo_adicional.attr('numero')}`);

                /* Atribui o campo reserva_servico_id a uma variavel */
                let campo_adicional_reserva_id = $(`#adr-${campo_adicional.attr('numero')}`);

                /* Pega os valor da variavel de informacao de campo no objeto campo */
                campo.informacao = campo_adicional.val();

                /* Verifica se o campo adicional é valido ou retorna */
                if(isCampoAdicionalValido(campo.informacao) == false) {
                    return alerta("Campo adicional inválido", `O(s) campo(s) adicionais devem conter pelo menos 4 caracteres.`);
                }

                /* Pega as informações de campo_id e reserva_id e salva no objeto campo */
                campo.campo_adicional_servico_id = campo_adicional_id.val();
                campo.reserva_pedido_id = campo_adicional_reserva_id.val();

                /* Coloca o objeto campo dentro da lista de campos */
                campos.push(campo);

                /* Coloca a lista de campos na variavel global campos_adicionais_lista */
                campos_adicionais_lista = campos;
            }

            /* Chama a função para submeter as informações */
            submeterInformacoes();
        };

        /* Serve para subtemer e registrar os dados informados pelo cliente */
        let submeterInformacoes = () => {

            /* Abre a modal de carregamento */
            Helpers.loader.show();

            /* Monta um objeto de requisião com as informações coletadas */
            let req = {
                acompanhantes: acompanhantes,
                campos_adicionais: campos_adicionais_lista,
            };

            /* Envia o post para registrar as informações e quando chega a resposta ele atualiza a página */
            axios.post(link_informacao_finalizacao, req).then((response) => {
                window.location.reload();
            });
        };

        let isNomeOuDocumentoAcompValido = (nome) => {
            if(nome.length > 4) {
                return true;
            }
            return false;
        };

        let isNascimentoAcompValido = (nascimento) => {
            if(nascimento.length == 10) {
                return true;
            }
            return false;
        };

        let isCampoAdicionalValido = (campo_adicional) => {
            if(campo_adicional.length > 3) {
                return true;
            }
            return false;
        };

        let alerta = (titulo, texto) => {
            swal(titulo, texto, 'warning');
        };

    </script>

    @include('template.footer')
@endsection
