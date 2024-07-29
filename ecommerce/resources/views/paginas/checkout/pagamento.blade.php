@extends('template.master')

@section('title', "Informações de pagamento")



@section('body')

    @php($year = date('Y'))
    @php($index_input = 1)
    @php($steps = 1)
    @php($position = 1)
    @php($json_servicos = [])

@include('template.navbar-clear')

<style>

    .desconto-pix {
        color: #28a745;
        font-weight: 600;
    }

</style>

<div data-page="pagamento" class="bg-light pb-5"
    data-cliente="{{ route('ecommerce.verificar-email') }}"
    data-geracao-pix="{{ route('ecommerce.carrinho.gerar-qrcode-pagamento-pix') }}"
     data-cancelar-pix="{{route('ecommerce.carrinho.cancelar-pix-sessao')}}"
    data-consultar-pix="{{ route('ecommerce.carrinho.consultar-pagamento-pix') }}"
    data-gerar-pedido="{{ route('ecommerce.carrinho.gerar-pedido') }}"
    data-pagamento-sucesso="{{ route('ecommerce.carrinho.pagamento.sucesso') }}"
     data-pagamento-cliente-sessao="{{ route('ecommerce.carrinho.cliente.sessao') }}"
    data-controller="PagamentoCtrl">
    <main class="container pb-3">
        <div class="row justify-content-between">
            <div class="col-12">
                {{-- Passos para checkout --}}
                @include('paginas.checkout.step', ['current_step' => 2])
            </div>
            <div class="col-12">
                {{-- Mensagens de erro --}}
                @if($errors->any())
                <div class="alert alert-danger mb-2 mt-3" role="alert">
                    @foreach($errors->all() as $error)
                    <p class="m-0">{{ $error }}</p>
                    @endforeach
                </div>
                @endif
                {{-- Mensagem de sucesso --}}
                @if(session()->has('status'))
                <div class="alert alert-success mb-2 mt-3" role="alert">
                    @foreach($errors->all() as $error)
                    {{ session()->get('status') }}
                    @endforeach
                </div>
                @endif
            </div>
            <div class="col-12 col-lg-8 pr-lg-4">
                {{-- Formulario do pagamento --}}
                <form data-validate-post class="mt-1 mt-md-2" action="{{ route('ecommerce.carrinho.efetuar-pagamento') }}" method="POST">
                    @if(is_null($cliente))
                    <h2 class="font-weight-medium h2 m-0">{{ $steps++ }}. Como quer comprar?</h2>
                    <hr class="blue mb-3">
                    <div class="alert alert-secondary border-0 bg-light d-flex w-100 align-items-center p-0 p-md-2" role="alert">
                        <i class="iconify mr-3 d-none d-md-inline" data-icon="jam:info"></i>
                        <p class="m-0 line-initial text-muted">Caso você já possua uma conta faça login, ou informe seu
                            e-mail abaixo para continuar seu cadastro e receber seus vouchers</p>
                    </div>
                    <div class="row d-flex align-items-center justify-content-around mt-4 mb-3 pb-3">
                        <div class="col-12 col-md-5 order-md-last">
                            <div class="text-center pb-1 pb-md-0">
                                <i class="iconify icon-user mt-1 mb-3 d-none d-md-inline" data-icon="la:user-check-solid"></i>
                                <strong class="d-block mb-2 font-weight-medium">Já possuo conta</strong>
                                <button type="button" tabindex="-1" class="btn btn-blue btn-rounded text-uppercase text-white border-0 px-4 pb-2" data-modal="#modal-login" title="Fazer login">
                                    Acessar conta <i class="iconify ml-1" data-icon="jam:chevron-right"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-12 col-md-5 mt-4 mt-md-0">
                            <div class="text-center">
                                <i class="iconify icon-user mt-1 mb-3" data-icon="la:user-plus-solid"></i>
                                <label for="email" class="font-weight-medium d-block">Comprando pela primeira
                                    vez</label>
                            </div>
                            <div class="position-relative">
                                <div class="input-group-prepend-absolute">
                                    <i class="iconify" data-icon="jam:envelope"></i>
                                </div>
                                <input class="form-control" id="email" name="cliente[email]" type="email" placeholder="Informe seu e-mail" required title="E-mail para confirmação" maxlength="200" tabindex="{{ $index_input++ }}" data-required data-min="3" value="{{ session()->get('cliente.email') ?? "" }}">
                                <span class="invalid-feedback"></span>
                                <div class="loader-input d-none">
                                    <span class="spinner-border spinner-border-sm align-baseline"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                        <input class="form-control" id="email" name="cliente[email]" type="hidden" placeholder="Informe seu e-mail" required title="E-mail para confirmação" maxlength="200" tabindex="{{ $index_input++ }}" data-required data-min="3" value="{{ $cliente->email }}">
                    @endif
                    {{-- Informacoes do comprador --}}
                    <h2 class="font-weight-medium h2 m-0">{{ $steps++ }}. Suas informações</h2>
                    <hr class="blue mb-4">
                    <div class="row mb-3">
                        <div class="col-12 col-md-6">
                            <label for="nome_cadastro">Nome completo</label>
                            <div class="position-relative mb-3">
                                <div class="input-group-prepend-absolute">
                                    <i class="iconify" data-icon="jam:user-circle"></i>
                                </div>
                                @if(is_null($cliente) == false)
                                    <input type="text" name="cliente[nome]" class="form-control" id="nome_cadastro" required placeholder="Nome e sobrenome" title="Nome completo" data-auto-capitalize data-nome-completo="true" data-required data-min="3" maxlength="100" tabindex="{{ $index_input++ }}" value="{{ $cliente->nome ?? "" }}">
                                @else
                                    <input type="text" name="cliente[nome]" class="form-control" id="nome_cadastro" required placeholder="Nome e sobrenome" title="Nome completo" data-auto-capitalize data-nome-completo="true" data-required data-min="3" maxlength="100" tabindex="{{ $index_input++ }}" value="{{ session()->get('cliente.nome') ?? "" }}">
                                @endif
                                <span class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="cpf_cadastro">CPF</label>
                            <div class="position-relative mb-3">
                                <div class="input-group-prepend-absolute">
                                    <i class="iconify" data-icon="jam:fingerprint"></i>
                                </div>
                                @if(is_null($cliente) == false)
                                    <input type="tel" name="cliente[cpf]" class="form-control vanillaMask" id="cpf_cadastro" required placeholder="***.***.***-**" data-required data-min="14" data-mask="cpf" data-checkout="docNumber" title="CPF" maxlength="14" tabindex="{{ $index_input++ }}" value="{{ $cliente->cpf ? $cliente->cpf : session()->get('cliente.cpf') }}" />
                                @else
                                    <input type="tel" name="cliente[cpf]" class="form-control vanillaMask" id="cpf_cadastro" required placeholder="***.***.***-**" data-required data-min="14" data-mask="cpf" data-checkout="docNumber" title="CPF" maxlength="14" tabindex="{{ $index_input++ }}" value="{{ session()->get('cliente.cpf') ?? "" }}" />
                                @endif
                                <span class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="nascimento_cadastro">Data de nascimento</label>
                            <div class="position-relative mb-3">
                                <div class="input-group-prepend-absolute">
                                    <i class="iconify" data-icon="jam:calendar"></i>
                                </div>
                                @if(is_null($cliente) == false)
                                    <input type="tel" name="cliente[nascimento]" class="form-control vanillaMask" id="nascimento_cadastro" required placeholder="DD/MM/AAAA" data-required data-min="10" title="Data de nascimento" data-mask="date" maxlength="10" tabindex="{{ $index_input++ }}" value="{{ (isset($cliente->nascimento)) ? $cliente->nascimento->format('d/m/Y') : session()->get('cliente.nascimento') }}" />
                                @else
                                    <input type="tel" name="cliente[nascimento]" class="form-control vanillaMask" id="nascimento_cadastro" required placeholder="DD/MM/AAAA" data-required data-min="10" title="Data de nascimento" data-mask="date" maxlength="10" tabindex="{{ $index_input++ }}" value="{{ session()->get('cliente.nascimento') ?? "" }}" />
                                @endif
                                <span class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="tel_cadastro">Telefone</label>
                            <div class="position-relative mb-3">
                                <div class="input-group-prepend-absolute">
                                    <i class="iconify" data-icon="jam:phone"></i>
                                </div>
                                @if(is_null($cliente) == false)
                                    <input type="tel" name="cliente[telefone]" class="form-control vanillaMask" id="tel_cadastro" required placeholder="(00) 0 0000.0000" data-required data-min="16" data-mask="phone" value="{{ $cliente->telefone ? $cliente->telefone : session()->get('cliente.telefone') }}" title="Telefone para contato" maxlength="16" tabindex="{{ $index_input++ }}" />
                                @else
                                    <input type="tel" name="cliente[telefone]" class="form-control vanillaMask" id="tel_cadastro" required placeholder="(00) 0 0000.0000" data-required data-min="16" data-mask="phone" value="{{ session()->get('cliente.telefone') ?? "" }}" title="Telefone para contato" maxlength="16" tabindex="{{ $index_input++ }}" />
                                @endif
                                    <span class="invalid-feedback"></span>
                            </div>
                        </div>
                    </div>
                    {{-- Informacoes do endereço de cobrança --}}
                    <h2 class="font-weight-medium h2 m-0">{{ $steps++ }}. Endereço de cobrança</h2>
                    <hr class="blue mb-4">
                    <div class="row mb-3">
                        <div class="col-12 col-md-6">
                            <label for="rua_cadastro">Rua</label>
                            <div class="position-relative mb-3">
                                <div class="input-group-prepend-absolute">
                                    <i class="iconify" data-icon="jam:directions"></i>
                                </div>
                                <input type="text" name="cliente[rua]" class="form-control" id="rua_cadastro" value="{{ $cliente->endereco->rua ?? session()->get('cliente.rua') ?? "" }}" required placeholder="Rua" title="Rua" data-auto-capitalize data-required data-min="3" maxlength="100" tabindex="{{ $index_input++ }}">
                                <span class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="cpf_cadastro">Número</label>
                            <div class="position-relative mb-3">
                                <div class="input-group-prepend-absolute">
                                    <i class="iconify" data-icon="jam:home"></i>
                                </div>
                                <input type="tel" name="cliente[numero]" class="form-control vanillaMask" id="numero_cadastro" value="{{ $cliente->endereco->numero ?? session()->get('cliente.numero') ?? "" }}" required placeholder="Número" data-required data-min="2" data-mask="numero" data-checkout="docNumero" title="Numero" maxlength="10" tabindex="{{ $index_input++ }}"/>
                                <span class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="nascimento_cadastro">Bairro</label>
                            <div class="position-relative mb-3">
                                <div class="input-group-prepend-absolute">
                                    <i class="iconify" data-icon="jam:map-marker"></i>
                                </div>
                                <input type="text" name="cliente[bairro]" class="form-control" id="bairro_cadastro" value="{{ $cliente->endereco->bairro ?? session()->get('cliente.bairro') ?? "" }}" required placeholder="Bairro" title="Bairro" data-auto-capitalize data-required data-min="3" maxlength="100" tabindex="{{ $index_input++ }}">
                                <span class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="tel_cadastro">Cidade</label>
                            <div class="position-relative mb-3">
                                <div class="input-group-prepend-absolute">
                                    <i class="iconify" data-icon="jam:building"></i>
                                </div>
                                    <input type="text" name="cliente[cidade]" class="form-control" id="cidade_cadastro" value="{{ $cliente->endereco->cidade ?? session()->get('cliente.cidade') ?? "" }}" required placeholder="Cidade" title="cidade" data-auto-capitalize data-required data-min="3" maxlength="100" tabindex="{{ $index_input++ }}">
                                    <span class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="tel_cadastro">Estado</label>
                            <div class="position-relative mb-3">
                                <div class="input-group-prepend-absolute">
                                    <i class="iconify" data-icon="jam:map"></i>
                                </div>
                                <select name="cliente[estado]" id="estado_cadastro" type="select" class="custom-select" style="padding-left: 30px;" title="Estado" data-auto-capitalize data-required data-min="2" maxlength="100">
                                    <option value="0" style="display: none;">Selecione o estado</option>
                                    @IF(is_null($cliente) == null)
                                        @FOREACH(TourFacil\Core\Enum\EstadosEnum::ESTADOS as $sigla => $estado)
                                            <option {{ $cliente->endereco != null && ($cliente->endereco->estado == $sigla) ? "selected" : "" }} value="{{ $sigla }}">{{$estado}}</option>
                                        @ENDFOREACH
                                    @ELSE
                                        @FOREACH(TourFacil\Core\Enum\EstadosEnum::ESTADOS as $sigla => $estado)
                                            <option {{ (session()->get('cliente.estado') == $sigla) ? "selected" : "" }} value="{{ $sigla }}">{{$estado}}</option>
                                        @ENDFOREACH
                                    @ENDIF
                                </select>
                                <span class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="tel_cadastro">CEP</label>
                            <div class="position-relative mb-3">
                                <div class="input-group-prepend-absolute">
                                    <i class="iconify" data-icon="jam:search-folder"></i>
                                </div>
                                <input type="cep" name="cliente[cep]" class="form-control vanillaMask" id="cep_cadastro" value=" {{ $cliente->endereco->cep ?? session()->get('cliente.cep') ?? "" }} " required placeholder="*****-***" data-required data-min="9" data-mask="cep" title="Telefone para contato" maxlength="16" tabindex="{{ $index_input++ }}" />
                                <span class="invalid-feedback"></span>
                            </div>
                        </div>
                    </div>
                    {{-- Formas de pagamento --}}
                    <h2 class="font-weight-medium h2 m-0">{{ $steps++ }}. Forma de Pagamento</h2>
                    <hr class="blue">
                    <div class="row mb-3">
                        @IF(isPixAtivo())
                            <div class="col-12 col-md-12 float-left">
                                <label for="forma_pagamento">Opções</label>
                                <select id="forma_pagamento"
                                    tabindex="{{ $index_input++ }}"
                                    name="forma_pagamento"
                                    class="custom-select"
                                    data-required required title="Forma de Pagamento">
                                    <option style="color: #495057;" value="" selected>Selecione</option>
                                    <option style="color: #495057;" value="cartao_credito">Cartão de Crédito</option>
                                    <option class="desconto-pix" value="pix" {{ isPixAtivo() ? '' : 'Disabled' }}>Pix {!! \TourFacil\Core\Services\Pagamento\DescontoPIXService::isDescontoPIXAplicavel() ? " <span style='color: red;'> - Desconto de " . \TourFacil\Core\Services\Pagamento\DescontoPIXService::getPixDesconto() . "%</span>" : '' !!}{{ isPixAtivo() ? '' : ' - Em breve' }}</option>
                                </select>
                            </div>
                        @ENDIF
                        <div class="float-right col-12 col-md-12 mt-4" style="display: none" id="btn_gerar_codigo_pix" >
                            <div class="text-center my-4">
                                <p class="m-0 line-initial">
                                    Ao efetuar o pagamento você aceita os
                                    <a href="{{ route('ecommerce.ajuda.termos') }}" title="Termos e Condições"
                                        target="_blank">Termos e Condições</a>
                                    de uso do site.
                                </p>
                            </div>
                            <div class="d-none">
                                @csrf
                                <input type="hidden" name="metodo_pagamento" value="{{ TourFacil\Core\Enum\MetodoPagamentoEnum::PIX }}">
                            </div>
                            <div class="text-center">
                                <button tabindex="{{ $index_input++ }}" title="Efetuar pagamento"
                                    class="btn btn-success btn-rounded text-uppercase text-white border-0 btn-pay">
                                    Pagar PIX de R$ {{ formataValor($total_carrinho_pix) }}
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Crédito --}}
                    <div id="cartao_credito" style="display: {{ isPixAtivo() ? 'none' : 'block' }}">
                        <div class="credit-card-wrapper">
                            <div class="credit-card d-flex align-items-end flex-column pt-4 p-3">
                                <div class="mt-auto pt-1 w-100">
                                    <div class="row d-flex mb-2 pb-1">
                                        <div class="col-10">
                                            <label for="numero_cartao">Número do cartão</label>
                                            <input type="tel" id="numero_cartao" name="credito[numero_cartao]"
                                                data-callback="#callback_one_card" data-logo=".callback-bandeira"
                                                tabindex="{{ $index_input++ }}" class="form-control card-number vanillaMask"
                                                autocomplete="cc-number" required data-min="17" data-required
                                                title="Número do cartão" data-mask="cartao"
                                                placeholder="**** **** **** ****" maxlength="19">
                                            <span class="invalid-feedback"></span>
                                        </div>
                                        <div class="col-2 pl-0">
                                            <div class="callback-bandeira"></div>
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="col-12 col-md-7">
                                            <label for="nome_cartao">Nome do titular do cartão</label>
                                            <input type="text" id="nome_cartao" name="credito[nome_cartao]" required
                                                data-required data-auto-capitalize data-hj-whitelist
                                                title="Nome do titular do cartão" class="form-control validate-checkout"
                                                data-min="5" autocomplete="cc-name" placeholder="Impresso no cartão"
                                                tabindex="{{ $index_input++ }}" data-checkout="cardholderName">
                                            <span class="invalid-feedback"></span>
                                        </div>
                                        <div class="col-7 col-sm-5 pl-md-0 mt-3 mt-md-0 pr-0 pr-sm-2">
                                            <label for="mes_cartao">Validade do cartão</label>
                                            <div class="d-flex flex-nowrap">
                                                <select title="Mês de validate do cartão" id="mes_cartao"
                                                    tabindex="{{ $index_input++ }}" data-required required
                                                    autocomplete="cc-exp-month" name="credito[validade_mes_cartao]"
                                                    class="custom-select">
                                                    @for($mes = 1; $mes <= 12; $mes++) <option>
                                                        {{ (strlen($mes) == 1) ? "0$mes" : $mes }}</option>
                                                    @endfor
                                                </select>
                                                <select title="Ano de validade do cartão" id="ano_cartao"
                                                    tabindex="{{ $index_input++ }}" autocomplete="cc-exp-year"
                                                    name="credito[validade_ano_cartao]" class="custom-select ml-2"
                                                    data-required required>
                                                    @for($ano = date('Y'); $ano <= ($year + 10); $ano++) <option>{{ $ano }}
                                                        </option>
                                                        @endfor
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-5 col-sm-7 mt-3 d-md-none">
                                            <label class="text-truncate w-100 line-initial" for="codigo_cartao_mobile">Cód.
                                                Segurança</label>
                                            <input type="tel" id="codigo_cartao_mobile" name="credito[codigo_cartao]"
                                                title="Código de segurança" data-min="3" maxlength="3" autocomplete="nope"
                                                class="form-control vanillaMask" data-checkout="securityCode"
                                                data-mask="cvv" data-required required placeholder="3 Dígitos"
                                                tabindex="{{ $index_input++ }}">
                                            <span class="invalid-feedback"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="credit-card card-2 d-none d-md-flex align-items-end justify-content-end flex-column pt-4 p-3">
                                <div class="row d-flex align-items-end justify-content-end w-100">
                                    <div class="col-4">
                                        <label for="codigo_cartao" class="line-initial">Cód. Segurança</label>
                                        <input type="tel" name="credito[codigo_cartao]" id="codigo_cartao"
                                            title="Código de segurança" data-min="3" maxlength="3" autocomplete="nope"
                                            class="form-control vanillaMask" data-checkout="securityCode" data-mask="cvv"
                                            data-required required placeholder="3 Dígitos" tabindex="{{ $index_input++ }}">
                                        <span class="invalid-feedback"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3 pt-1 row d-flex align-items-center justify-content-center">
                            <div class="col-12 col-md-6">
                                <label for="numero_parcelas">Número de parcelas</label>
                                <select id="numero_parcelas" tabindex="{{ $index_input++ }}" name="credito[parcelas]"
                                    class="custom-select" data-required required title="Número de parcelas" {{ ($test_ab1 == true) ? 'disabled' : '' }}>
                                    <optgroup label="Sem juros">
                                        @foreach($parcelamento['sem_juros'] as $p_sem_juros)
                                        <option data-valor="{{ $p_sem_juros['valor_parcela'] }}"
                                            value="{{ $p_sem_juros['parcela'] }}">
                                            {{ $p_sem_juros['parcela'] }}x de R$
                                            {{ formataValor($p_sem_juros['valor_parcela']) }} =
                                            {{ ($is_mobile) ? "" : "R$" }} {{ formataValor($p_sem_juros['valor_total']) }}
                                        </option>
                                        @endforeach
                                    </optgroup>
                                    @if(count($parcelamento['com_juros']))
                                    <optgroup label="Com juros">
                                        @foreach($parcelamento['com_juros'] as $p_com_juros)
                                        <option data-valor="{{ $p_com_juros['valor_parcela'] }}"
                                            value="{{ $p_com_juros['parcela'] }}">
                                            {{ $p_com_juros['parcela'] }}x de R$
                                            {{ formataValor($p_com_juros['valor_parcela']) }} =
                                            {{ ($is_mobile) ? "" : "R$" }} {{ formataValor($p_com_juros['valor_total']) }}
                                        </option>
                                        @endforeach
                                    </optgroup>
                                    @endif
                                </select>
                            </div>
                            <div class="col-12 text-center text-md-left col-md-5 mt-3 mt-md-0">
                                <span class="mb-1 mb-md-2 d-block">Bandeira aceitas</span>
                                <img src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs="
                                    class="img-fluid lazyload cartoes"
                                    data-src="{{ asset('images/bandeiras_pagamento_all.svg') }}" alt="Cartões aceitos!">
                            </div>
                        </div>
                        <div class="text-center my-4">
                            <p class="m-0 line-initial">
                                Ao efetuar o pagamento você aceita os
                                <a href="{{ route('ecommerce.ajuda.termos') }}" title="Termos e Condições"
                                    target="_blank">Termos e Condições</a>
                                de uso do site.
                            </p>
                        </div>
                        <div class="d-none">
                            @csrf
                            <input type="hidden" tabindex="-1" name="credito[bandeira_cartao]" id="callback_one_card" value>
                            <input type="hidden" name="metodo_pagamento" id="metodo_pagamento_cartao" value="{{ $e_pagamento_cartao }}">
                        </div>
                        <div class="text-center">
                            <button tabindex="{{ $index_input++ }}" title="Efetuar pagamento"
                                class="btn btn-success btn-rounded text-uppercase text-white border-0 btn-pay">
                                Pagar em 1x de R$ {{ formataValor($total_carrinho) }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-12 col-lg-4">
                <div class="card-shop-left pt-2 position-sticky">
                    <hr class="blue mb-4">
                    <div class="row d-flex justify-content-between">
                        <div class="col-auto">
                            <h2 class="font-weight-medium h5">Total ({{ count($carrinho) }} item)</h2>
                        </div>
                        <div class="col-auto text-right">
                            <input type="hidden" id="servico_valor" value="{{ formataValor($total_carrinho) }}">
                            <strong class="d-block h4 m-0 font-weight-medium text-muted">
                                @if($total_carrinho == $total_original)
                                    R$ {{ formataValor($total_carrinho) }}
                                @else
                                    <s>De R$ {{ formataValor($total_original) }}</s> <br>
                                    <span style="color: #1e7e34">Por R$ {{ formataValor($total_carrinho) }}</span>
                                @endif
                            </strong>
                            <small class="text-muted">Nenhum valor adicional</small>
                        </div>
                    </div>
                    <div class="list-cart-items mt-3">
                        @foreach($carrinho as $index => $servico_carrinho)
                        @php($json_servicos[$index] = ["id" => $servico_carrinho['uuid'], "name" =>
                        $servico_carrinho['nome_servico'], "price" => $servico_carrinho['valor_total'], "category" =>
                        $servico_carrinho['categoria'] . " em " . $servico_carrinho['cidade'], "position" =>
                        $position++, "quantity" => 1])
                        <div class="row d-flex align-items-center mb-4">
                            <div class="col-3 pr-lg-1">
                                <img src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" class="img-fluid mt-1 rounded lazyload" data-src="{{ $servico_carrinho['foto_principal'] }}" alt="{{ $servico_carrinho['nome_servico'] }}">
                            </div>
                            <div class="col-9">
                                <input type="hidden" id="servico_nome" value="{{ $servico_carrinho['nome_servico'] }}">
                                <h4 class="font-weight-normal h5 text-truncate mb-1">
                                    {{ $servico_carrinho['nome_servico'] }}
                                </h4>
                                <div class="row d-flex justify-content-between">
                                    <div class="col-auto">
                                        <p class="mb-0 text-muted line-initial">
                                            {{ dataExtenso($servico_carrinho['agenda_selecionada']['data'], true) }}
                                        </p>
                                        <p class="mb-0 text-muted line-initial">Para
                                            {{ ($servico_carrinho['com_bloqueio'] + $servico_carrinho['sem_bloqueio']) }}
                                            pessoa(s)
                                        </p>
                                    </div>
                                    <div class="col-auto align-self-end">
                                        <strong class="text-muted">
                                            @if(array_key_exists('valor_total_cupom', $servico_carrinho))
                                                De <s>R$ {{ formataValor($servico_carrinho['valor_total']) }}</s>
                                                <br>
                                                <span style="color: #1e7e34">Por R$ {{ number_format($servico_carrinho['valor_total_cupom'], 2, ',', '.') }}</span>
                                            @else
                                                R$ {{ formataValor($servico_carrinho['valor_total']) }}
                                            @endif
                                        </strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        <div id="container-cupom-link" style="margin-top: 15px;">
                            @if(session()->exists('cupom_desconto') == false)
                                <div class="text-center">
                                    <a href="#" onclick="abrirCampoCupom(event)">
                                        Tem um cupom de desconto ?
                                    </a>
                                </div>
                            @else
                                <div class="text-success text-center">
                                    <span class="iconify" data-icon="ic:baseline-check"></span>
                                    <label>Parabéns, seu cupom foi validado!</label>
                                </div>
                                <div id="cupom_desconto" style="background-color: #efefef; border-radius: 5px; padding: 10px; display: flex;">
                                    @php($cupom = session()->get('cupom_desconto'))
                                    <div style="display: flex; width: 100%;" >
                                        <div style="width: 50%;">
                                            <span class="iconify" data-icon="fa:ticket" data-width="20" data-height="15"></span>{{ $cupom->codigo }} - {{ $cupom->nome_publico }}
                                        </div>
                                        <div style="width: 50%; text-align: right;">
                                            <span style="text-align: right;">
                                                <a href="#" onclick="removerCupom(event)">Remover</a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div id="container-cupom" style="display: none;" class="text-center">
                            <input id="codigo-cupom" class="form-control" style="display: inline-block; width: 75%; text-transform: uppercase; vertical-align: middle;" placeholder="Cupom de desconto">
                            <button id="botao-cupom" class="btn btn-blue text-uppercase" style="color: white; height: calc(1.5em + 0.75rem + 2px); padding: 6px;" onclick="adicionarCupom()">
                                Aplicar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

{{-- Modal login --}}
@include('paginas.modais.modal-login')

{{-- Modal aviso de e-mail já utilizado --}}
@include('paginas.modais.aviso-login-pagamento')

{{-- footer --}}
@include('template.footer-clear')

@endsection

@section('scripts')
<script>
    window.carrinho = @json($json_servicos);

    let verifica_pix = true;

    /* Carrega o Sweet Alert assim que a página estiver carregada */
    window.onload = () => {
        Helpers.loadSweetAlert();
    };

    let adicionarCupom = () => {

        let codigo_cupom = $("#codigo-cupom").val();

        if(codigo_cupom.length < 3) {
            return swal("Atenção", 'O código do cupom deve ter pelo menos 3 caracteres.', 'warning');
        }

        let payload = {
            codigo_cupom: codigo_cupom,
        };

        axios.post("{{ Route('ecommerce.cupom.adicionar') }}", payload).then((response) => {

            let data = response.data;
            let status = data.status;
            let info = data.info;

            if(status == false || status == undefined) {
                return swal("Atenção", info, 'warning');
            }

            let modal_data = {
                title: 'Sucesso',
                text: info,
                icon: 'success',
                timer: 2000,
                showConfirmButton: false,
            };

            /*swal(modal_data).then(() => {*/
                window.location.reload();
            /*});*/

        });
    };

    let abrirCampoCupom = (event) => {
        event.preventDefault();

        /* Esconde o link de cupom */
        $("#container-cupom-link").css('display', 'none');

        /* Mostra o input para aplicar cupom */
        $("#container-cupom").css("display", "block");
    };

    let removerCupom = (event) => {
        event.preventDefault();

        let url_post = "{{ Route('ecommerce.cupom.remover') }}";

        axios.post(url_post, {}).then((response) => {
            window.location.reload();
        });
    };

</script>
@endsection
