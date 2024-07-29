@extends('template.header')

@section('title', 'Editar Desconto')

@section('content')

    <div class="row">
        <div class="page-header">
            <div class="d-flex align-items-center">
                <h2 class="page-header-title text-gradient-01">Editar Desconto {{ $desconto->nome_publico }}</h2>
                <div>{{ Breadcrumbs::render('app.descontos.desconto.edit') }}</div>
            </div>
        </div>
    </div>


    <div class="col-12">
        <div class="tab-content">
            <div class="tab-pane active" id="cadastro">
                <div class="widget has-shadow">
                    <div class="widget-body pl-lg-5 pr-lg-5">
                        <form id="form-desconto" name="ficha-cadastro" method="POST" action="{{ Route('app.descontos.desconto.update') }}" class="form-horizontal" onsubmit="submit(event)">
                            @CSRF
                            <div class="ml-auto mt-4 mb-4">
                                <div class="section-title mr-auto">
                                    <h3>01. Dados do desconto</h3>
                                    <p class="mt-1">Informações para a aplicação do desconto</p>
                                </div>
                            </div>
                            <div class="form-group row">

                                <div class="col-xl-4 mb-3">
                                    <label for="nome_interno" class="form-control-label">Nome interno</label>
                                    <input id="nome_interno" type="text" class="form-control" placeholder="Nome não apresentado ao cliente" required
                                           data-required data-min="3" title="Nome Interno" name="nome_interno" autocomplete="off" value="{{ $desconto->nome_interno }}">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-xl-4 mb-3">
                                    <label for="banco" class="form-control-label">Desconto NET</label>
                                    <select id="desconto_net" name="tipo_desconto_fornecedor" class="form-control boostrap-select-custom" required >
                                        <option value="UNDEFINED">Selecione uma opção</option>
                                        <option {{ ($desconto->tipo_desconto_fornecedor == \TourFacil\Core\Enum\Descontos\TipoDesconto::NET) ? 'selected' : '' }} value="{{ \TourFacil\Core\Enum\Descontos\TipoDesconto::NET }}">ATIVADO</option>
                                        <option {{ ($desconto->tipo_desconto_fornecedor == \TourFacil\Core\Enum\Descontos\TipoDesconto::VENDA) ? 'selected' : '' }} value="{{ \TourFacil\Core\Enum\Descontos\TipoDesconto::VENDA }}">DESATIVADO</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-xl-4 mb-3">
                                    <label for="status" class="form-control-label">Status</label>
                                    <select id="status" name="status" class="form-control boostrap-select-custom" required>
                                        <option {{ ($desconto->status == \TourFacil\Core\Enum\Descontos\StatusDesconto::ATIVO) ? 'selected' : '' }} value="{{ \TourFacil\Core\Enum\Descontos\StatusDesconto::ATIVO }}">ATIVO</option>
                                        <option {{ ($desconto->status == \TourFacil\Core\Enum\Descontos\StatusDesconto::INATIVO) ? 'selected' : '' }} value="{{ \TourFacil\Core\Enum\Descontos\StatusDesconto::INATIVO }}">INATIVO</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xl-3 mb-3">
                                    <label for="banco" class="form-control-label">Serviço</label>
                                    <div class="input-group">
                                        <select id="select-servico" name="servico_id" class="form-control boostrap-select-custom" required>
                                            <option value="UNDEFINED">Selecione um serviço</option>
                                            @FOREACH($servicos as $servico)
                                                <option {{ ($desconto->servico_id == $servico->id) ? 'selected' : '' }} value="{{$servico->id}}">{{$servico->nome}}</option>
                                            @ENDFOREACH
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-xl-3 mb-3">
                                    <label for="tipo_desconto" class="form-control-label">Tipo desconto</label>
                                    <select id="tipo_desconto" name="tipo_desconto_valor" class="form-control boostrap-select-custom" onchange="onChangeTipoDesconto(event)" required value="{{ \TourFacil\Core\Enum\Descontos\TipoDescontoValor::PERCENTUAL }}">
                                        <option {{ ($desconto->tipo_desconto_valor == \TourFacil\Core\Enum\Descontos\TipoDescontoValor::PERCENTUAL) ? 'selected' : '' }} value="{{ \TourFacil\Core\Enum\Descontos\TipoDescontoValor::PERCENTUAL }}">PERCENTUAL</option>
                                        <option {{ ($desconto->tipo_desconto_valor == \TourFacil\Core\Enum\Descontos\TipoDescontoValor::FIXO) ? 'selected' : '' }} value="{{ \TourFacil\Core\Enum\Descontos\TipoDescontoValor::FIXO }}">FIXO</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-xl-3 mb-3">
                                    <div class="div-valor-percentual" id="div-valor-percentual" style="display:{{ ($desconto->tipo_desconto_valor == \TourFacil\Core\Enum\Descontos\TipoDescontoValor::PERCENTUAL) ? 'block' :  'none'}}">
                                        <label for="valor_percentual" class="form-control-label">Valor venda percentual %</label>
                                        <input id="valor_venda_percentual" type="text" class="form-control" placeholder="Valor percentual %" value="{{ $desconto->desconto }}"
                                               data-min="1" title="Valor percentual" name="valor_percentual_venda" autocomplete="off" required {{ ($desconto->tipo_desconto_valor == \TourFacil\Core\Enum\Descontos\TipoDescontoValor::PERCENTUAL) ? '' : 'disabled' }}>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="div-valor-fixo" id="div-valor-fixo" style="display:{{ ($desconto->tipo_desconto_valor == \TourFacil\Core\Enum\Descontos\TipoDescontoValor::FIXO) ? 'block' :  'none'}}">
                                        <label for="valor_fixo" class="form-control-label">Valor venda fixo R$</label>
                                        <input id="valor_venda_fixo" type="tel" class="form-control vanillaMaskMoney" value="{{ number_format($desconto->desconto, 2, ',', '.') }}" placeholder="R$ 0,00"
                                               data-required data-min="1" title="Valor fixo" name="valor_fixo_venda" autocomplete="off" {{ ($desconto->tipo_desconto_valor == \TourFacil\Core\Enum\Descontos\TipoDescontoValor::FIXO) ? '' : 'disabled' }}>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-xl-3 mb-3">
                                    <div class="div-valor-percentual" id="div-valor-percentual-net" style="display:{{ ($desconto->tipo_desconto_valor == \TourFacil\Core\Enum\Descontos\TipoDescontoValor::PERCENTUAL) ? 'block' :  'none'}}">
                                        <label for="valor_percentual" class="form-control-label">Valor net percentual %</label>
                                        <input id="valor_net_percentual" type="text" class="form-control" placeholder="Valor percentual %" value="{{ $desconto->desconto_net }}"
                                               data-required data-min="1" title="Valor percentual" name="valor_percentual_net" autocomplete="off" required {{ ($desconto->tipo_desconto_valor == \TourFacil\Core\Enum\Descontos\TipoDescontoValor::PERCENTUAL) ? '' : 'disabled' }}>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="div-valor-fixo" id="div-valor-fixo-net" style="display:{{ ($desconto->tipo_desconto_valor == \TourFacil\Core\Enum\Descontos\TipoDescontoValor::FIXO) ? 'block' :  'none'}}">
                                        <label for="valor_fixo" class="form-control-label">Valor net fixo R$</label>
                                        <input id="valor_net_fixo" type="tel" class="form-control vanillaMaskMoney" placeholder="R$ 0,00" value="{{ number_format($desconto->desconto_net, 2, ',', '.') }}" autocomplete="off" {{ ($desconto->tipo_desconto_valor == \TourFacil\Core\Enum\Descontos\TipoDescontoValor::FIXO) ? '' : 'disabled' }}
                                               data-required data-min="1" title="Valor fixo" name="valor_fixo_net">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="em-separator separator-dashed"></div>
                            <div class="ml-auto mt-4 mb-4">
                                <div class="section-title mr-auto">
                                    <h3>02. Prazos</h3>
                                    <p class="mt-1">Informações sobre as datas de venda e utilização</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xl-6 mb-6">
                                    <label for="inicio" class="form-control-label">Data inicio</label>
                                    <input id="inicio" type="datetime-local" class="form-control" required title="inicio" name="inicio" value="{{ $desconto->inicio }}">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-xl-6 mb-6">
                                    <label for="final" class="form-control-label">Data final</label>
                                    <input id="final" type="datetime-local" class="form-control" required title="final" name="final" value="{{ $desconto->final }}">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xl-6 mb-6">
                                    <label class="form-control-label">Data inicio utilização</label>
                                    <input id="inicio_utilizacao" type="datetime-local" class="form-control" required name="inicio_utilizacao" value="{{ $desconto->inicio_utilizacao }}">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-xl-6 mb-6">
                                    <label class="form-control-label">Data final utilização</label>
                                    <input id="final_utilizacao" type="datetime-local" class="form-control" required name="final_utilizacao" value="{{ $desconto->final_utilizacao }}">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>

                            <div class="em-separator separator-dashed"></div>
                            <div class="ml-auto mt-4 mb-4">
                                <div class="section-title mr-auto">
                                    <h3>03. Informações públicas</h3>
                                    <p class="mt-1">Informações que serão exibidas ao cliente</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xl-6 mb-6">
                                    <label for="nome_publico" class="form-control-label">Nome público</label>
                                    <input id="nome_publico" type="text" class="form-control" placeholder="Nome apresentado ao cliente" required
                                           data-required data-min="3" title="nome_publico" name="nome_publico" autocomplete="off" value="{{ $desconto->nome_publico }}">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-xl-3 mb-3">
                                    <label class="form-control-label">De R$</label>
                                    <input id="valor_de" type="text" class="form-control vanillaMaskMoney" placeholder="De" autocomplete="off" value="{{ number_format($desconto->valor_de, 2, ',', '.') }}"
                                           data-required data-min="1" title="De" name="valor_de" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-xl-3 mb-3">
                                    <label class="form-control-label">Por R$</label>
                                    <input id="valor_por" type="text" class="form-control vanillaMaskMoney" placeholder="Por" autocomplete="off" value="{{ number_format($desconto->valor_por, 2, ',', '.') }}"
                                           data-required data-min="1" title="Por" name="valor_por" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <input name="desconto_id" value="{{ $desconto->id }}" type="hidden">
                            <div class="text-center">
                                <button id="button-finalizar" class="btn btn-gradient-01" type="submit" onclick="onClickFinalizar(event)">Finalizar cadastro <i class="la la-angle-right right"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>

        let onChangeTipoDesconto = (event) => {

            let desconto_percentual = "{{ \TourFacil\Core\Enum\Descontos\TipoDescontoValor::PERCENTUAL }}";
            let desconto_fixo = {{ \TourFacil\Core\Enum\Descontos\TipoDescontoValor::FIXO }};

            let value = event.target.value;

            if(value == desconto_percentual) {
                $("#div-valor-percentual").css("display", "block");
                $("#div-valor-fixo").css("display", "none");

                $("#div-valor-percentual-net").css("display", "block");
                $("#div-valor-fixo-net").css("display", "none");

                $("#valor_net_fixo").prop('disabled', true);
                $("#valor_venda_fixo").prop('disabled', true);

                $("#valor_net_percentual").prop('disabled', false);
                $("#valor_venda_percentual").prop('disabled', false);

                return;
            }

            if(value == desconto_fixo) {
                $("#div-valor-percentual").css("display", "none");
                $("#div-valor-fixo").css("display", "block");

                $("#div-valor-percentual-net").css("display", "none");
                $("#div-valor-fixo-net").css("display", "block");

                $("#valor_net_fixo").prop('disabled', false);
                $("#valor_venda_fixo").prop('disabled', false);

                $("#valor_net_percentual").prop('disabled', true);
                $("#valor_venda_percentual").prop('disabled', true);

                return;
            }
        }

        let onClickFinalizar = (event) => {

            let desconto_net = $("#desconto_net").val();
            let servico = $("#select-servico").val();

            let tipo_desconto = $("#tipo_desconto").val();

            if(servico == 'UNDEFINED') {
                swal('Selecione o serviço', 'Para continuar é necessário selecionar o campo de serviço.', 'warning')
                event.preventDefault();
            }

            if(desconto_net == 'UNDEFINED') {
                swal('Selecione o desconto net', 'Para continuar é necessário selecionar o campo de desconto net.', 'warning')
                event.preventDefault();
            }

            if(tipo_desconto == '0') {

                let valor_venda_percentual = $("#valor_venda_percentual").val();
                let valor_net_percentual = $("#valor_net_percentual").val();

                console.log(valor_venda_percentual);
                console.log(valor_net_percentual);

                if(isNaN(valor_venda_percentual)) {
                    swal('Desconto venda inválido', 'O desconto venda deve ser um número', 'warning');
                    event.preventDefault();
                }

                if(isNaN(valor_net_percentual)) {
                    swal('Desconto net inválido', 'O desconto net deve ser um número', 'warning');
                    event.preventDefault();
                }
            }
        }

    </script>
@endsection
