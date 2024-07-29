@extends('template.header')

@section('title', 'Detalhes reserva #' . $reserva->voucher)

@section('content')

    @php($count_quantidade = 1)

    <div class="row">
        <div class="page-header pb-4">
            <div class="d-flex align-items-center">
                <h2 class="page-header-title text-dark">Detalhes reserva <span class="text-gradient-01">#{{ $reserva->voucher }}</span></h2>
                <div>{{ Breadcrumbs::render('app.reservas.view', $reserva->pedido) }}</div>
            </div>
        </div>
    </div>

    {{-- Informativo sobre o status da reserva --}}
    <div class="row">
        <div class="col-12">
            @if(is_object($reserva->validacao))
                <div class="alert alert-primary mb-4" role="alert">
                    <i class="la la-check-circle mr-2"></i>
                    Essa reserva foi <strong>autenticada</strong> no dia {{ $reserva->validacao->created_at->format('d/m/Y H:i') }}
                </div>
            @elseif($reserva->status === $enum_cancelada)
                <div class="alert alert-danger mb-4" role="alert">
                    <i class="la la-ban mr-2"></i>
                    Essa reserva foi <strong>cancelada</strong> no dia {{ $reserva->updated_at->format('d/m/Y H:i') }}
                </div>
            @elseif($reserva->status === $enum_aguardando)
                <div class="alert alert-warning mb-4" role="alert">
                    <i class="la la-clock-o mr-2"></i>
                    Essa reserva está aguardando o <strong>pagamento</strong>.
                </div>
            @elseif($reserva->status === $enum_finalizar)
                <div class="alert alert-primary mb-4" role="alert">
                    <i class="la la-clock-o mr-2"></i>
                    Essa reserva está aguardando o lançamento das <strong>informações</strong>.
                </div>
            @endif
        </div>
    </div>

    <div class="row" data-controller="ReservaCtrl" data-disabled="{{ $disable_fields }}">
        <div class="col-12">
            <div class="widget has-shadow info-pedido">
                <div class="widget-body pl-lg-5 pr-lg-5 pb-2">
                    <div class="row">
                        <div class="col-xl-8 mt-3 mb-1">
                            <div class="section-title mr-auto">
                                <i class="la la-cart-arrow-down la-3x pull-left pr-4"></i>
                                <h3>Informações da reserva</h3>
                                <p class="mt-1">Reserva realizada no dia {{ $reserva->pedido->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        <div class="col-xl-4 mt-2 mb-4 d-flex align-items-center">
                            <h2 class="ml-auto mb-0">#{{ $reserva->voucher }}</h2>
                        </div>
                        <div class="col-xl-12 mb-4">
                            <div class="em-separator separator-dashed mt-0 mb-2"></div>
                        </div>
                        <div class="col-xl-9">
                            <div class="row">
                                <div class="col-xl-8 mb-1 info-terminal">
                                    <p>
                                        Serviço adquirido: <br>
                                        <strong>
                                            ({{ $reserva->quantidade }}x) {{ $reserva->servico->nome }}
                                        </strong>
                                    </p>
                                </div>
                                    <div class="col-xl-2 mb-1 info-terminal">
                                        <p>
                                            Vendedor
                                            <br>
                                            <select id="select-vendedor" class="form-control" onchange="atribuirVendedorReserva()" {{ !userIsAdmin() ? "disabled" : "" }}>
                                                <option value="0">Nenhum</option>
                                                @FOREACH($vendedores as $vendedor)
                                                    <option {{ ($vendedor->id == $reserva->vendedor_id) ? "selected" : "" }} value="{{ $vendedor->id }}">{{ $vendedor->nome_fantasia }}</option>
                                                @ENDFOREACH
                                            </select>
                                        </p>
                                    </div>
                                <div class="col-xl-2 mb-1 info-terminal">
                                    <p>
                                        Afiliado
                                        <br>
                                        <select id="select-afiliado" class="form-control" onchange="atribuirAfiliadoReserva()" {{ !userIsAdmin() ? "disabled" : "" }}>
                                            <option value="0">Nenhum</option>
                                            @FOREACH($afiliados as $afiliado)
                                                <option {{ ($afiliado->id == $reserva->afiliado_id) ? "selected" : "" }} value="{{ $afiliado->id }}">{{ $afiliado->nome_fantasia }}</option>
                                            @ENDFOREACH
                                        </select>
                                    </p>
                                </div>
                                <div class="col-xl-4 mb-1 info-terminal">
                                    <p>
                                        Agendado para <br>
                                        <strong>{{ dataExtenso($reserva->agendaDataServico->data) }}</strong>
                                    </p>
                                    <div style="display: flex; justify-content: space-between;">
                                        <p>
                                            Código pedido <br>
                                            <strong>
                                                <a href="{{ route('app.pedidos.view', $reserva->pedido->codigo) }}" class="text-secondary">#{{ $reserva->pedido->codigo }}</a>
                                            </strong>
                                        </p>
                                        <p>
                                            @if($reserva->integracaoPWI)
                                                Código Skyglass <br>
                                                <strong>
                                                    <a href="#" class="text-secondary">#{{ $reserva->idPWI }}</a>
                                                </strong>
                                            @endif
                                        </p>
                                    </div>

                                </div>
                                <div class="col-xl-4 mb-1 info-terminal">
                                    <p>
                                        Valor da reserva <br>
                                        <strong>R$ {{ formataValor($reserva->valor_total) }}</strong> - <strong>R$ {{ formataValor($reserva->valor_net) }}</strong>
                                    </p>
                                    <p>
                                        Operado por <br>
                                        <strong class="text-truncate">{{ $reserva->fornecedor->nome_fantasia }}</strong>
                                    </p>
                                </div>
                                <div class="col-xl-4 mb-1 info-terminal">
                                    <p>
                                        Categoria do serviço <br>
                                        <strong>{{ $reserva->servico->categoria->nome }}</strong>
                                    </p>
                                    <p>
                                        Status da reserva <br>
                                        @if($reserva->status == 'ATIVA' && $reserva->finalizada == \TourFacil\Core\Enum\StatusFinalizacaoReservaEnum::NAO_FINALIZADA)
                                            <strong class="text-warning text-uppercase">Finalizar</strong>
                                        @else
                                            <strong class="text-{{ $reserva->cor_status }} text-uppercase">{{ $reserva->status_reserva }}</strong>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 d-flex flex-column align-self-center pl-xl-5">
                            <button type="button" data-action="alterar_agenda" class="btn btn-gradient-02 btn-block mb-3 disable-action"
                                    data-url="{{ route('app.reservas.calendario.view', $reserva->id) }}">Alterar agenda</button>
                            @if($reserva->finalizada == \TourFacil\Core\Enum\StatusFinalizacaoReservaEnum::FINALIZADA)
                                @if(isReservaIntegrada($reserva) || $reserva->servico->integracao == 'NAO')
                                    @if($reserva->servico->integracao == \TourFacil\Core\Enum\IntegracaoEnum::SNOWLAND)
                                        <button type="button" data-action="print_voucher" class="btn btn-gradient-03 btn-block mb-3 disable-action"
                                                data-url="{{ $reserva->snowlandVoucher->url_voucher ?? "" }}">Imprimir voucher</button>
                                    @elseif($reserva->servico->integracao == \TourFacil\Core\Enum\IntegracaoEnum::EXCEED_PARK)
                                        <button type="button" data-action="print_voucher" class="btn btn-gradient-03 btn-block mb-3 disable-action"
                                                data-url="{{ $reserva->exceedVoucher->url_voucher ?? "" }}">Imprimir voucher</button>
                                    @elseif($reserva->servico->integracao == \TourFacil\Core\Enum\IntegracaoEnum::OLIVAS)
                                        <button type="button" data-action="print_voucher" class="btn btn-gradient-03 btn-block mb-3 disable-action"
                                            data-url="{{ $reserva->olivasVoucher->url_voucher ?? "" }}">Imprimir voucher</button>
                                    @elseif($reserva->servico->integracao == \TourFacil\Core\Enum\IntegracaoEnum::MINI_MUNDO)
                                        <button type="button" data-action="print_voucher" class="btn btn-gradient-03 btn-block mb-3 disable-action"
                                            data-url="{{ $reserva->miniMundoVoucher->url_voucher ?? "" }}">Imprimir voucher</button>
                                    @elseif($reserva->servico->integracao == \TourFacil\Core\Enum\IntegracaoEnum::DREAMS)
                                        <button type="button" data-action="print_voucher" class="btn btn-gradient-03 btn-block mb-3 disable-action"
                                            data-url="{{ $reserva->dreamsVoucher->url_voucher ?? "" }}">Imprimir voucher</button>
                                    @elseif($reserva->servico->integracao == \TourFacil\Core\Enum\IntegracaoEnum::ALPEN)
                                        <button type="button" data-action="print_voucher" class="btn btn-gradient-03 btn-block mb-3 disable-action"
                                                data-url="{{ $reserva->alpenVoucher->url_voucher ?? "" }}">Imprimir voucher</button>
                                    @elseif($reserva->servico->integracao == \TourFacil\Core\Enum\IntegracaoEnum::FANTASTIC_HOUSE)
                                        <button type="button" data-action="print_voucher" class="btn btn-gradient-03 btn-block mb-3 disable-action"
                                                data-url="{{ $reserva->fantasticHouseVoucher->url_voucher ?? "" }}">Imprimir voucher</button>
                                    @elseif($reserva->servico->integracao == \TourFacil\Core\Enum\IntegracaoEnum::MATRIA)
                                        <button type="button" data-action="print_voucher" class="btn btn-gradient-03 btn-block mb-3 disable-action"
                                                data-url="{{ $reserva->matriaVoucher->url_voucher ?? "" }}">Imprimir voucher</button
                                    @elseif($reserva->servico->integracao == \TourFacil\Core\Enum\IntegracaoEnum::VILA_DA_MONICA)
                                        <button type="button" data-action="print_voucher" class="btn btn-gradient-03 btn-block mb-3 disable-action"
                                                data-url="{{ $reserva->vilaDaMonicaVoucher->url_voucher ?? "" }}">Imprimir voucher</button>
                                    @elseif($reserva->servico->integracao == \TourFacil\Core\Enum\IntegracaoEnum::ACQUA_MOTION)
                                        <button type="button" data-action="print_voucher" class="btn btn-gradient-03 btn-block mb-3 disable-action"
                                                data-url="{{ $reserva->acquaMotionVoucher->url_voucher ?? "" }}">Imprimir voucher</button>

                                    @elseif($reserva->servico->integracao == \TourFacil\Core\Enum\IntegracaoEnum::NBA_PARK)
                                        <button type="button" data-action="print_voucher" class="btn btn-gradient-03 btn-block mb-3 disable-action"
                                                data-url="{{ $reserva->nbaParkVoucher->url_voucher ?? "" }}">Imprimir voucher</button>



                                    @elseif($reserva->servico->integracao == \TourFacil\Core\Enum\IntegracaoEnum::SKYGLASS)
                                        <button type="button" data-action="print_voucher" class="btn btn-gradient-03 btn-block mb-3 disable-action"
                                                data-url="{{ route('app.reservas.print', $reserva->voucher) }}">Imprimir voucher</button>
                                    @else
                                        <button type="button" data-action="print_voucher" class="btn btn-gradient-03 btn-block mb-3 disable-action"
                                                data-url="{{ route('app.reservas.print', $reserva->voucher) }}">Imprimir voucher</button>
                                    @endif
                                @else
                                    <button type="button" id="forcar-integracao-btn" reserva_id="{{ $reserva->id }}" data-action="forcarIntegracao" data-url="{{ route('app.integracao.integrar') }}" class="btn btn-gradient-04 btn-block mb-3 disable-action">
                                        Forçar integração
                                    </button>
                                @endif
                            @else
                                <button id="finalizar-reserva-botao" reserva_id="{{ $reserva->id }}" style="background: #FF9800;" type="button" class="btn btn-gradient-03 btn-block mb-3 disable-action">
                                    Finalizar Reserva
                                </button>
                            @endif
                            {{-- Cancelamento somente administrador --}}
                            @if(userIsAdmin())
                                <button type="button" class="btn btn-gradient-01 btn-block mb-3 disable-action" data-target="#cancelar-reserva" data-toggle="modal">Cancelar reserva</button>
                            @else
                                <button type="button" disabled class="btn btn-gradient-01 btn-block mb-3 disabled">Cancelar reserva</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($reserva->dadoClienteReservaPedido->count() > 0)
        <div class="row">
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
                                <h2 class="ml-auto mb-0">{{ $reserva->quantidade }} acompanhantes</h2>
                            </div>
                            <div class="col-12 mb-0 mt-2">
                                @foreach($reserva->dadoClienteReservaPedido as $index => $dado_cliente)
                                    <form action="{{ route('app.reservas.acompanhante.update') }}" data-validate-ajax>
                                        <div class="form-group row {{ ($loop->remaining) ?: "mb-0" }}">
                                            <div class="col-xl-12 mb-3">
                                                <strong class="info-terminal">{{ $index + 1 }}° Acompanhante - {{ $dado_cliente->variacaoServico->nome }}</strong>
                                            </div>
                                            <div class="col-xl-4 mb-3">
                                                <label for="nome_acop_{{ $index }}" class="form-control-label">Nome completo</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon addon-secondary">
                                                        <i class="la la-user"></i>
                                                    </span>
                                                    <input id="nome_acop_{{ $index }}" type="text" class="form-control disable-action" placeholder="Nome completo do acompanhante" required {{ (!$dado_cliente->status || !userIsAdmin()) ? "disabled" : "" }}
                                                           data-required data-min="5" title="Nome do acompanhante" data-auto-capitalize name="nome" value="{{ $dado_cliente->nome }}">
                                                    <div class="invalid-feedback"></div>
                                                </div>
                                            </div>
                                            <div class="col-xl-3 mb-3">
                                                <label for="documento_acop_{{ $index }}" class="form-control-label">Núm. documento </label>
                                                <div class="input-group">
                                                    <span class="input-group-addon addon-secondary">
                                                        <i class="la la-file-text"></i>
                                                    </span>
                                                    <input id="documento_acop_{{ $index }}" type="text" class="form-control disable-action" placeholder="CPF ou RG do acompanhante" required {{ (!$dado_cliente->status || !userIsAdmin()) ? "disabled" : "" }}
                                                           data-required data-min="1" title="Documento do acompanhante" name="documento" value="{{ $dado_cliente->documento }}">
                                                    <div class="invalid-feedback"></div>
                                                </div>
                                            </div>
                                            <div class="col-xl-3 mb-3">
                                                <label for="nascimento_acop_{{ $index }}" class="form-control-label">Nascimento</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon addon-secondary">
                                                        <i class="la la-calendar"></i>
                                                    </span>
                                                    <input id="nascimento_acop_{{ $index }}" type="tel" class="form-control vanillaMask disable-action" placeholder="DD/MM/AAAA" required data-mask="date" {{ (!$dado_cliente->status || !userIsAdmin()) ? "disabled" : "" }}
                                                           data-required data-min="5" title="Nascimento do acompanhante" name="nascimento" value="{{ $dado_cliente->nascimento->format('d/m/Y') }}">
                                                    <div class="invalid-feedback"></div>
                                                </div>
                                            </div>
                                            <div class="hide">
                                                {{ method_field("PUT") }}
                                                <input type="hidden" name="acompanhante_id" value="{{ $dado_cliente->id }}">
                                            </div>
                                            {{-- remover somente administrador --}}
                                            @if(userIsAdmin())
                                                {{-- Permite a remocao do acompanhante--}}
                                                @if($dado_cliente->status && $allow_remocao)
                                                    <div class="col-xl-2 pr-xl-0 mb-3 d-flex flex-row align-self-end">
                                                        <button class="btn btn-success mr-2 disable-action">Atualizar</button>
                                                        <button type="button" data-action="delete_acompanhante" data-index="{{ $index }}" data-id="{{ $dado_cliente->id }}" data-tooltip="Remover acompanhante"
                                                                data-url="{{ route('app.reservas.acompanhante.delete') }}" class="btn btn-gradient-01 has-tooltip-top disable-action"><i class="la la-trash m-0"></i></button>
                                                    </div>
                                                    {{-- NÃO permite a remocao do acompanhante quando for o ultimo da reserva --}}
                                                @elseif($allow_remocao == false && $dado_cliente->status)
                                                    <div class="col-xl-2 pr-xl-0 mb-3 d-flex flex-row align-self-end">
                                                        <button type="button" disabled class="btn disabled mr-2">Atualizar</button>
                                                        <button type="button" disabled class="btn disabled disable-action"><i class="la la-trash m-0"></i></button>
                                                    </div>
                                                    {{-- Informação de quando que foi removido --}}
                                                @else
                                                    <div class="col-xl-2 pr-xl-0 mb-3 d-flex flex-column align-self-end">
                                                        <p class="mb-0">Removido no dia</p>
                                                        <strong>{{ $dado_cliente->deleted_at->format('d/m/Y H:i') }}</strong>
                                                    </div>
                                                @endif
                                            @else
                                                @if($dado_cliente->status)
                                                    <div class="col-xl-2 pr-xl-0 mb-3 d-flex flex-row align-self-end">
                                                        <button type="button" disabled class="btn disabled mr-2">Atualizar</button>
                                                        <button type="button" disabled class="btn disabled disable-action"><i class="la la-trash m-0"></i></button>
                                                    </div>
                                                @else
                                                    <div class="col-xl-2 pr-xl-0 mb-3 d-flex flex-column align-self-end">
                                                        <p class="mb-0">Removido no dia</p>
                                                        <strong>{{ $dado_cliente->deleted_at->format('d/m/Y H:i') }}</strong>
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    </form>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-12">
                <div class="widget has-shadow">
                    <div class="widget-body pl-lg-5 pr-lg-5">
                        <div class="row">
                            <div class="col-xl-8 mt-4 mb-3">
                                <div class="section-title mr-auto">
                                    <i class="la la la-users la-3x pull-left pr-4"></i>
                                    <h3>Quantidade adquirida</h3>
                                    <p class="mt-1">Listagem das opções compradas pelo cliente.</p>
                                </div>
                            </div>
                            <div class="col-xl-4 mt-4 mb-5 d-flex align-items-center">
                                <h2 class="ml-auto mb-0">{{ $reserva->quantidade }} pessoas</h2>
                            </div>
                            <div class="col-12 mb-2 mt-1">
                                <div class="table-responsive">
                                    <table class="table table-divider mb-0">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Variação</th>
                                            <th>Descrição</th>
                                            <th>Valor total</th>
                                            <th>Valor net</th>
                                            <th class="text-center" width="150">Administração</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($reserva->quantidadeReserva as $quantidade_reserva)
                                            @php($status = $quantidade_reserva->status)
                                            <tr class="{{ ($status) ? : "disabled" }}">
                                                <td>{{ $count_quantidade++ }}</td>
                                                <td class="text-primary"><strong>({{ $quantidade_reserva->quantidade }}x) {{ $quantidade_reserva->variacaoServico->nome }}</strong></td>
                                                <td class="text-primary">{{ $quantidade_reserva->variacaoServico->descricao }}</td>
                                                <td class="text-success"><strong>R$ {{ formataValor($quantidade_reserva->valor_total) }}</strong></td>
                                                <td class="text-primary">R$ {{ formataValor($quantidade_reserva->valor_net) }}</td>
                                                <td class="text-center aviso">
                                                    {{-- remover somente administrador --}}
                                                    @if(userIsAdmin())
                                                        @if($status && $reserva->quantidade > 1)
                                                            <button type="button" class="btn btn-warning disable-action" data-action="edit_quantidade"
                                                                    data-route="{{ route('app.reservas.quantidade.view', $quantidade_reserva->id) }}">Editar quantidade <i class="la la-edit right"></i></button>
                                                        @elseif($status && $reserva->quantidade == 1)
                                                            <button type="button" class="btn btn-warning disable-action disabled" disabled>Editar quantidade <i class="la la-edit right"></i></button>
                                                        @else
                                                            <p class="mb-0">Removido no dia</p>
                                                            <strong>{{ $quantidade_reserva->deleted_at->format('d/m/Y H:i') }}</strong>
                                                        @endif
                                                    @else
                                                        @if($quantidade_reserva->status)
                                                            <button type="button" class="btn btn-warning disable-action disabled" disabled>Editar quantidade <i class="la la-edit right"></i></button>
                                                        @else
                                                            <p class="mb-0">Removido no dia</p>
                                                            <strong>{{ $quantidade_reserva->deleted_at->format('d/m/Y H:i') }}</strong>
                                                        @endif
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Caso a reserva tenha campos adicionais --}}
    @if($qtd_campos_adicionais > 0)
        <div class="row">
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
                                <h2 class="ml-auto mb-0">{{ $qtd_campos_adicionais }} campos</h2>
                            </div>
                            <div class="col-12 mb-2 mt-1">
                                <form action="{{ route('app.reservas.adicionais.update') }}" data-validate-ajax>
                                    <div class="form-group row mb-3">
                                        @foreach($reserva->campoAdicionalReservaPedido as $index => $campo_adicional)
                                            <div class="col-xl-{{ $colunas_adicionais }} mb-3">
                                                <label for="adicional_{{ $campo_adicional->id }}" class="form-control-label">{{ $campo_adicional->campoAdicionalServico->campo }}</label>
                                                <input id="adicional_{{ $campo_adicional->id }}" type="text" class="form-control disable-action" placeholder="{{ $campo_adicional->campoAdicionalServico->placeholder }}" data-min="2"
                                                       title="{{ $campo_adicional->campoAdicionalServico->campo }}" {{ ($campo_adicional->campoAdicionalServico->is_required) ? "required data-required" : "" }}
                                                       value="{{ $campo_adicional->informacao }}" name="adicionais[{{ $index }}][informacao]" >
                                                <input type="hidden" name="adicionais[{{ $index }}][campo_adicional_id]" value="{{ $campo_adicional->id }}">
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="hide">{{ method_field("PUT") }}</div>
                                    <div class="row">
                                        <div class="col-6">
                                            <span class="mt-2">Última alteração: {{ $reserva->campoAdicionalReservaPedido->first()->updated_at->format('d/m/Y H:i') }}</span>
                                        </div>
                                        <div class="col-6 text-right">
                                            <button  class="btn btn-success mr-2 disable-action">Atualizar dados adicionais</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(is_object($reserva->comissaoTerminal))
        <div class="row">
            <div class="col-12">
                <div class="widget has-shadow">
                    <div class="widget-body pl-lg-5 pr-lg-5">
                        <div class="row">
                            <div class="col-xl-8 mt-4 mb-3">
                                <div class="section-title mr-auto">
                                    <i class="la la la-desktop la-3x pull-left pr-4"></i>
                                    <h3>Comissão terminal de venda</h3>
                                    <p class="mt-1">Detalhes do terminal onde foi realizada a compra.</p>
                                </div>
                            </div>
                            <div class="col-xl-4 mt-4 mb-5 d-flex align-items-center">
                                <h2 class="ml-auto mb-0">R$ {{ formataValor($reserva->comissaoTerminal->comissao) }}</h2>
                            </div>
                            <div class="col-12 mb-2 mt-1">
                                <div class="table-responsive">
                                    <table class="table table-divider mb-0">
                                        <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th>Terminal de venda</th>
                                            <th class="text-center">Cód. Reserva</th>
                                            <th class="text-center">Prev. Pagamento</th>
                                            <th class="text-center">Valor reserva</th>
                                            <th class="text-center">Valor comissão</th>
                                            <th class="text-center">Status da comissão</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td class="text-primary">
                                                <strong>
                                                    <a href="{{ route('app.terminais.view', $reserva->comissaoTerminal->terminal->id) }}" target="_blank">{{ $reserva->comissaoTerminal->terminal->nome }}</a>
                                                </strong>
                                            </td>
                                            <td class="text-primary text-center">#{{ $reserva->voucher }}</td>
                                            <td class="text-center text-primary">{{ $reserva->comissaoTerminal->data_previsao->format('d/m/Y') }}</td>
                                            <td class="text-center text-primary">R$ {{ formataValor($reserva->valor_total) }}</td>
                                            <td class="text-center text-primary"><strong>R$ {{ formataValor($reserva->comissaoTerminal->comissao) }}</strong></td>
                                            <td class="text-center">
                                                <strong class="text-{{ $reserva->comissaoTerminal->cor_status_comissao }} text-uppercase">
                                                    {{  $reserva->comissaoTerminal->status_comissao }}
                                                </strong>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($reserva->historicoReservaPedido->count() > 0)
        <div class="row">
            <div class="col-12">
                <div class="widget has-shadow">
                    <div class="widget-body pl-lg-5 pr-lg-5">
                        <div class="row">
                            <div class="col-xl-8 mt-3 mb-3">
                                <div class="section-title mr-auto">
                                    <i class="la la-history la-3x pull-left pr-4"></i>
                                    <h3>Histórico de alterações</h3>
                                    <p class="mt-1">Listagem das alterações realizadas na reserva.</p>
                                </div>
                            </div>
                            <div class="col-xl-4 mt-4 mb-5 d-flex align-items-center">
                                <h2 class="ml-auto mb-0">{{ $reserva->historicoReservaPedido->count() }} alterações</h2>
                            </div>
                            <div class="col-12 mb-2 mt-1">
                                <div class="table-responsive">
                                    <table class="table table-divider mb-0">
                                        <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th>Motivo da alteração</th>
                                            <th>Usuário</th>
                                            <th class="text-center">Valor total</th>
                                            <th class="text-center">Valor net</th>
                                            <th class="text-center">Data da alteração</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($reserva->historicoReservaPedido as $alteracao)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td class="text-primary">{{ $alteracao->motivo_historico }}</td>
                                                <td class="text-primary">{{ $alteracao->user->name }}</td>
                                                <td class="text-center text-primary">R$ {{ formataValor($alteracao->valor) }}</td>
                                                <td class="text-center text-primary">R$ {{ formataValor($alteracao->valor_fornecedor) }}</td>
                                                <td class="text-center text-primary">{{ $alteracao->created_at->format('d/m/Y H:i') }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal para cancelar a reserva --}}
    <div id="cancelar-reserva" class="modal modal-top fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Cancelamento da reserva</h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">×</span>
                        <span class="sr-only">close</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form data-validate-ajax action="{{ route('app.reservas.delete') }}" class="form-horizontal">
                        <div class="form-group row">
                            <div class="col-xl-12 mb-4">
                                <div class="alert alert-secondary m-0" role="alert">
                                    Informe abaixo um motivo para o cancelamento da reserva
                                </div>
                            </div>
                            <div class="col-xl-12 mb-1">
                                <label for="motivo_id" class="form-control-label">Motivo do cancelamento</label>
                                <select id="motivo_id" name="motivo" class="form-control boostrap-select-custom" required
                                        data-required title="Selecione um motivo">
                                    @foreach($motivos_cancelamento as $motivo => $motivo_detalhe)
                                        <option value="{{ $motivo }}">{{ $motivo_detalhe }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="hide">
                            {{ method_field("DELETE") }}
                            <input type="hidden" name="reserva_id" value="{{ $reserva->id }}">
                        </div>
                        <div class="em-separator separator-dashed"></div>
                        <div class="mt-3 text-center mb-1">
                            <button class="btn btn-danger disable-action">Cancelar reserva <i class="la la-angle-right right"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal para alterar data --}}
    <div id="edit-agenda-reserva" class="modal modal-top fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Alterar data da reserva</h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">×</span>
                        <span class="sr-only">close</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form data-validate-ajax action="{{ route('app.reservas.agenda.update') }}" class="form-horizontal">
                        <div class="form-group row">
                            <div class="col-xl-6 mb-4">
                                <label for="data_atual" class="form-control-label">Data atual</label>
                                <input id="data_atual" type="text" class="form-control" placeholder="DD/MM/AAAA"
                                       readonly title="Data atual da reserva">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-xl-6 mb-4">
                                <label for="qtd_pessoas" class="form-control-label">Quantidade</label>
                                <input id="qtd_pessoas" type="text" class="form-control" placeholder="Número de pessoas"
                                       readonly title="Quantidade de pessoas">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-xl-12 mb-1">
                                <label for="new_date" class="form-control-label">Nova de utilização</label>
                                <div class="input-group">
                                    <span class="input-group-addon addon-secondary left"><i class="la la-calendar"></i></span>
                                    <input id="new_date" type="tel" class="form-control vanillaMask" placeholder="DD/MM/AAAA"
                                           title="Quantidade de pessoas" required data-required="true" data-mask="date" autocomplete="off">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="hide">
                            {{ method_field("PUT") }}
                            <input type="hidden" name="reserva_id" value="{{ $reserva->id }}">
                            <input type="hidden" name="data_agenda_id">
                        </div>
                        <div class="em-separator separator-dashed"></div>
                        <div class="mt-3 text-center mb-1">
                            <button class="btn btn-success disable-action">Alterar para nova data <i class="la la-refresh right"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal para editar a quantidade --}}
    <div id="edit-quantidade-reserva" class="modal modal-top fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Editar quantidade</h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">×</span>
                        <span class="sr-only">close</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form data-validate-ajax action="{{ route('app.reservas.quantidade.update') }}" class="form-horizontal">
                        <div class="form-group row">
                            <div class="col-xl-12 mb-4">
                                <label for="quantidade_atual" class="form-control-label">Quantidade atual da reserva</label>
                                <input id="quantidade_atual" type="text" class="form-control" placeholder="Quantidade"
                                       readonly title="Quantidade atual">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-xl-12 mb-1">
                                <label for="nova_quantidade" class="form-control-label">Nova quantidade <small>(Será cancelado a quantidade restante)</small></label>
                                <div class="input-group">
                                    <span class="input-group-addon addon-secondary left"><i class="la la-users"></i></span>
                                    <select id="nova_quantidade" name="nova_quantidade" class="form-control" required
                                            data-required title="Nova quantidade">
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="alert alert-secondary mt-4 mb-0" role="alert">
                                    <i class="la la-info-circle mr-2"></i>
                                    Os <strong>valores</strong> da reserva serão recalculados!
                                </div>
                            </div>
                        </div>
                        <div class="hide">
                            {{ method_field("PUT") }}
                            <input type="hidden" name="reserva_id" value="{{ $reserva->id }}">
                            <input type="hidden" name="quantidade_reserva_id">
                        </div>
                        <div class="em-separator separator-dashed"></div>
                        <div class="mt-3 text-center mb-1">
                            <button class="btn btn-gradient-01 disable-action">Alterar para nova quantidade <i class="la la-refresh right"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div style="display: none;">
        <span id="link-finalizacao">{{ Route('app.reservas.finalizar') }}</span>
        <span id="link-finalizacao-store">{{ Route('app.reservas.finalizar.store') }}</span>
    </div>

    @include('paginas.modais.lista-acompanhantes-finalizar')
    @include('paginas.modais.campo-adicional-finalizar')

    <script>

        let link_atribuir_reserva_afiliado = "{{ Route('app.relatorios.afiliados.atribuir-afiliado-reserva') }}";
        let link_atribuir_reserva_vendedor = "{{ Route('app.relatorios.afiliados.atribuir-vendedor-reserva') }}";

        let reserva_id = {{ $reserva->id }};

        let atribuirAfiliadoReserva = () => {

            App.loader.show()

            let afiliado_id = $("#select-afiliado").val()
            let payload = {
                reserva_id: reserva_id,
                afiliado_id: afiliado_id,
            }

            axios.post(link_atribuir_reserva_afiliado, payload).then((response) => {
                window.location.reload();
            });
        };

        let atribuirVendedorReserva = () => {

            App.loader.show()

            let vendedor_id = $("#select-vendedor").val()
            let payload = {
                reserva_id: reserva_id,
                vendedor_id: vendedor_id,
            }

            axios.post(link_atribuir_reserva_vendedor, payload).then((response) => {
                window.location.reload();
            });
        };


    </script>

@endsection
