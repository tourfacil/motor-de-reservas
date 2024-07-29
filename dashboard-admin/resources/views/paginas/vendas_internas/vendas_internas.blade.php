@extends('template.header')

@section('title', 'Vendas Internas')

@section('content')

@php

    use \Carbon\Carbon;

    function getValorReserva($servico) {

        if(array_key_exists('valor_total_cupom', $servico)) {
            return $servico['valor_total_cupom'];
        }

        $valor_total = 0;

        foreach($servico['agenda_selecionada']['variacoes'] as $variacao) {

            if(array_key_exists('quantidade', $variacao)) {
                $valor_total += $variacao['quantidade'] * $variacao['valor_venda'];
            }
        }

        return $valor_total;
    }

    function getValorReservaSemDesconto($servico) {

        $valor_total = 0;

        foreach($servico['agenda_selecionada']['variacoes'] as $variacao) {

            if(array_key_exists('quantidade', $variacao)) {
                $valor_total += $variacao['quantidade'] * $variacao['valor_venda'];
            }
        }

        return $valor_total;
    }

    function getValorPedido($servicos, $desconto = true) {

        $valor_total = 0;

        foreach($servicos as $servico) {
            if($desconto) {
                $valor_total += getValorReservaSemDesconto($servico);
            } else {
                $valor_total += getValorReserva($servico);
            }
        }

        return $valor_total;
    }

    function getQuantidadePessoas($variacoes) {

        $total = 0;

        foreach($variacoes as $variacao) {

            if(array_key_exists("quantidade", $variacao)) {
                $total += $variacao['quantidade'];
            }
        }

        return $total;
    }

    function getQuantidadePessoasVendaPago($servico) {

        return $servico['com_bloqueio'] + $servico['sem_bloqueio'];
    }

@endphp

<style>
    .add-remove-icon {
        font-weight: bolder;
    }

    .add-remove-icon:hover {
        color: #fd584c;
        cursor: pointer;
    }

    .espaco-span {
        margin-top: 10px;
    }

    .noselect {
        -webkit-touch-callout: none; /* iOS Safari */
        -webkit-user-select: none; /* Safari */
        -khtml-user-select: none; /* Konqueror HTML */
        -moz-user-select: none; /* Old versions of Firefox */
        -ms-user-select: none; /* Internet Explorer/Edge */
        user-select: none; /* Non-prefixed version, currently
                                        supported by Chrome, Edge, Opera and Firefox */
    }
</style>

<div class="row">
    <div class="page-header">
        <div class="d-flex align-items-center">
            <h2 class="page-header-title text-dark">Vendas internas do <span class="text-gradient-01">Tourfacil</span></h2>
            <div>{{ Breadcrumbs::render('app.vendas-internas.index') }}</div>
        </div>
    </div>
</div>

