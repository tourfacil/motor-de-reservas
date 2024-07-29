@extends('template.header')

@section('title', 'Novo Cupom')

@section('content')

    <div class="row">
        <div class="page-header">
            <div class="d-flex align-items-center">
                <h2 class="page-header-title text-gradient-01">Novo cupom</h2>
                <div>{{ Breadcrumbs::render('app.descontos.cupom.create') }}</div>
            </div>
        </div>
    </div>


    <div class="col-12">
        <div class="tab-content">
            <div class="tab-pane active" id="cadastro">
                <div class="widget has-shadow">
                    <div class="widget-body pl-lg-5 pr-lg-5">
                        <form name="ficha-cadastro" method="POST" action="{{ route('app.descontos.cupom.store') }}" class="form-horizontal">
                            @CSRF
                            <div class="ml-auto mt-4 mb-4">
                                <div class="section-title mr-auto">
                                    <h3>01. Dados do cupom</h3>
                                    <p class="mt-1">Informações para a aplicação do cupom de desconto</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xl-3 mb-3">
                                    <label for="nome_publico" class="form-control-label">Nome público</label>
                                    <input id="nome_publico" type="text" class="form-control" placeholder="Nome apresentado ao cliente" required
                                           data-required data-min="3" title="nome_publico" name="nome_publico">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-xl-4 mb-3">
                                    <label for="nome_interno" class="form-control-label">Nome interno</label>
                                    <input id="nome_interno" type="text" class="form-control" placeholder="Nome não apresentado ao cliente" required
                                           data-required data-min="3" title="Nome Interno" data-auto-capitalize name="nome_interno">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-xl-5 mb-3">
                                    <label for="codigo" class="form-control-label">Código</label>
                                    <input id="codigo" type="text" class="form-control" placeholder="Código para ativar o cupom" required
                                           data-required data-min="3" title="Codigo" data-auto-capitalize name="codigo" style="text-transform:uppercase" >
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xl-3 mb-3">
                                    <label for="banco" class="form-control-label">Desconto NET</label>
                                    <select id="desconto_net" name="desconto_net" class="form-control boostrap-select-custom" required value="{{ \TourFacil\Core\Enum\Descontos\TipoDesconto::NET }}">
                                        <option value="{{ \TourFacil\Core\Enum\Descontos\TipoDesconto::NET }}">ATIVADO</option>
                                        <option value="{{ \TourFacil\Core\Enum\Descontos\TipoDesconto::VENDA }}">DESATIVADO</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-xl-4 mb-3">
                                    <label for="tipo_desconto" class="form-control-label">Tipo desconto</label>
                                    <select id="tipo_desconto" name="tipo_desconto" class="form-control boostrap-select-custom" onchange="onChangeTipoDesconto(event)" required value="{{ \TourFacil\Core\Enum\Descontos\TipoDescontoValor::PERCENTUAL }}">
                                        <option value="{{ \TourFacil\Core\Enum\Descontos\TipoDescontoValor::PERCENTUAL }}">PERCENTUAL</option>
                                        <option value="{{ \TourFacil\Core\Enum\Descontos\TipoDescontoValor::FIXO }}">FIXO</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-xl-5 mb-3">
                                    <div class="div-valor-percentual" id="div-valor-percentual">
                                        <label for="valor_percentual" class="form-control-label">Valor percentual %</label>
                                        <input id="telefone_responsavel" type="number" class="form-control" placeholder="Valor percentual %"
                                               data-required data-min="1" title="Valor percentual" name="valor_percentual">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="div-valor-fixo" style="display: none;" id="div-valor-fixo">
                                        <label for="valor_fixo" class="form-control-label">Valor fixo R$</label>
                                        <input id="telefone_responsavel" type="tel" class="form-control vanillaMaskMoney" placeholder="R$ 0,00"
                                               data-required data-min="1" title="Valor fixo" name="valor_fixo">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xl-3 mb-3">
                                    <label for="banco" class="form-control-label">Serviço</label>
                                    <div class="input-group">
{{--                                            <span class="input-group-addon addon-secondary left"><i class="la la-suitcase"></i></span>--}}
                                        <select id="select-servico" name="servico" class="form-control boostrap-select-custom" required>
                                            <option value="0" selected>Todos os serviços</option>
                                            @FOREACH($servicos as $servico)
                                                <option value="{{$servico->id}}">{{$servico->nome}}</option>
                                            @ENDFOREACH
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
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
                                <div class="col-xl-5 mb-3">
                                    <label for="maximo_utilizacao" class="form-control-label">Máximo de utilizações</label>
                                    <input id="maximo_utilizacao" type="number" class="form-control" placeholder="Máximo de utilizações" required
                                           data-required data-min="1" title="Maximo Utilização" name="maximo_utilizacao">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="em-separator separator-dashed"></div>
                            <div class="text-center">
                                <button class="btn btn-gradient-01" type="submit">Finalizar cadastro <i class="la la-angle-right right"></i></button>
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
                return;
            }

            if(value == desconto_fixo) {
                $("#div-valor-percentual").css("display", "none");
                $("#div-valor-fixo").css("display", "block");
                return;
            }
        }

    </script>

@endsection
