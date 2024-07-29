@extends('template.header')

@section('title', 'Novo Desconto')

@section('content')

    <div class="row">
        <div class="page-header">
            <div class="d-flex align-items-center">
                <h2 class="page-header-title text-gradient-01">Novo Desconto</h2>
                <div>{{ Breadcrumbs::render('app.descontos.desconto.create') }}</div>
            </div>
        </div>
    </div>


    <div class="col-12">
        <div class="tab-content">
            <div class="tab-pane active" id="cadastro">
                <div class="widget has-shadow">
                    <div class="widget-body pl-lg-5 pr-lg-5">
                        <form id="form-desconto" name="ficha-cadastro" method="POST" action="{{ Route('app.descontos.desconto.store') }}" class="form-horizontal" onsubmit="submit(event)">
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
                                           data-required data-min="3" title="Nome Interno" name="nome_interno" autocomplete="off">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-xl-4 mb-3">
                                    <label for="banco" class="form-control-label">Desconto NET</label>
                                    <select id="desconto_net" name="tipo_desconto_fornecedor" class="form-control boostrap-select-custom" required value="{{ \TourFacil\Core\Enum\Descontos\TipoDesconto::NET }}">
                                        <option value="UNDEFINED">Selecione uma opção</option>
                                        <option value="{{ \TourFacil\Core\Enum\Descontos\TipoDesconto::NET }}">ATIVADO</option>
                                        <option value="{{ \TourFacil\Core\Enum\Descontos\TipoDesconto::VENDA }}">DESATIVADO</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-xl-4 mb-3">
                                    <label for="status" class="form-control-label">Status</label>
                                    <select id="status" name="status" class="form-control boostrap-select-custom" required>
                                        <option selected value="{{ \TourFacil\Core\Enum\Descontos\StatusDesconto::ATIVO }}">ATIVO</option>
                                        <option value="{{ \TourFacil\Core\Enum\Descontos\StatusDesconto::INATIVO }}">INATIVO</option>
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
                                                <option value="{{$servico->id}}">{{$servico->nome}}</option>
                                            @ENDFOREACH
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-xl-3 mb-3">
                                    <label for="tipo_desconto" class="form-control-label">Tipo desconto</label>
                                    <select id="tipo_desconto" name="tipo_desconto_valor" class="form-control boostrap-select-custom" onchange="onChangeTipoDesconto(event)" required value="{{ \TourFacil\Core\Enum\Descontos\TipoDescontoValor::PERCENTUAL }}">
                                        <option value="{{ \TourFacil\Core\Enum\Descontos\TipoDescontoValor::PERCENTUAL }}">PERCENTUAL</option>
                                        <option value="{{ \TourFacil\Core\Enum\Descontos\TipoDescontoValor::FIXO }}">FIXO</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-xl-3 mb-3">
                                    <div class="div-valor-percentual" id="div-valor-percentual">
                                        <label for="valor_percentual" class="form-control-label">Valor venda percentual %</label>
                                        <input id="valor_venda_percentual" type="number" class="form-control" placeholder="Valor percentual %"
                                               data-required data-min="1" title="Valor percentual" name="valor_percentual_venda" autocomplete="off" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="div-valor-fixo" style="display: none;" id="div-valor-fixo">
                                        <label for="valor_fixo" class="form-control-label">Valor venda fixo R$</label>
                                        <input id="valor_venda_fixo" type="tel" class="form-control vanillaMaskMoney" placeholder="R$ 0,00"
                                               data-required data-min="1" title="Valor fixo" name="valor_fixo_venda" autocomplete="off" disabled>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-xl-3 mb-3">
                                    <div class="div-valor-percentual" id="div-valor-percentual-net">
                                        <label for="valor_percentual" class="form-control-label">Valor net percentual %</label>
                                        <input id="valor_net_percentual" type="number" class="form-control" placeholder="Valor percentual %"
                                               data-required data-min="1" title="Valor percentual" name="valor_percentual_net" autocomplete="off" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="div-valor-fixo" style="display: none;" id="div-valor-fixo-net">
                                        <label for="valor_fixo" class="form-control-label">Valor net fixo R$</label>
                                        <input id="valor_net_fixo" type="tel" class="form-control vanillaMaskMoney" placeholder="R$ 0,00"  autocomplete="off" disabled
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
                                    <input id="inicio" type="datetime-local" class="form-control" required title="inicio" name="inicio">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-xl-6 mb-6">
                                    <label for="final" class="form-control-label">Data final</label>
                                    <input id="final" type="datetime-local" class="form-control" required title="final" name="final">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xl-6 mb-6">
                                    <label class="form-control-label">Data inicio utilização</label>
                                    <input id="inicio_utilizacao" type="datetime-local" class="form-control" required name="inicio_utilizacao">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-xl-6 mb-6">
                                    <label class="form-control-label">Data final utilização</label>
                                    <input id="final_utilizacao" type="datetime-local" class="form-control" required name="final_utilizacao">
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
                                           data-required data-min="3" title="nome_publico" name="nome_publico" autocomplete="off">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-xl-3 mb-3">
                                    <label class="form-control-label">De R$</label>
                                    <input id="valor_de" type="text" class="form-control vanillaMaskMoney" placeholder="De" autocomplete="off"
                                           data-required data-min="1" title="De" name="valor_de" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-xl-3 mb-3">
                                    <label class="form-control-label">Por R$</label>
                                    <input id="valor_por" type="text" class="form-control vanillaMaskMoney" placeholder="Por" autocomplete="off"
                                           data-required data-min="1" title="Por" name="valor_por" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
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

            if(servico == 'UNDEFINED') {
                swal('Selecione o serviço', 'Para continuar é necessário selecionar o campo de serviço.', 'warning')
                event.preventDefault();
            }

            if(desconto_net == 'UNDEFINED') {
                swal('Selecione o desconto net', 'Para continuar é necessário selecionar o campo de desconto net.', 'warning')
                event.preventDefault();
            }
        }

    </script>
@endsection