<div id="javascript-main-controller" data-controller="VendaInternaCtrl">

   {{-- VISUALIZAÇÃO DO CARRINHO --}}

    <div class="row">
        <div class="col-12">
            <div class="widget has-shadow info-pedido">
                <div class="widget-body pl-lg-5 pr-lg-5 pb-3">
                    <div class="row">
                        <div class="col-xl-6 mt-4 mb-3">
                            <div class="section-title mr-auto">
                                <i class="la la-cart-arrow-down la-3x pull-left pr-4"></i>
                                <h3>Adicionar serviço</h3>
                                <p class="mt-1">Selecione o Selecione os detalhes do serviço</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">

                        {{-- PRIMEIRA COLUNA --}}

                        <div class="col-xl-4">
                            <div class="row">
                                <label for="new_date" class="form-control-label">Selecione o serviço</label>
                                <div class="input-group">
                                    <span class="input-group-addon addon-secondary left"><i class="la la-suitcase"></i></span>

                                    @IF(session()->exists('carrinho_link') == false)
                                        <select id="select-servico" name="fornecedor" class="form-control boostrap-select-custom" required
                                                data-required title="Selecione um serviço" data-url="{{Route('app.venda-interna.obter.servico.estilo.carrinho')}}">
                                            @FOREACH($servicos as $servico)
                                                <option value="{{$servico->id}}">{{$servico->nome}}</option>
                                            @ENDFOREACH
                                        </select>
                                    @ELSE
                                        <select id="select-servico" name="fornecedor" class="form-control boostrap-select-custom" required disabled
                                            data-required title="Selecione um serviço" data-url="{{Route('app.venda-interna.obter.servico.estilo.carrinho')}}">
                                            @FOREACH($servicos as $servico)
                                                <option value="{{$servico->id}}">{{$servico->nome}}</option>
                                            @ENDFOREACH
                                    </select>
                                    @ENDIF


                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="row">
                                <label for="new_date" class="form-control-label">Selecione a data</label>
                                <div class="input-group">
                                    <span class="input-group-addon addon-secondary left"><i class="la la-calendar"></i></span>
                                    @IF(session()->exists('carrinho_link') == false)
                                        <input id="new_date" type="tel" class="form-control vanillaMask" placeholder="DD/MM/AAAA" data-url="{{Route('app.servicos.calendario')}}"
                                                title="Quantidade de pessoas" required data-required="true" data-mask="date" autocomplete="off" >
                                        <div class="invalid-feedback"></div>
                                    @ELSE
                                        <input id="new_date" type="tel" class="form-control vanillaMask" placeholder="DD/MM/AAAA" data-url="{{Route('app.servicos.calendario')}}"
                                        title="Quantidade de pessoas" required data-required="true" data-mask="date" autocomplete="off" disabled>
                                        <div class="invalid-feedback"></div>
                                    @ENDIF
                                </div>
                            </div>
                            @IF(session()->exists('carrinho_tipo_pagamento') == false)
                                <div class="row">
                            @ELSE
                                <div class="row" style="display: none;">
                            @ENDIF
                                <label for="new_date" class="form-control-label">Status de pagamento</label>
                                <div class="input-group">
                                    <span class="input-group-addon addon-secondary left"><i class="la la-suitcase"></i></span>
                                    <select id="select-pagamento" name="fornecedor" class="form-control boostrap-select-custom" required
                                            data-required title="Selecione um status de pagamento">
                                            <option value="PENDENTE">Pendente</option>
                                            <option value="PAGO" {{ userIsAdmin() ? "" : "disabled" }}>Pago</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        {{-- SEGUNDA COLUNA --}}

                        <div class="col-xl-5">
                            <div id="variacoes-reserva-titulo" style="display: none;">
                                <div class="row">
                                    <div class="col-xl-8">
                                        <label for="new_date" class="form-control-label">Classificação de preço</label>
                                    </div>
                                    <div class="col" style="text-align: center;">
                                        <label for="new_date" class="form-control-label">Quantidade</label>
                                    </div>
                                </div>
                            </div>
                            <div id="variacoes-reserva">

                            </div>
                        </div>

                         {{-- TERCEIRA COLUNA --}}

                        <div class="col">
                            <div id="inside-column-valor-total" style="display: none;">
                                <div class="row">
                                    <label for="new_date" class="form-control-label">Valor Total Reserva</label>
                                </div>
                                <div class="row">
                                    <label style="color: #097a27; font-weight: bold; font-size: 30px;"  id="valor_total">R$ 0,00</label>
                                </div>
                                <div class="row">
                                    @IF(session()->exists('carrinho_tipo_pagamento') == false)
                                        <button class="btn btn-success pull-right" id="botao-adicionar-servico" style="display: none;">
                                            Adicionar serviço
                                        </button>
                                        <button class="btn btn-success pull-right" id="botao-cadastrar-cliente" style="display: none;">Cadastrar clientes</button>
                                    @ELSEIF(session()->get('carrinho_tipo_pagamento') == "PENDENTE")
                                        <button class="btn btn-success pull-right" id="botao-adicionar-servico">
                                            Adicionar serviço
                                        </button>
                                    @ELSE
                                        <button class="btn btn-success pull-right" id="botao-cadastrar-cliente">Cadastrar clientes</button>
                                    @ENDIF
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- INFORMAÇÕES REFERENTES AO PAGAMENTO --}}

    <div class="row" id="cad-pagamento" style="display: none;">
        <div class="col-12">
            <div class="widget has-shadow">
                <div class="widget-body pl-lg-5 pr-lg-5">
                    <div class="row">
                        <div class="col-xl-8 mt-4 mb-3">
                            <div class="section-title mr-auto">
                                <i class="la la la-money la-3x pull-left pr-4"></i>
                                <h3>Informações de pagamento</h3>
                                <p class="mt-1">Informações sobre o pagamento do pedido</p>
                            </div>
                        </div>
                        <div class="col-xl-4 mt-4 mb-5 d-flex align-items-center">
                        </div>
                        <div class="col-12 mb-0 mt-2" id="container-pagamento">
                            <div class="row">
                                <div class="col">
                                    <label for="new_date" class="form-control-label">Meio de pagamento</label>
                                    <div class="input-group">
                                        <span class="input-group-addon addon-secondary left"><i class="la la-bank"></i></span>
                                        <select id="select-meio-pagamento" name="meio_pagamento_interno" class="form-control boostrap-select-custom" required data-required title="Selecione um meio de pagamento">
                                            @foreach($meios_pagamento as $key => $meio_pagamento)
                                                <option {{ (session()->get('carrinho_pagamento.meio_pagamento') == $key) ? 'selected' : '' }} value="{{ $key }}">{{ $meio_pagamento }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col">
                                    <label for="new_date" class="form-control-label">Método de pagamento</label>
                                    <div class="input-group">
                                        <span class="input-group-addon addon-secondary left"><i class="fa fa-solid fa-money-bill-transfer"></i></i></span>
                                        <select id="select-metodo-pagamento" name="metodo_pagamento_interno" class="form-control boostrap-select-custom" required data-required title="Selecione um método de pagamento">
                                            @foreach($metodos_pagamento as $key_2 => $metodo_pagamento)
                                                <option {{ (session()->get('carrinho_pagamento.metodo_pagamento') == $key_2) ? 'selected' : '' }} value="{{ $key_2 }}">{{ $metodo_pagamento }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>






    {{-- TITULAR DO PEDIDO --}}

    <div class="row" id="cad-titular" style="display: none;">
        <div class="col-12">
            <div class="widget has-shadow">
                <div class="widget-body pl-lg-5 pr-lg-5">
                    <div class="row">
                        <div class="col-xl-8 mt-4 mb-3">
                            <div class="section-title mr-auto">
                                <i class="la la la-user la-3x pull-left pr-4"></i>
                                <h3>Títular do pedido</h3>
                                <p class="mt-1">Informações sobre o títular do pedido</p>
                            </div>
                        </div>
                        <div class="col-xl-4 mt-4 mb-5 d-flex align-items-center">
                        </div>
                        <div class="col-12 mb-0 mt-2" id="container-titular">
                            <div class="row">
                                <div class="col">
                                    <label class="form-control-label">Nome completo</label>
                                    <div class="input-group">
                                        <span class="input-group-addon addon-secondary left"><i class="la la-calendar"></i></span>
                                        <input type="text" class="form-control" placeholder="Nome e sobrenome"  autocomplete="off" id="titular-nome" value="{{session()->get('carrinho_titular.nome') ?? ""}}" disabled>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col">
                                    <label class="form-control-label">E-mail</label>
                                    <div class="input-group">
                                        <span class="input-group-addon addon-secondary left"><i class="la la-calendar"></i></span>
                                        <input type="text" class="form-control" placeholder="cliente@email.com.br" autocomplete="off" id="titular-email" value="{{session()->get('carrinho_titular.email') ?? ""}}">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col">
                                    <label class="form-control-label">Telefone</label>
                                    <div class="input-group">
                                        <span class="input-group-addon addon-secondary left"><i class="la la-calendar"></i></span>
                                        <input type="text" class="form-control vanillaMask" data-mask="phone" data-required data-min="16" placeholder="(00) 0 0000-0000"  autocomplete="off"  id="titular-telefone" value="{{session()->get('carrinho_titular.telefone') ?? ""}}" disabled>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label class="form-control-label espaco-span">Documento</label>
                                    <div class="input-group">
                                        <span class="input-group-addon addon-secondary left"><i class="la la-calendar"></i></span>
                                        <input type="text" class="form-control vanillaMask" data-mask="cpf" placeholder="000.000.000-00"  autocomplete="off"  id="titular-documento" value="{{session()->get('carrinho_titular.documento') ?? ""}}" disabled>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col">
                                    <label class="form-control-label espaco-span">Nascimento</label>
                                    <div class="input-group">
                                        <span class="input-group-addon addon-secondary left"><i class="la la-calendar"></i></span>
                                        <input type="text" class="form-control vanillaMask" data-mask="date" placeholder="00/00/0000"  autocomplete="off"  id="titular-nascimento"  value="{{session()->get('carrinho_titular.nascimento') ?? ""}}" disabled>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col">
                                    <label class="form-control-label espaco-span">Endereço</label>
                                    <div class="input-group">
                                        <span class="input-group-addon addon-secondary left"><i class="la la-calendar"></i></span>
                                        <input type="text" class="form-control" placeholder="Rua X, 000 "  autocomplete="off"  id="titular-endereco" value="{{session()->get('carrinho_titular.endereco') ?? ""}}" disabled>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 10px; float: right; display: none;" id="container-botao-cadastrar-titular-post">
                                <button class="btn btn-success pull-right" id="botao-cadastrar-titular-post">Adicionar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ACOMPANHANTES --}}


    <div class="row" id="cad-cli" style="display: none;">
        <div class="col-12">
            <div class="widget has-shadow">
                <div class="widget-body pl-lg-5 pr-lg-5">
                    <div class="row">
                        <div class="col-xl-8 mt-4 mb-3">
                            <div class="section-title mr-auto">
                                <i class="la la la-users la-3x pull-left pr-4"></i>
                                <h3>Acompanhantes da reserva</h3>
                                <p class="mt-1">Informações dos acompanhantes da reserva.</p>
                            </div>
                        </div>
                        <div class="col-xl-4 mt-4 mb-5 d-flex align-items-center">
                        </div>
                        <div class="col-12 mb-0 mt-2" id="container-clientes">

                        </div>
                    </div>
                    <div class="row">
                        <div class="col"></div>
                        <div class="col"></div>
                        <div class="col"  style="text-align: right;">
                            @IF(session()->exists('carrinho_link') == false)
                                <button class="btn btn-success pull-right" id="botao-cadastrar-cliente-post" style="display: none;">Adicionar</button>
                            @ENDIF
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

{{--        Campos adicionais    --}}

    <div class="row" style="display:none;" id="container-row-campos-adicionais">
        <div class="col-12">
            <div class="widget has-shadow">
                <div class="widget-body pl-lg-5 pr-lg-5">
                    <div class="row">
                        <div class="col-xl-8 mt-4 mb-3">
                            <div class="section-title mr-auto">
                                <i class="la la la-file-text la-3x pull-left pr-4"></i>
                                <h3>Dados adicionais da reserva</h3>
                                <p class="mt-1">Informações adicionais sobre a reserva.</p>
                            </div>
                        </div>
                        <div class="col-xl-4 mt-4 mb-5 d-flex align-items-center">
{{--                            <h2 class="ml-auto mb-0">{{ $qtd_campos_adicionais }} campos</h2>--}}
                        </div>
                        <div class="col-12 mb-2 mt-1">
                            <div class="form-group row mb-3" id="container-campos-adicionais">

                            </div>
                            <div class="row">
                                <div class="col"></div>
                                <div class="col"></div>
                                <div class="col"  style="text-align: right;">
                                    @IF(session()->exists('carrinho_link') == false)
                                        <button class="btn btn-success pull-right" id="botao-cadastrar-campo-adicional-post">Adicionar</button>
                                    @ENDIF
                                </div>
                            </div>
                            <div class="hide">{{ method_field("PUT") }}</div>
                            <div class="row">
                                <div class="col-6">
{{--                                        <span class="mt-2">Última alteração: {{ $reserva->campoAdicionalReservaPedido->first()->updated_at->format('d/m/Y H:i') }}</span>--}}
                                </div>
                                <div class="col-6 text-right">
{{--                                        <button  class="btn btn-success mr-2 disable-action">Atualizar dados adicionais</button>--}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- VISUALIZAÇÃO DE ITENS --}}

    <div class="row">
        <div class="col-12">
            <div class="widget has-shadow info-pedido">
                <div class="widget-body pl-lg-5 pr-lg-5 pb-3">
                    <div class="row">
                        <div class="col-xl-7 mt-3 mb-2">
                            <div class="section-title mr-auto">
                                <i class="la la-shopping-cart la-3x pull-left pr-4"></i>
                                <h3>Resumo do pedido</h3>
                                <p class="mt-1">Informações referente ao pedido</p>
                            </div>
                        </div>
                        <div class="col-xl-5 mt-3 mb-4 d-flex align-items-center">
                            @IF(session()->exists('carrinho') && session()->get('carrinho') != [])
                                @if(\TourFacil\Core\Services\CupomDescontoService::isCupomNaSessao() == false)
                                    <h2 class="ml-auto mb-0">Valor total: R$ {{number_format(getValorPedido(session()->get('carrinho')), 2, ',', '.')}}</h2>
                                @else
                                    @if(session()->get('cupom_desconto')->servico_id == null)
                                        <h2 class="ml-auto mb-0">Valor total: <span style="">De <span style="text-decoration: line-through;"> R$ {{ number_format(getValorPedido(session()->get('carrinho')), 2, ',', '.') }}</span> por R$ {{  number_format(\TourFacil\Core\Services\CupomDescontoService::aplicarDescontoValor(session()->get('cupom_desconto'), getValorPedido(session()->get('carrinho'))), 2, ',', '.') }}</span></h2>
                                    @else
                                        <h2 class="ml-auto mb-0">Valor total: <span style="">De <span style="text-decoration: line-through;"> R$ {{ number_format(getValorPedido(session()->get('carrinho')), 2, ',', '.') }}</span> por R$ {{  number_format( getValorPedido(session()->get('carrinho'), false), 2, ',', '.') }}</span></h2>
                                    @endif
                                @endif
                            @ENDIF
                        </div>
                    </div>
                    <hr>
                    @IF(session()->exists('carrinho') && session()->get('carrinho') != [])
                        @FOREACH(session()->get('carrinho') as $key => $servico_carrinho)
                            <div class="row">
                                <div class="col-xl-1" style="padding-right: 0px;">
                                    <img style="width:100%; height:100%;border-radius:5px;" src="{{ $servico_carrinho['foto_principal'] }}">
                                </div>
                                <div class="col">
                                    <label for="new_date" class="form-control-label">{{$servico_carrinho['nome_servico']}} - {{Carbon::parse($servico_carrinho['agenda_selecionada']['data'])->format('d/m/Y')}}</label>
                                    <br>
                                    @IF(array_key_exists('acompanhantes', $servico_carrinho))
                                        <label for="new_date" class="form-control-label">{{getQuantidadePessoasVendaPago($servico_carrinho) ?? ""}} Pessoa(s) - Valor: R$ {{number_format(getValorReserva($servico_carrinho), 2, ',', '.')}} @IF(session()->exists('carrinho_link') == false) - </label> {{--<a style="font-size:12px;" href="">Editar</a>--}} <a style="font-size:12px;" href="{{Route('app.venda-interna.carrinho.remover', ['index' => $key])}}">Remover</a> @ENDIF
                                    @ELSE
                                        <label for="new_date" class="form-control-label">{{getQuantidadePessoas($servico_carrinho['variacoes'])}} Pessoa(s) - Valor: R$ {{number_format(getValorReserva($servico_carrinho), 2, ',', '.')}} @IF(session()->exists('carrinho_link') == false)- </label> {{--<a style="font-size:12px;" href="">Editar</a>--}} <a style="font-size:12px;" href="{{Route('app.venda-interna.carrinho.remover', ['index' => $key])}}">Remover</a> @ENDIF
                                    @ENDIF
                                </div>
                            </div>
                            <hr>
                        @ENDFOREACH
                    @ELSE
                        O pedido não possui reservas
                    @ENDIF
                    @IF(session()->exists('carrinho') && session()->get('carrinho') != [])
                        <div class="row" style="text-align: left;">
                            <div class="col-xl-10">
                                @IF(session()->exists('carrinho_link'))
                                    <label class="form-control-label" for="link">Clique para copiar o link</label>
                                    <div class="input-group"  style="cursor:pointer;" id="link-container">
                                        <span class="input-group-addon addon-secondary">
                                            <i class="la la-link"></i>
                                        </span>
                                        <input id="link" type="text" class="form-control disable-action noselect" style="cursor:pointer;"
                                                data-required data-min="1" title="Documento do acompanhante" name="link" disabled value="{{session()->get('carrinho_link')}}">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                @ELSE
                                    @if(session()->get('carrinho_tipo_pagamento') != 'PENDENTE')
                                        <div class="input-group" style="width: 50%;">
                                                <span class="input-group-addon addon-secondary">
                                                    <i class="la la-ticket"></i>
                                                </span>
                                            @if(\TourFacil\Core\Services\CupomDescontoService::isCupomNaSessao())
                                                <input id="cupom" type="text" class="form-control disable-action" placeholder="Cupom" title="Cupom"
                                                    name="cupom" autocomplete="off" style="margin-right: 15px; text-transform: uppercase;" value="{{ session()->get('cupom_desconto')->codigo }}" disabled>
                                            @else
                                                <input id="cupom" type="text" class="form-control disable-action" placeholder="Cupom" title="Cupom"
                                                       name="cupom" autocomplete="off" style="margin-right: 15px; text-transform: uppercase;">
                                            @endif
                                            <div class="invalid-feedback"></div>
                                            <input style="display:{{ \TourFacil\Core\Services\CupomDescontoService::isCupomNaSessao() ? 'none' : 'block' }}" type="submit" value="Adicionar" class="btn btn-info pull-right" id="cupom-add">
                                            <input style="display:{{ \TourFacil\Core\Services\CupomDescontoService::isCupomNaSessao() ? 'block' : 'none' }}" type="submit" value="Remover" class="btn btn-danger pull-right" id="cupom-remove">
                                    </div>
                                    @endif
                                @ENDIF
                            </div>
                            @IF(session()->exists('carrinho_link') == false)
                                <div class="col" style="text-align: right;">
                                    @IF(session()->get('carrinho_tipo_pagamento') == "PAGO")
                                        <form action="{{Route('app.venda-interna.carrinho.finalizar')}}">
                                            <input type="submit" value="Finalizar" class="btn btn-success pull-right" id="botao-finalizar">
                                        </form>
                                    @ELSE
                                        <form action="{{Route('app.venda-interna.carrinho.gerar-link')}}">
                                            <input type="submit" value="Finalizar" class="btn btn-success pull-right" id="botao-finalizar">
                                        </form>
                                    @ENDIF
                                </div>
                            @ELSE
                                <div class="col" style="text-align: right; padding-top: 2.5%;">
                                    <form action="{{Route('app.venda-interna.carrinho.novo-link')}}">
                                        <input type="submit" value="Novo link" class="btn btn-success pull-right" id="botao-finalizar">
                                    </form>
                                </div>
                            @ENDIF
                        </div>
                    @ENDIF
                </div>
            </div>
        </div>
    </div>

    <span id="link-carrinho-adicionar" link="{{Route('app.venda-interna.carrinho.adicionar')}}"></span>
    <span id="link-consultar-email" link="{{Route('app.venda-interna.consultar-email')}}"></span>
    <span id="link-set-cupom-desconto" link="{{ Route('app.venda-interna.set-cupom-desconto') }}"></span>
    <span id="link-unset-cupom-desconto" link="{{ Route('app.venda-interna.unset-cupom-desconto') }}"></span>

    @IF(session()->exists('carrinho_tipo_pagamento') == true)
        <span id="tipo_pagamento" data="{{session()->get('carrinho_tipo_pagamento')}}"></span>
    @ENDIF

</div>

@endsection


