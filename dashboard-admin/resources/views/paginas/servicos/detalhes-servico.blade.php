@extends('template.header')

@section('title', $servico->nome)

@section('content')

    @php($steps = 1)
    @php($length_errors = sizeof($info_servico['alertas']))

    <style>

        .icone {
            color: #4d5061;
            font-size: 1.5rem;
            -webkit-transition: all .3s;
            transition: all .3s;
            margin-bottom: 0.5rem;
        }

    </style>

    <div class="row">
        <div class="page-header pb-4">
            <div class="d-flex align-items-center">
                <h2 class="page-header-title text-dark">{{ str_limit($servico->nome, 35) }} <span class="text-gradient-01">| {{ $servico->canalVenda->nome }}</span></h2>
                <div>{{ Breadcrumbs::render('app.servicos.view') }}</div>
            </div>
        </div>
    </div>

    {{-- Informativo sobre o status do servico --}}
    <div class="row">
        <div class="col-12">
            @if($servico->status === $s_ativo)
                <div class="alert alert-success mb-4" role="alert">
                    <i class="la la-check-circle mr-2"></i>
                    Este serviço está <strong>Ativo</strong> no site!
                </div>
            @elseif($servico->status === $s_inativo)
                <div class="alert alert-danger mb-4" role="alert">
                    <i class="la la-ban mr-2"></i>
                    Este serviço está <strong>Inativo</strong>!
                </div>
            @elseif($servico->status === $s_indisponivel)
                <div class="alert alert-warning mb-4" role="alert">
                    <i class="la la-ban mr-2"></i>
                    Este serviço está <strong>Indisponível</strong>!
                </div>
            @elseif($servico->status === $s_pendente)
                <div class="alert alert-primary mb-4" role="alert">
                    <i class="la la-exclamation-circle mr-2"></i>
                    Este serviço está <strong>Pendente</strong>!
                </div>
            @endif
        </div>
    </div>

    {{-- Informativo sobre os erros do servico --}}
    @if($length_errors > 0)
        <div class="row">
            <div class="col-12">
                <div class="alert alert-danger mb-4" role="alert">
                    <ul class="list">
                        @foreach($info_servico['alertas'] as $aviso)
                            <li><strong>{{ $loop->iteration }}</strong>. {{ $aviso }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <div class="row" data-controller="ServicoCtrl">
        <div class="col-12">
            <ul id="tab_servico" class="nav nav-tabs nav-tab-header nav-tab-no-border tabs-mobile">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#descricao">
                        <i class="la la-certificate la-2x align-middle mr-2"></i> Descrição
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#categoria">
                        <i class="la la-filter la-2x align-middle mr-2"></i> Categorias
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#regra-servico">
                        <i class="la la-image la-2x align-middle mr-2"></i> Regras de serviço
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#regras">
                        <i class="la la-quote-left la-2x align-middle mr-2"></i> Regras e voucher
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#variacoes">
                        <i class="la la-users la-2x align-middle mr-2"></i> Variações
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#form">
                        <i class="la la-keyboard-o la-2x align-middle mr-2"></i> Formulário
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#galeria">
                        <i class="la la-image la-2x align-middle mr-2"></i> Galeria de fotos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#faq">
                        <i class="la la-info la-2x align-middle mr-2"></i> Faq
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane active" id="descricao">
                    <div class="widget has-shadow">
                        <div class="widget-body pl-lg-5 pr-lg-5">
                            <form data-validate-ajax method="POST" action="{{ route('app.servicos.update.descricao') }}" class="form-horizontal">
                                <div class="ml-auto mt-4 mb-4">
                                    <div class="row">
                                        <div class="col-12 col-md-8">
                                            <div class="section-title mr-auto">
                                                <h3>0{{ $steps++ }}. Informações do serviço</h3>
                                                <p class="mt-1">Informações detalhadas sobre o serviço.</p>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4 text-md-right">
                                            @if($servico->agenda)
                                                <a href="{{ route('app.agenda.view', $servico->agenda->id) }}" target="_blank" class="btn btn-secondary mr-2" title="Editar agenda da serviço">Agenda <i class="la la-calendar right"></i></a>
                                            @endif
                                            @if($servico->status === $s_ativo && $url_online != "")
                                                <a href="//{{ $url_online }}?preview=true" target="_blank" class="btn btn-secondary" title="Ver serviço online">Visualizar <i class="la la-external-link right"></i></a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xl-8 mb-3">
                                        <label for="nome" class="form-control-label">Nome do serviço</label>
                                        <input id="nome" type="text" class="form-control" placeholder="Ex. Maria Fumaça, Snowland, Sequencia de Fondue" required
                                               data-required data-min="5" title="Nome do serviço" data-auto-capitalize name="nome" value="{{ $servico->nome }}">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-xl-4 mb-3">
                                        <label for="valor_venda" class="form-control-label">Valor venda <small>&nbsp;(Menor valor da agenda)</small></label>
                                        <div class="input-group">
                                            <span class="input-group-addon addon-secondary left">R$</span>
                                            <input id="valor_venda" type="tel" class="form-control vanillaMaskMoney" placeholder="0,00" required
                                                   data-required data-min="4" title="Valor de venda" name="valor_venda" value="{{ $servico->valor_venda }}">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xl-4 mb-3">
                                        <label for="fornecedor_id" class="form-control-label">Fornecedor <small>&nbsp;(Não é possível alterar)</small></label>
                                        <input id="fornecedor_id" type="text" class="form-control" placeholder="Fonecedor"
                                               title="Fornecedor" value="{{ $servico->fornecedor->nome_fantasia }}" readonly>
                                    </div>
                                    <div class="col-xl-4 mb-3">
                                        <label for="destino_id" class="form-control-label">Destino <small>&nbsp;(Não é possível alterar)</small></label>
                                        <input id="destino_id" type="text" class="form-control" placeholder="Destino"
                                               title="Destino" value="{{ $servico->destino->nome }}" readonly>
                                    </div>
                                    <div class="col-xl-4 mb-3">
                                        <label for="canal_venda_id" class="form-control-label">Canal de venda <small>&nbsp;(Não é possível alterar)</small></label>
                                        <input id="canal_venda_id" type="text" class="form-control" placeholder="Canal de venda"
                                               readonly title="Canal de venda" value="{{ $servico->canalVenda->site }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xl-4 mb-3">
                                        <label for="info_clientes" class="form-control-label">Requer identificação? <small>&nbsp;(Por cliente)</small></label>
                                        <select id="info_clientes" name="info_clientes" class="form-control boostrap-select-custom" required
                                                data-required title="Selecione uma opção">
                                            @foreach($info_clientes as $key => $info_cliente)
                                                @if($servico->info_clientes == $key)
                                                    <option value="{{ $key }}" selected>{{ $info_cliente }}</option>
                                                @else
                                                    <option value="{{ $key }}">{{ $info_cliente }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-xl-4 mb-3">
                                        <label for="comissao_afiliado" class="form-control-label">Comissão do afiliado &nbsp;<small>(Em porcentagem)</small></label>
                                        <div class="input-group">
                                            <span class="input-group-addon addon-secondary left"><i class="la la-balance-scale"></i></span>
                                            <input id="comissao_afiliado" type="tel" class="form-control vanillaPorcentage" placeholder="0.00%" value="{{ $servico->comissao_afiliado }}"
                                                   title="Comissão do afiliado" name="comissao_afiliado" required data-required data-min="4" maxlength="6">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 mb-3">
                                        <label for="status_servico" class="form-control-label">Status do serviço &nbsp;<small>(Disponibilidade no site)</small></label>
                                        <div class="input-group">
                                            <span class="input-group-addon addon-secondary left"><i class="la la-thumb-tack"></i></span>
                                            @if($length_errors > 0)
                                                <input id="status_servico" type="text" class="form-control" readonly value="{{ ucfirst(mb_strtolower($s_pendente)) }}">
                                            @else
                                                <select id="status_servico" name="status" class="form-control boostrap-select-custom" required
                                                        data-required title="Selecione uma opção">
                                                    @foreach($status_servico as $status => $info_status)
                                                        @if($servico->status == $status)
                                                            <option value="{{ $status }}" selected>{{ $info_status }}</option>
                                                        @else
                                                            <option value="{{ $status }}">{{ $info_status }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback"></div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xl-8 mb-3">
                                        <label for="palavras_chaves" class="form-control-label">Palavras chaves <small>&nbsp;(Separadas por virgula)</small></label>
                                        <div class="input-group">
                                            <span class="input-group-addon addon-secondary left"><i class="la la-search"></i></span>
                                            <input id="palavras_chaves" type="text" class="form-control" placeholder="Palavras que facilitam para encontrar o serviço"
                                                   title="Palavras chaves" name="palavras_chaves" required data-required data-min="5" value="{{ $servico->palavras_chaves }}">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 mb-3">
                                        <label for="integracao" class="form-control-label">Possui integração?</label>
                                        <div class="input-group">
                                            <span class="input-group-addon addon-secondary left"><i class="la la-puzzle-piece"></i></span>
                                            <select id="integracao" name="integracao" class="form-control boostrap-select-custom" required
                                                    data-required title="Selecione uma opção">
                                                @foreach($integracoes as $key => $integracao)
                                                    @if($servico->integracao == $key)
                                                        <option value="{{ $key }}" selected>{{ $integracao }}</option>
                                                    @else
                                                        <option value="{{ $key }}">{{ $integracao }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="em-separator separator-dashed"></div>
                                <div class="ml-auto mt-4 mb-4">
                                    <div class="section-title mr-auto">
                                        <h3>0{{ $steps++ }}. Antecedência de venda e corretagem.</h3>
                                        <p class="mt-1">Dias de bloqueio para a venda e valor de ajuste para margem.</p>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xl-3 mb-3">
                                        <label for="antecedencia_venda" class="form-control-label">Antecedência de venda <small>&nbsp;(Deadline)</small></label>
                                        <select id="antecedencia_venda" name="antecedencia_venda" class="form-control" required
                                                data-required title="Selecione uma opção">
                                            @for($antecedencia = 0; $antecedencia <= 10; $antecedencia++)
                                                @if($antecedencia === 0)
                                                    <option value="{{ $antecedencia }}" {{ ($servico->antecedencia_venda == $antecedencia) ? "selected" : "" }}>Nenhuma</option>
                                                @else
                                                    <option value="{{ $antecedencia }}" {{ ($servico->antecedencia_venda == $antecedencia) ? "selected" : "" }}>{{ $antecedencia }} Dia(s)</option>
                                                @endif
                                            @endfor
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    <div class="col-xl-3 mb-3">
                                        <label for="antecedencia_venda" class="form-control-label">Hora máxima de venda<small>&nbsp;(Deadline) Deixe zero para desativar</small></label>
                                        <input id="hora_maxima_antecedencia" name="hora_maxima_antecedencia" type="time" value="{{ $servico->hora_maxima_antecedencia ?? '00:00' }}" class="form-control">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-xl-3 mb-3">
                                        <label for="tipo_corretagem" class="form-control-label">Tipo de corretagem <small>&nbsp;(R$ ou %)</small></label>
                                        <select id="tipo_corretagem" name="tipo_corretagem" class="form-control" required
                                                data-required title="Selecione uma opção">
                                            @foreach($corretagens as $key => $corretagem)
                                                @if($servico->tipo_corretagem == $key)
                                                    <option value="{{ $key }}" selected>{{ $corretagem }}</option>
                                                @else
                                                    <option value="{{ $key }}">{{ $corretagem }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-xl-3 mb-3 corretagem-percentual {{ ($servico->tipo_corretagem == $c_percentual) ? "" : "hide" }}">
                                        <label for="corretagem_porcent" class="form-control-label">Porcentagem da corregatem</label>
                                        <div class="input-group">
                                            <span class="input-group-addon addon-secondary left">%</span>
                                            <input id="corretagem_porcent" type="tel" class="form-control vanillaMask" placeholder="0.0"
                                                   title="Porcentagem da corregatem" name="corretagem" data-mask="porcent" maxlength="3"
                                                   {{ ($servico->tipo_corretagem == $c_percentual) ? "" : "disabled" }} value="{{ $servico->corretagem }}">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 mb-3 corretagem-valor-fixo {{ ($servico->tipo_corretagem == $c_percentual) ? "hide" : "" }}">
                                        <label for="corretagem" class="form-control-label">Valor da corregatem</label>
                                        <div class="input-group">
                                            <span class="input-group-addon addon-secondary left">R$</span>
                                            <input id="corretagem" type="tel" class="form-control vanillaMaskMoney" placeholder="0,00" value="{{ $servico->corretagem }}"
                                                   title="Valor da corregatem" name="corretagem" {{ ($servico->tipo_corretagem == $c_percentual || $servico->corretagem == 0) ? "disabled" : "" }}>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="em-separator separator-dashed"></div>
                                <div class="ml-auto mt-4 mb-4">
                                    <div class="section-title mr-auto">
                                        <h3>0{{ $steps++ }}. Localização e horários de atendimento</h3>
                                        <p class="mt-1">Endereço onde ocorre o serviço (Será mostrado um mapa na página do serviço)</p>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xl-4 mb-3">
                                        <label for="cidade" class="form-control-label">Cidade <small>&nbsp; (Obrigatório)</small></label>
                                        <div class="input-group">
                                            <span class="input-group-addon addon-secondary left"><i class="la la-map-marker"></i></span>
                                            <input id="cidade" name="cidade" type="text" class="form-control" placeholder="Ex. Gramado, Canela e etc."
                                                   title="Cidade" data-required data-min="5" required data-auto-capitalize value="{{ $servico->cidade }}">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-xl-8 mb-3">
                                        <label for="localizacao" class="form-control-label">Endereço <small>&nbsp; (Não é obrigatório)</small></label>
                                        <div class="input-group">
                                            <span class="input-group-addon addon-secondary left"><i class="la la-map-o"></i></span>
                                            <input id="localizacao" name="localizacao" type="text" class="form-control" placeholder="Endereço completo para o mapa (Google Maps)"
                                                   title="Endereço" value="{{ $servico->localizacao }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xl-12 mb-3">
                                        <label for="horario" class="form-control-label">Horário de atendimento <small>&nbsp;(Abertura e fechamento)</small></label>
                                        <div class="input-group">
                                            <span class="input-group-addon addon-secondary left"><i class="la la-clock-o"></i></span>
                                            <input id="horario" type="text" class="form-control" placeholder="A partir das 08:00h até às 16:00h" value="{{ $servico->horario }}"
                                                   title="Horário de atendimento" name="horario" required data-required data-min="5" maxlength="100">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="em-separator separator-dashed"></div>
                                <div class="ml-auto mt-4 mb-4">
                                    <div class="section-title mr-auto">
                                        <h3>0{{ $steps++ }}. Tags Serviço</h3>
                                        <p class="mt-1">Tags referentes ao serviço.</p>
                                    </div>
                                </div>
                                <div class="tag-servico">
                                    @foreach($servico->tags as $tag_servico)
                                        <button type="button"  tipo-tag="EXTERNA" class="btn btn-secondary mr-1 mb-1" data-action="editTag" data-target="#edit-tag"
                                                data-tag="{{ route('app.servicos.tags.view', $tag_servico->id) }}" data-route="{{ route('app.servicos.tags.icones') }}">
                                            <i class="jam jam-{{ $tag_servico->icone }}"></i> {{ $tag_servico->descricao }}
                                        </button>
                                    @endforeach
                                    <button type="button" class="btn btn-gradient-02 mr-1 mb-1" data-target="#new-tag"
                                            data-action="newTag" tipo-tag="EXTERNA" data-route="{{ route('app.servicos.tags.icones') }}">Adicionar tag</button>
                                </div>

                                <div class="em-separator separator-dashed"></div>
                                <div class="ml-auto mt-4 mb-4">
                                    <div class="section-title mr-auto">
                                        <h3>0{{ $steps++ }}. Tags Serviço Descrição</h3>
                                        <p class="mt-1">Tags referentes a descrição do serviço.</p>
                                    </div>
                                </div>
                                <div class="tag-servico">
                                    @foreach($servico->tagsInternas as $tag_servico)
                                        <button style="max-width: 40%;" type="button" tipo-tag="INTERNA"  class="btn btn-secondary mr-1 mb-1" data-action="editTag" data-target="#edit-tag"
                                                data-tag="{{ route('app.servicos.tags.view2', $tag_servico->id) }}" data-route="{{ route('app.servicos.tags.icones') }}">
                                            <i class="{{$tag_servico->icone }}"></i> {{ $tag_servico->titulo }}
                                            <br>
                                            <small>{{ $tag_servico->descricao }}</small>
                                        </button>
                                    @endforeach
                                    <button type="button" class="btn btn-gradient-02 mr-1 mb-1" data-target="#new-tag"
                                            data-action="newTag" tipo-tag="INTERNA" data-route="{{ route('app.servicos.tags.icones') }}">Adicionar tag</button>
                                </div>

                                <div class="em-separator separator-dashed"></div>
                                <div class="ml-auto mt-4 mb-4">
                                    <div class="section-title mr-auto">
                                        <h3>0{{ $steps++ }}. Ferramentas de SEO</h3>
                                        <p class="mt-1">Título da página e descrição curta do serviço, utilizadas para SEO.</p>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xl-6 mb-3">
                                        <label for="titulo_pagina" class="form-control-label">Título da página <small>&nbsp;(Opcional)</small></label>
                                        <textarea id="titulo_pagina" name="titulo_pagina" class="form-control not-resize" rows="5" title="Título da página"
                                                  placeholder="O título da página HTML" data-required data-min="5">{{ $servico->titulo_pagina }}</textarea>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-xl-6 mb-3">
                                        <label for="descricao_curta" class="form-control-label">Descrição curta <small>&nbsp;(Sem marcação markdown até 70 caracteres)</small></label>
                                        <textarea id="descricao_curta" name="descricao_curta" class="form-control not-resize" rows="5" title="Descrição curta"
                                                  placeholder="Descreva o serviço em até 70 caracteres" data-required data-min="5" required>{{ $servico->descricao_curta }}</textarea>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xl-6 mb-3">
                                        <label for="titulo_servico" class="form-control-label">Título do serviço <small>&nbsp;(Opcional)</small></label>
                                        <div class="input-group">
                                            <span class="input-group-addon addon-secondary left"><i class="la la-clock-o"></i></span>
                                            <input id="titulo_servico" type="text" class="form-control" placeholder="Título do serviço na sessão de informações" value="{{ $servico->titulo_servico }}"
                                                   title="Título do serviço" name="titulo_servico" data-min="5" maxlength="100">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="em-separator separator-dashed"></div>
                                <div class="ml-auto mt-4 mb-4">
                                    <div class="section-title mr-auto">
                                        <h3>0{{ $steps++ }}. Descrição completa</h3>
                                        <p class="mt-1">Descrição completa do serviço, utilizada na página do serviço.</p>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xl-12 mb-0">
                                        <label for="descricao_completa" class="form-control-label">Descrição completa <small>&nbsp;(Com marcação markdown)</small></label>
                                        <textarea id="descricao_completa" name="descricao_completa" class="form-control not-resize simple-editor" rows="5" title="Descrição completa"
                                                  placeholder="Descreva os detalhes do serviço" data-required data-min="5" required>{{ $servico->descricao_completa }}</textarea>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="hide">
                                    {{ method_field("PUT") }}
                                    <input type="hidden" name="servico_id" value="{{ $servico->id }}">
                                    <input type="hidden" name="canal_venda_id" value="{{ $servico->canalVenda->id }}">
                                </div>
                                <div class="em-separator separator-dashed"></div>
                                <div class="text-center">
                                    <button class="btn btn-gradient-01" type="submit">Atualizar descrição <i class="la la-save right"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="categoria">
                    <div class="widget widget-18 has-shadow">
                        <div class="widget-body pl-lg-5 pr-lg-5">
                            <div class="ml-auto mt-4 mb-4">
                                <div class="section-title mr-auto">
                                    <h3>0{{ $steps++ }}. Categorias do serviço</h3>
                                    <p class="mt-1">Selecione as categorias relacionadas ao serviço.</p>
                                </div>
                            </div>
                            <div class="em-separator separator-dashed"></div>
                            <div class="row mt-3">
                                {{-- caso nao tenha uma categoria --}}
                                @if($servico->categorias->count() == 0)
                                    <div class="col-xl-12">
                                        <div class="alert alert-secondary alert-lg square">
                                            <i class="la la-rocket mr-2"></i>
                                            <strong>Adicione</strong> uma categoria ao serviço!
                                        </div>
                                    </div>
                                @endif
                                {{--Percorre as categorias--}}
                                @foreach($servico->categorias as $categoria)
                                    <div class="col-xl-6">
                                        <div class="card-categoria has-shadow mb-5">
                                            <div class="row">
                                                <div class="col-xl-5 pr-xl-0">
                                                    <div class="card-categoria-image placeholder-img place-loader-medium lazyload" data-src="{{ $categoria->foto_categoria }}"></div>
                                                </div>
                                                <div class="col-xl-7 pl-xl-0">
                                                    <div class="card-categoria-body">
                                                        <a href="{{ route('app.servicos.view.categoria', [$servico->id, $categoria->id]) }}" class="btn btn-gradient-01 mt-3 pull-right"
                                                           data-action="edit-categoria">Editar</a>
                                                        @if($categoria->pivot->padrao == $e_categoria_padrao)
                                                            <strong class="text-info">Padrão</strong>
                                                        @else
                                                            <span>Normal</span>
                                                        @endif
                                                        <br><h4 class="mt-1">{{ $categoria->nome }}</h4>
                                                        <p>{{ $servico->destino->nome }}</p>
                                                    </div>
                                                    <div class="card-categoria-secoes">
                                                        <ul class="list-group w-100 widget-scroll">
                                                            @foreach($servico->secoesCategoria as $secao)
                                                                @if($secao->categoria_id == $categoria->id)
                                                                    <li class="list-group-item">
                                                                        <div class="media">
                                                                            <div class="media-left align-self-center mr-3">
                                                                                <div class="media-letter">
                                                                                    <span>{{ str_limit($secao->nome, 1, "") }}</span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="media-body align-self-center">
                                                                                <div class="people-name">{{ $secao->nome }}</div>
                                                                            </div>
                                                                            <div class="media-right align-self-center">
                                                                                <div class="checkbox check"></div>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                @endif
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="em-separator separator-dashed mt-0"></div>
                            <div class="text-center mt-3">
                                <button class="btn btn-gradient-01" data-action="add-categoria"
                                        data-target="#add-categoria" data-route="{{ route('app.destinos.view.json', $servico->destino->id) }}">
                                    Adicionar categoria <i class="la la-plus right"></i></button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane" id="regra-servico">
                    <div class="widget has-shadow">
                        <div class="widget-body pl-lg-5 pr-lg-5">
                            <form data-validate-ajax method="POST" action="{{ route('app.servicos.regras-servico.store-ou-update') }}" class="form-horizontal">
                                <div class="ml-auto mt-4 mb-4">
                                    <div class="section-title mr-auto">
                                        <h3>0{{ $steps++ }}. Regras do serviço</h3>
                                        <p class="mt-1">Descreva as regras que compõem o serviço.</p>
                                    </div>
                                </div>
                                    <div class="form-group row">
                                        <div class="col-xl-4 mb-3">
                                            <label for="antecedencia" class="form-control-label">Antecedencia</label>
                                            <input id="regra_ant_antecedencia" type="number" class="form-control" placeholder="Quantidade de dias" required
                                                   title="Antecedencia_regra" data-auto-capitalize name="regra_ant_antecedencia" value="{{ $regra_antecedencia->regras['antecedencia'] ?? "" }}">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="col-xl-4 mb-3">
                                            <label for="antecedencia" class="form-control-label">Tipo valor</label>
                                            <select id="tipo_categoria" name="regra_ant_tipo_valor" class="form-control boostrap-select-custom" required
                                                    data-required disabled>
                                                <option selected value="FIXO">FIXO</option>
                                                <option value="PERCENTUAL">PERCENTUAL</option>
                                            </select>
                                        </div>
                                        <div class="col-xl-4 mb-3">
                                            <label for="valor_venda" class="form-control-label">Valor</label>
                                            <div class="input-group">
                                                <span class="input-group-addon addon-secondary left">R$</span>
                                                <input id="regra_valor" type="tel" class="form-control vanillaMaskMoney" placeholder="0,00" required
                                                       title="Valor de venda" name="regra_ant_valor" value="{{ $regra_antecedencia->regras['valor'] ?? "" }}">
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>
                                <div class="em-separator separator-dashed"></div>
                                <input type="hidden" name="regra_ant_regra_id" value="{{ $regra_antecedencia->id ?? "" }}">
                                <input type="hidden" name="regra_ant_servico_id" value="{{ $servico->id }}">
                                <input type="submit" value="Atualizar">
                            </form>
                        </div>
                    </div>
                </div>


                <div class="tab-pane" id="regras">
                    <div class="widget has-shadow">
                        <div class="widget-body pl-lg-5 pr-lg-5">
                            <form data-validate-ajax method="POST" action="{{ route('app.servicos.update.regras') }}" class="form-horizontal">
                                <div class="ml-auto mt-4 mb-4">
                                    <div class="section-title mr-auto">
                                        <h3>0{{ $steps++ }}. Regras do serviço</h3>
                                        <p class="mt-1">Descreva as regras que compõem o serviço.</p>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xl-12 mb-0">
                                        <label for="regras" class="form-control-label">Regras serviço <small>&nbsp;(Com marcação markdown)</small></label>
                                        <textarea id="regras" name="regras" class="form-control not-resize simple-editor" rows="5" title="Regras serviço"
                                                  placeholder="Descreva as regras do serviço">{{ $servico->regras }}</textarea>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="em-separator separator-dashed"></div>
                                <div class="ml-auto mt-4 mb-4">
                                    <div class="section-title mr-auto">
                                        <h3>0{{ $steps++ }}. Observação no voucher</h3>
                                        <p class="mt-1">Adicione observações para serem impressas no voucher.</p>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xl-12 mb-0">
                                        <label for="observacao_voucher" class="form-control-label">Observações <small>&nbsp;(Com marcação markdown)</small></label>
                                        <textarea id="observacao_voucher" name="observacao_voucher" class="form-control not-resize simple-editor" rows="5" title="Observações no voucher"
                                                  placeholder="Liste as observações para serem adicionadas no voucher">{{ $servico->observacao_voucher }}</textarea>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="hide">
                                    {{ method_field("PUT") }}
                                    <input type="hidden" name="servico_id" value="{{ $servico->id }}">
                                </div>
                                <div class="em-separator separator-dashed"></div>
                                <div class="text-center">
                                    <button class="btn btn-gradient-01" type="submit">Salvar alterações <i class="la la-save right"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                {{-- Variacões --}}
                <div class="tab-pane" id="variacoes">
                    <div class="widget has-shadow">
                        <div class="widget-body pl-lg-5 pr-lg-5">
                            <div class="ml-auto mt-4 mb-4">
                                <div class="section-title mr-auto">
                                    <h3>0{{ $steps++ }}. Variações do serviço</h3>
                                    <p class="mt-1">Cadastre as variações para o serviço.</p>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-divider mb-0">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Opção</th>
                                        <th>Descrição</th>
                                        <th>Bloqueio</th>
                                        <th class="text-center">Markup</th>
                                        <th class="text-center">Percentual</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Administração</th>
                                        <th class="text-center">Min Pax's</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($servico->variacaoServico as $variacao)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                @if($variacao->status)
                                                    <a href="{{ route('app.servicos.view.variacao', $variacao->id) }}" data-action="edit-variation">
                                                        {{ $variacao->nome }} @if($variacao->variacao_destaque)*@endif
                                                    </a>
                                                @else
                                                    {{ $variacao->nome }}
                                                @endif
                                            </td>
                                            <td>{{ str_limit($variacao->descricao, 25) }}</td>
                                            <td>{{ $variacao->bloqueio }}</td>
                                            <td class="text-center">
                                                @if(userIsAdmin())
                                                    <a href="{{ route('app.servicos.view.variacao', $variacao->id) }}"
                                                       data-action="edit-markup">{{ $variacao->markup }}</a>
                                                @else
                                                    {{ $variacao->markup }}
                                                @endif
                                            </td>
                                            <td class="text-center">{{ number_format($variacao->percentual, 2) }}% tarifa</td>
                                            <td class="text-center">
                                                @if($variacao->status)
                                                    <span class="badge-text badge-text-small info">Ativo</span>
                                                @else
                                                    <span class="badge-text badge-text-small danger">Inativo</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($variacao->status)
                                                    <a href="{{ route('app.servicos.view.variacao', $variacao->id) }}" data-action="edit-variation" class="btn btn-outline-primary">
                                                        Editar <i class="la la-edit right"></i>
                                                    </a>
                                                @else
                                                    <a href="{{ route('app.servicos.update.reactivate') }}" data-action="activate-variation"
                                                       data-id="{{ $variacao->id }}" class="btn btn-outline-light">
                                                        Ativar <i class="la la-level-up right"></i>
                                                    </a>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                {{ $variacao->min_pax }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="em-separator separator-dashed"></div>
                            <div class="text-center">
                                <button class="btn btn-gradient-01" data-target="#add-variation" data-toggle="modal">
                                    Nova variação <i class="la la-plus right"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Formulários --}}
                <div class="tab-pane" id="form">
                    <div class="widget has-shadow">
                        <div class="widget-body pl-lg-5 pr-lg-5">
                            <div class="ml-auto mt-4 mb-4">
                                <div class="section-title mr-auto">
                                    <h3>{{ $steps++ }}. Formulário</h3>
                                    <p class="mt-1">Adicione campos adicionais a serem preenchidos após a compra.</p>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-divider mb-0">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nome do campo</th>
                                        <th>Placeholder</th>
                                        <th class="text-center">Obrigatório</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Administração</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if($servico->camposAdicionais->count() > 0)
                                        @foreach($servico->camposAdicionais as $campo)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    @if($campo->status)
                                                        <a data-action="edit-field" href="{{ route('app.servicos.view.form', $campo->id) }}">
                                                            {{ $campo->campo }}
                                                        </a>
                                                    @else
                                                        {{ $campo->campo }}
                                                    @endif
                                                </td>
                                                <td>{{ $campo->placeholder }}</td>
                                                <td class="text-center">{{ $campo->obrigatorio }}</td>
                                                <td class="text-center">
                                                    @if($campo->status)
                                                        <span class="badge-text badge-text-small info">Ativo</span>
                                                    @else
                                                        <span class="badge-text badge-text-small danger">Inativo</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if($campo->status)
                                                        <a data-action="edit-field" href="{{ route('app.servicos.view.form', $campo->id) }}" class="btn btn-outline-primary">
                                                            Editar <i class="la la-edit right"></i>
                                                        </a>
                                                    @else
                                                        <a data-action="activate-field" href="{{ route('app.servicos.active.form') }}" data-id="{{ $campo->id }}" class="btn btn-outline-light">
                                                            Ativar <i class="la la-level-up right"></i>
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="6" class="text-center pt-4 pb-4">Sem campos adicionais</td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                            <div class="em-separator separator-dashed"></div>
                            <div class="text-center">
                                <button class="btn btn-gradient-02" data-target="#add-field" data-toggle="modal">
                                    Adicionar campo <i class="la la-plus right"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="galeria">
                    <div class="widget has-shadow">
                        <div class="widget-body pl-lg-5 pr-lg-5">
                            <form name="fotos_servico" novalidate autocomplete="off" method="POST" enctype="multipart/form-data" action="{{ route('app.servicos.store.fotos') }}" class="form-horizontal">
                                <div class="ml-auto mt-4 mb-4">
                                    <div class="section-title mr-auto">
                                        <h3>{{ $steps++ }}. Galeria de fotos</h3>
                                        <p class="mt-1">Adicione fotos referentes ao serviço.</p>
                                    </div>
                                </div>
                                <div class="row mb-0 list-imagens-servico">
                                    @foreach($servico->fotos as $foto_servico)
                                        <div class="col-xl-3 mb-4">
                                            <div class="d-inline-block position-relative w-100">
                                                @if($foto_servico->tipo == $e_principal)
                                                    <div class="foto-principal">
                                                        <p class="text-gradient-01">Foto destaque</p>
                                                    </div>
                                                @endif
                                                <a href="{{ $foto_servico->foto_large }}" title="{{ $foto_servico->legenda }}" data-lumos="servico" class="lumos-link w-100">
                                                    <img src="{{ asset('images/loader_place.svg') }}" alt="{{ $foto_servico->legenda }}" data-src="{{ $foto_servico->foto_large }}"
                                                         class="img-fluid border-3 shadow-sm lazyload">
                                                </a>
                                                <a href="{{ route('app.servicos.view.fotos', $foto_servico->id) }}"
                                                   data-action="edit-photo" class="action-foto"><i class="la la-edit"></i> Editar</a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="row">
                                    <div class="col-xl-12">
                                        <label for="fotos_servico" class="form-control-label">Fotos do serviço</label>
                                        <div class="input-group">
                                            <span class="input-group-btn">
                                                <button type="button" data-callback="#callback_fotos" class="btn btn-primary open-search-file">Selecionar fotos</button>
                                            </span>
                                            <input type="text" id="fotos_servico" name="placeholder" placeholder="Nenhum arquivo selecionado" required data-required
                                                   class="form-control open-search-file" title="Fotos do serviço" data-callback="#callback_fotos">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <p class="mt-4 mb-0">
                                            O tamanho ideal é {{ $preset_foto['width'] }}x{{ $preset_foto['height'] }} |
                                            Formatos aceitos: .PNG .JPG e .JPEG até 3mb.
                                        </p>
                                    </div>
                                </div>
                                <div class="hide">
                                    <input type="file" name="fotos[]" id="callback_fotos" multiple accept="image/png, image/jpeg, image/jpg">
                                    <input type="hidden" name="servico_id" value="{{ $servico->id }}">
                                </div>
                                <div class="em-separator separator-dashed"></div>
                                <div class="text-center">
                                    <button class="btn btn-gradient-01" type="submit">Enviar fotos <i class="la la-level-up right"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


                <div class="tab-pane" id="faq">
                    <div class="widget has-shadow">
                        <div class="widget-body pl-lg-5 pr-lg-5">
                            <div class="ml-auto mt-4 mb-4">
                                <div class="section-title mr-auto">
                                    <h3>{{ $steps++ }}. Faq do serviço</h3>
                                    <p class="mt-1">Cadastre as perguntas frequentes do serviço</p>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-divider mb-0">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Pergunta</th>
                                        <th>Resposta</th>
                                        <th>Administração</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($servico->faqServico as $key => $faq)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $faq->titulo }}</td>
                                            <td>{{ $faq->texto }}</td>
                                            <td style="display: flex;">
                                                <button class="btn btn-outline-primary editar-faq" faq_servico_id="{{ $faq->id }}" titulo="{{ $faq->titulo }}" texto="{{ $faq->texto }}">Editar</button>
                                                <form action="{{ Route('app.servicos.faq.delete') }}" method="post" data-validate-ajax>
                                                    @csrf
                                                    <input data-auto-capitalize type="hidden" value="{{ $faq->id }}" name="faq_servico_id"  class="form-horizontal">
                                                    <button class="btn btn-gradient-01" type="submit">Excluir</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="em-separator separator-dashed"></div>
                            <div class="text-center">
                                <button class="btn btn-gradient-01" data-target="#add-pergunta" data-toggle="modal">
                                    Nova pergunta <i class="la la-plus right"></i></button>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="tab-pane" id="regra-servico">
                </div>
            </div>
        </div>

        {{-- Modal para cadastrar categoria ao serviço --}}
        <div id="add-categoria" class="modal modal-top fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Adicionar categoria</h4>
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">×</span>
                            <span class="sr-only">close</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form data-validate-ajax method="POST" action="{{ route('app.servicos.store.categoria') }}" class="form-horizontal">
                            <div class="form-group row mb-0">
                                <div class="col-xl-12 mb-1 mt-0 mb-3">
                                    <label for="categoria-cadastro" class="form-control-label">Categorias disponíveis em</label>
                                    <select id="categoria-cadastro" name="categoria_id" class="form-control" required
                                            data-required title="Selecione uma categoria" data-route="{{ route('app.categorias.view.json') }}">
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-xl-12 mb-3">
                                    <label for="tipo_categoria_novo" class="form-control-label">Qual é o tipo da categoria neste serviço?</label>
                                    <select id="tipo_categoria_novo" name="padrao" class="form-control boostrap-select-custom" required
                                            data-required title="Selecione um tipo">
                                        @foreach($e_categoria_servico as $index_tipo => $tipo_categoria)
                                            @if($index_tipo != $e_categoria_padrao)
                                                <option value="{{ $index_tipo }}" selected>{{ $tipo_categoria }}</option>
                                            @else
                                                <option value="{{ $index_tipo }}">{{ $tipo_categoria }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-xl-12">
                                    <p class="text-dark">Selecione as seções que são ligadas ao serviço</p>
                                </div>
                                <div class="col-xl-12">
                                    <div class="list-secoes-categoria row pl-2">
                                        <div class="col-xl-12">
                                            <div class="alert alert-primary-bordered alert-lg square">
                                                <p class="m-0">Selecione uma categoria.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="hide">
                                <input type="hidden" name="servico_id" value="{{ $servico->id }}">
                            </div>
                            <div class="em-separator separator-dashed mt-2"></div>
                            <div class="text-center mt-3">
                                <button class="btn btn-success">Salvar categoria no serviço</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal editar a categoria ao serviço --}}
        <div id="edit-categoria" class="modal modal-top fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Editar categoria</h4>
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">×</span>
                            <span class="sr-only">close</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form data-validate-ajax method="POST" action="{{ route('app.servicos.update.categoria') }}" class="form-horizontal">
                            <div class="form-group row mb-0">
                                <div class="col-xl-12 mb-3">
                                    <label for="categoria_edit" class="form-control-label">Categoria do serviço</label>
                                    <input id="categoria_edit" type="text" class="form-control" placeholder="Nome da categoria"
                                           title="Categoria" readonly>
                                </div>
                                <div class="col-xl-12 mb-3">
                                    <label for="tipo_categoria" class="form-control-label">Qual é o tipo da categoria neste serviço?</label>
                                    <select id="tipo_categoria" name="padrao" class="form-control boostrap-select-custom" required
                                            data-required title="Selecione um tipo">
                                        @foreach($e_categoria_servico as $index_tipo => $tipo_categoria)
                                            <option value="{{ $index_tipo }}">{{ $tipo_categoria }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-xl-12">
                                    <p class="text-dark">Selecione as seções que são ligadas ao serviço</p>
                                </div>
                                <div class="col-xl-12">
                                    <div class="list-edit-secoes-categoria row pl-2"></div>
                                </div>
                            </div>
                            <div class="hide">
                                {{ method_field("PUT") }}
                                <input type="hidden" name="servico_id" value="{{ $servico->id }}">
                                <input type="hidden" name="categoria_id">
                                <input type="hidden" name="delete_category" value="off">
                            </div>
                            <div class="em-separator separator-dashed mt-2"></div>
                            <div class="mt-3">
                                <button type="button" data-action="delete_category" class="btn btn-danger left">Remover <i class="la la-trash right"></i></button>
                                <button class="btn btn-success pull-right">Atualizar <i class="la la-refresh right"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal para cadastrar campo adicional --}}
        <div id="add-field" class="modal modal-top fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Adicionar campo</h4>
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">×</span>
                            <span class="sr-only">close</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form data-validate-ajax method="POST" action="{{ route('app.servicos.store.form') }}" class="form-horizontal">
                            <div class="form-group row mb-0">
                                <div class="col-xl-12 mb-3">
                                    <label for="new_field_nome" class="form-control-label">Nome do campo</label>
                                    <input id="new_field_nome" type="text" class="form-control" placeholder="Ex. Número do voo, Hotel hospedado e etc." required
                                           data-required data-min="5" title="Nome do campo" data-auto-capitalize name="campo">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-xl-12 mb-3">
                                    <label for="new_field_placeholder" class="form-control-label">Placeholder <small>&nbsp; (Texto para auxiliar no preenchimento)</small></label>
                                    <input id="new_field_placeholder" type="text" class="form-control" placeholder="Texto que fica dentro do campo" required
                                           data-required data-min="2" title="Placeholder" name="placeholder">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-xl-12">
                                    <p class="text-dark">Campo obrigatório?</p>
                                </div>
                                @foreach($op_campos_adicionais as $key => $opcao)
                                    <div class="col-xl-2 mb-3 ml-2">
                                        <div class="styled-radio">
                                            <input type="radio" required name="obrigatorio" value="{{ $key }}" id="rad-field-{{ $key }}" title="Campo obrigatório">
                                            <label for="rad-field-{{ $key }}">{{ $opcao }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="hide">
                                <input type="hidden" name="servico_id" value="{{ $servico->id }}">
                            </div>
                            <div class="em-separator separator-dashed mt-1"></div>
                            <div class="text-center mt-3">
                                <button class="btn btn-success">Salvar campo adicional</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal para editar campo adicional --}}
        <div id="edit-field" class="modal modal-top fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Editar campo</h4>
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">×</span>
                            <span class="sr-only">close</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form data-validate-ajax method="POST" action="{{ route('app.servicos.update.form') }}" class="form-horizontal">
                            <div class="form-group row mb-0">
                                <div class="col-xl-12 mb-3">
                                    <label for="edit_field_nome" class="form-control-label">Nome do campo</label>
                                    <input id="edit_field_nome" type="text" class="form-control" placeholder="Ex. Número do voo, Hotel hospedado e etc." required
                                           data-required data-min="5" title="Nome do campo" data-auto-capitalize name="campo">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-xl-12 mb-3">
                                    <label for="edit_field_placeholder" class="form-control-label">Placeholder <small>&nbsp; (Texto para auxiliar no preenchimento)</small></label>
                                    <input id="edit_field_placeholder" type="text" class="form-control" placeholder="Texto que fica dentro do campo" required
                                           data-required data-min="2" title="Placeholder" name="placeholder">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-xl-12">
                                    <p class="text-dark">Campo obrigatório?</p>
                                </div>
                                @foreach($op_campos_adicionais as $key => $opcao)
                                    <div class="col-xl-2 mb-3 ml-2">
                                        <div class="styled-radio">
                                            <input type="radio" required name="obrigatorio" value="{{ $key }}" data-field="{{ $key }}" id="edit-rad-field-{{ $key }}" title="Campo obrigatório">
                                            <label for="edit-rad-field-{{ $key }}">{{ $opcao }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="hide">
                                {{ method_field("PUT") }}
                                <input type="hidden" name="campo_id">
                                <input type="hidden" name="delete_campo" value="off">
                            </div>
                            <div class="em-separator separator-dashed mt-1"></div>
                            <div class="mt-3">
                                <button type="button" data-action="delete" class="btn btn-danger left">Desativar <i class="la la-trash right"></i></button>
                                <button class="btn btn-success pull-right">Atualizar <i class="la la-refresh right"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal para editar a foto do serviço --}}
        <div id="edit-photo" class="modal modal-top fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Editar foto</h4>
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">×</span>
                            <span class="sr-only">close</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form data-validate-ajax method="POST" action="{{ route('app.servicos.update.fotos') }}" class="form-horizontal">
                            <div class="form-group row mb-0">
                                <div class="col-xl-12 mb-3">
                                    <img src="" alt="Foto serviço" id="foto_modal" class="w-100 border-3">
                                </div>
                                <div class="col-xl-12 mb-4">
                                    <label for="legenda_foto" class="form-control-label">Legenda</label>
                                    <input id="legenda_foto" type="text" class="form-control" placeholder="Legenda referente a foto" required
                                           data-required data-min="2" title="Legenda da foto" name="legenda" data-auto-capitalize>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-xl-12 mb-2">
                                    <div class="pl-1">
                                        <div class="styled-checkbox">
                                            <input type="checkbox" name="tipo" id="check-foto-principal" title="Foto destaque"
                                                   data-principal="{{ $e_principal }}" value="{{ $e_principal }}">
                                            <label for="check-foto-principal">Foto destaque</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="hide">
                                {{ method_field("PUT") }}
                                <input type="hidden" name="foto_id">
                                <input type="hidden" name="delete_foto" value="off">
                            </div>
                            <div class="em-separator separator-dashed mt-1"></div>
                            <div class="mt-3">
                                <button type="button" data-action="delete_photo" class="btn btn-danger left">Excluir <i class="la la-trash right"></i></button>
                                <button class="btn btn-success pull-right">Atualizar <i class="la la-refresh right"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal para cadastrar uma variação --}}
        <div id="add-variation" class="modal modal-top fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Nova variação</h4>
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">×</span>
                            <span class="sr-only">close</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form data-validate-ajax method="POST" action="{{ route('app.servicos.store.variacao') }}" class="form-horizontal">
                            <div class="form-group row mb-0">
                                <div class="col-xl-12 mb-3">
                                    <label for="new_variation_name" class="form-control-label">Nome da variação</label>
                                    <input id="new_variation_name" type="text" class="form-control" placeholder="Ex: Criança grátis, Adulto, Sênior e etc." required
                                           data-required data-min="5" title="Nome da variação" data-auto-capitalize name="nome">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-xl-12 mb-3">
                                    <label for="new_variation_description" class="form-control-label">Descrição <small>&nbsp; (Descritivo curto sobre a variação)</small></label>
                                    <input id="new_variation_description" type="text" class="form-control" placeholder="Ex: 0 até 100 anos" required
                                           data-required data-min="2" title="Descrição" name="descricao">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-xl-12 mb-3">
                                    <label for="new_tipo_variacao" class="form-control-label">Destaque da variação &nbsp;<small>(Será utilizado como valor a partir de)</small></label>
                                    <select id="new_tipo_variacao" name="destaque" class="form-control boostrap-select-custom" required
                                            data-required title="Selecione uma opção">
                                        @foreach($tipos_variacao as $key => $tipo_variacao)
                                            <option value="{{ $key }}" data-tipo-variacao="{{ $key }}">{{ $tipo_variacao }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-xl-12 mb-3">
                                    <label for="new_net_base" class="form-control-label">Tarifa base do serviço &nbsp;<small>(Custo adulto ou o maior valor de custo do serviço)</small></label>
                                    <div class="input-group">
                                        <span class="input-group-addon addon-secondary left">R$</span>
                                        <input id="new_net_base" type="tel" class="form-control vanillaMaskMoney" placeholder="0,00" required
                                               data-required data-min="4" title="Valor net do serviço" name="net_servico">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-xl-6 mb-3">
                                    <label for="new_valor_net_variation" class="form-control-label">Valor net da VARIAÇÃO</label>
                                    <div class="input-group">
                                        <span class="input-group-addon addon-secondary left">R$</span>
                                        <input id="new_valor_net_variation" type="tel" class="form-control vanillaMaskMoney" placeholder="0,00" required
                                               data-required data-min="4" title="Valor net da variação" name="net_variacao">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-xl-6 mb-3">
                                    <label for="new_valor_venda_variation" class="form-control-label">Valor de venda da VARIAÇÃO</label>
                                    <div class="input-group">
                                        <span class="input-group-addon addon-secondary left">R$</span>
                                        <input id="new_valor_venda_variation" type="tel" class="form-control vanillaMaskMoney" placeholder="0,00" required
                                               data-required data-min="4" title="Valor de venda da variação" name="venda_variacao">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-xl-6 mb-2">
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <p class="text-dark">Consome bloqueio?</p>
                                        </div>
                                        <div class="col-xl-5 mt-1 pl-lg-4">
                                            <div class="styled-radio">
                                                <input type="radio" required name="consome_bloqueio" value="{{ $consome_bloqueio }}"
                                                       id="new_variation_block_{{ $consome_bloqueio }}" title="Consome bloqueio">
                                                <label for="new_variation_block_{{ $consome_bloqueio }}">Sim</label>
                                            </div>
                                        </div>
                                        <div class="col-xl-5 mt-1 pl-lg-4">
                                            <div class="styled-radio">
                                                <input type="radio" required name="consome_bloqueio" value="{{ $nao_consome_bloqueio }}"
                                                       id="new_variation_block_{{ $nao_consome_bloqueio }}" title="Consome bloqueio">
                                                <label for="new_variation_block_{{ $nao_consome_bloqueio }}">Não</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-12 mb-3">
                                    <label for="new_min_pax" class="form-control-label">Quantidade mínima de Pax's</label>
                                    <input id="new_min_pax" type="number" class="form-control" placeholder="Insira a quantidade mínima de pessoas" 
                                           required data-required data-min="1" title="Quantidade mínima de Pax's" name="min_pax">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="hide">
                                <input type="hidden" name="servico_id" value="{{ $servico->id }}">
                            </div>
                            <div class="em-separator separator-dashed mt-2"></div>
                            <div class="text-center mt-2 mb-2">
                                <button class="btn btn-success">Salvar variação</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>






        

        {{-- Modal para editar uma variação --}}
        <div id="edit-variation" class="modal modal-top fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Editar variação</h4>
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">×</span>
                            <span class="sr-only">close</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form data-validate-ajax method="POST" action="{{ route('app.servicos.update.variacao') }}" class="form-horizontal">
                            <div class="form-group row mb-0">
                                <div class="col-xl-12 mb-3">
                                    <label for="edit_variation_name" class="form-control-label">Nome da variação</label>
                                    <input id="edit_variation_name" type="text" class="form-control" placeholder="Ex: Criança grátis, Adulto, Sênior e etc." required
                                           data-required data-min="5" title="Nome da variação" data-auto-capitalize name="nome">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-xl-12 mb-3">
                                    <label for="edit_variation_description" class="form-control-label">Descrição <small>&nbsp; (Descritivo curto sobre a variação)</small></label>
                                    <input id="edit_variation_description" type="text" class="form-control" placeholder="Ex: 0 até 100 anos" required
                                           data-required data-min="2" title="Descrição" name="descricao">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-xl-12 mb-3">
                                    <label for="edit_tipo_variacao" class="form-control-label">Destaque da variação &nbsp;<small>(Será utilizado como valor a partir de)</small></label>
                                    <select id="edit_tipo_variacao" name="destaque" class="form-control boostrap-select-custom" required
                                            data-required title="Selecione uma opção">
                                        @foreach($tipos_variacao as $key => $tipo_variacao)
                                            <option value="{{ $key }}" data-tipo-variacao="{{ $key }}">{{ $tipo_variacao }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-12 toggleComissao hide">
                                    <div class="row mb-0">
                                        <div class="col-xl-12 mb-3">
                                            <label for="edit_net_base" class="form-control-label">Tarifa base do serviço &nbsp;<small>(Net adulto ou o maior valor net)</small></label>
                                            <div class="input-group">
                                                <span class="input-group-addon addon-secondary left">R$</span>
                                                <input id="edit_net_base" type="tel" class="form-control vanillaMaskMoney" placeholder="0,00" disabled
                                                       data-min="4" title="Valor net do serviço" name="net_servico">
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 mb-3">
                                            <label for="edit_valor_net_variation" class="form-control-label">Valor net da VARIAÇÃO</label>
                                            <div class="input-group">
                                                <span class="input-group-addon addon-secondary left">R$</span>
                                                <input id="edit_valor_net_variation" type="tel" class="form-control vanillaMaskMoney" placeholder="0,00" disabled
                                                       data-min="4" title="Valor net da variação" name="net_variacao">
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 mb-3">
                                            <label for="edit_valor_venda_variation" class="form-control-label">Valor de venda da VARIAÇÃO</label>
                                            <div class="input-group">
                                                <span class="input-group-addon addon-secondary left">R$</span>
                                                <input id="edit_valor_venda_variation" type="tel" class="form-control vanillaMaskMoney" placeholder="0,00" disabled
                                                       data-min="4" title="Valor de venda da variação" name="venda_variacao">
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6 mb-1">
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <p class="text-dark">Consome bloqueio?</p>
                                        </div>
                                        <div class="col-xl-5 mt-1 pl-lg-4">
                                            <div class="styled-radio">
                                                <input type="radio" required name="consome_bloqueio" value="{{ $consome_bloqueio }}" data-bloqueio="{{ $consome_bloqueio }}"
                                                       id="edit_variation_block_{{ $consome_bloqueio }}" title="Consome bloqueio">
                                                <label for="edit_variation_block_{{ $consome_bloqueio }}">Sim</label>
                                            </div>
                                        </div>
                                        <div class="col-xl-5 mt-1">
                                            <div class="styled-radio">
                                                <input type="radio" required name="consome_bloqueio" value="{{ $nao_consome_bloqueio }}" data-bloqueio="{{ $nao_consome_bloqueio }}"
                                                       id="edit_variation_block_{{ $nao_consome_bloqueio }}" title="Consome bloqueio">
                                                <label for="edit_variation_block_{{ $nao_consome_bloqueio }}">Não</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-12 mb-3">
                                    <label for="new_min_pax" class="form-control-label">Quantidade mínima de Pax's</label>
                                    <input id="new_min_pax" type="number" class="form-control" placeholder="Insira a quantidade mínima de pessoas" 
                                           required data-required data-min="1" title="Quantidade mínima de Pax's" name="min_pax">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-xl-6 mb-1 d-flex align-items-center">
                                    <div class="d-flex align-items-center m-auto">
                                        <a href="#" data-action="toggleEditComissao" class="btn btn-outline-secondary mt-2">
                                            Editar comissão <i class="la la-balance-scale right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="hide">
                                {{ method_field("PUT") }}
                                <input type="hidden" name="variacao_id">
                                <input type="hidden" name="delete_variacao" value="off">
                                <input type="hidden" name="edit_comissao" value="off">
                                <input type="hidden" name="servico" value="{{ $servico->id }}">
                            </div>
                            <div class="em-separator separator-dashed mt-2"></div>
                            <div class="mt-2 mb-2">
                                <button type="button" data-action="delete_variacao" class="btn btn-danger left">Desativar <i class="la la-trash right"></i></button>
                                <button class="btn btn-success pull-right">Atualizar <i class="la la-refresh right"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

         {{-- Modal para cadastrar uma Pergunta --}}
         <div id="add-pergunta" class="modal modal-top fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Nova pergunta</h4>
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">×</span>
                            <span class="sr-only">close</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form data-validate-ajax method="POST" action="{{ Route('app.servicos.faq.store') }}" class="form-horizontal">
                            <div class="form-group row mb-0">
                                <div class="col-xl-12 mb-3">
                                    <label for="new_variation_name" class="form-control-label">Pergunta</label>
                                    <input id="nova_pergunta_pergunta" name="nova_pergunta_pergunta" type="text" class="form-control" placeholder="Ex: Qual o horário de saida do serviço" required
                                           data-required data-min="5" title="pergunta" name="nome">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-xl-12 mb-3">
                                    <label for="new_variation_description" class="form-control-label">Resposta</label>
                                    <textarea class="form-control" name="nova_pergunta_resposta" id="" cols="30" rows="5" placeholder="Ex: O horário de saída é as 10:00 horas" required></textarea>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="hide">
                                <input type="hidden" name="servico_id" value="{{ $servico->id }}">
                            </div>
                            <div class="em-separator separator-dashed mt-2"></div>
                            <div class="text-center mt-2 mb-2">
                                <input class="btn btn-success" value="Salvar perguta" type="submit">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal para cadastrar uma Pergunta --}}
        <div id="edit-pergunta" class="modal modal-top fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Editar pergunta</h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">×</span>
                        <span class="sr-only">close</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form data-validate-ajax method="POST" action="{{ Route('app.servicos.faq.update') }}" class="form-horizontal">
                        <div class="form-group row mb-0">
                            <div class="col-xl-12 mb-3">
                                <label for="new_variation_name" class="form-control-label">Pergunta</label>
                                <input id="edit_pergunta_pergunta" name="edit_pergunta_pergunta" type="text" class="form-control" placeholder="Ex: Qual o horário de saida do serviço" required
                                        data-required data-min="5" title="pergunta" name="nome">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-xl-12 mb-3">
                                <label for="new_variation_description" class="form-control-label">Resposta</label>
                                <textarea class="form-control" name="edit_pergunta_resposta" id="edit_pergunta_resposta" cols="30" rows="5" placeholder="Ex: O horário de saída é as 10:00 horas" required></textarea>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="hide">
                            <input type="hidden" id="faq_servico_id" name="faq_servico_id" value="">
                        </div>
                        <div class="em-separator separator-dashed mt-2"></div>
                        <div class="text-center mt-2 mb-2">
                            <input class="btn btn-success" value="Salvar perguta" type="submit">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

 
        {{-- Modal para editar o markup do servico --}}
        <div id="edit-markup" class="modal modal-top fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Editar markup</h4>
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">×</span>
                            <span class="sr-only">close</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form data-validate-ajax method="POST" action="{{ route('app.servicos.update.markup') }}" class="form-horizontal">
                            <div class="form-group row mb-0">
                                <div class="col-xl-12 mb-3">
                                    <label for="markup_atual" class="form-control-label">Markup atual</label>
                                    <input id="markup_atual" type="text" class="form-control" title="Markup atual" readonly>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-xl-12 mb-3">
                                    <label for="new_markup" class="form-control-label">Novo markup</label>
                                    <input id="new_markup" type="tel" class="form-control vanillaMask" placeholder="0.00000" required
                                           data-required data-min="7" title="Novo markup" data-mask="markup" name="markup">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="hide">
                                {{ method_field("PUT") }}
                                <input type="hidden" name="variacao_id">
                            </div>
                            <div class="em-separator separator-dashed mt-2"></div>
                            <div class="mt-2 mb-2 text-center">
                                <button class="btn btn-success">Atualizar markup <i class="la la-refresh right"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal para adicionar uma tag no servico --}}
        <div id="new-tag" class="modal modal-top fade">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Nova tag serviço</h4>
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">×</span>
                            <span class="sr-only">close</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="form-tag" data-validate-ajax method="POST" action="{{ route('app.servicos.tags.store') }}" class="form-horizontal">
                            <div class="form-group row mb-0">
                                <div class="col-lg-3" id="campo-titulo">
                                    <label for="ordem_tag_new" class="form-control-label">Título</label>
                                    <input id="titulo_tag_new" type="text" class="form-control" placeholder="Ex: Horário, Item incluso, Brinde e etc." required
                                           data-required min="3" title="Titulo" name="titulo">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-lg-4">
                                    <label for="descricao_tag_new" class="form-control-label">Descrição</label>
                                    <input id="descricao_tag_new" type="text" class="form-control" placeholder="Ex: Horário, Item incluso, Brinde e etc." required
                                           data-required data-min="3" title="Descrição tag" name="descricao">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-lg-2">
                                    <label for="ordem_tag_new" class="form-control-label">Ordem</label>
                                    <input id="ordem_tag_new" type="number" class="form-control" placeholder="1 - 99" required
                                           data-required min="1" max="99" maxlength="2" title="Ordem da tag tag" name="ordem">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-lg-3">
                                    <label for="search_icon" class="form-control-label">Pesquisar icone</label>
                                    <div class="input-group">
                                        <span class="input-group-addon addon-secondary left"><i class="la la-search"></i></span>
                                        <input id="search_icon" data-action="search" data-target="#list-new-tag" type="text"
                                               class="form-control" placeholder="Digite aqui para filtrar" title="Pesquisar icone">
                                    </div>
                                </div>
                            </div>
                            <div class="em-separator separator-dashed mb-1"></div>
                            <div id="list-new-tag" class="row list-icones-tag pb-3"></div>
                            <div class="hide">
                                <input type="hidden" name="servico_id" value="{{ $servico->id }}">
                                <input type="hidden" name="icone">
                            </div>
                            <div class="em-separator separator-dashed mt-2"></div>
                            <div class="mt-2 mb-2 text-center">
                                <button class="btn btn-success">Cadastrar tag <i class="la la-save right"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal para editar uma tag no servico --}}
        <div id="edit-tag" class="modal modal-top fade">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Editar tag serviço</h4>
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">×</span>
                            <span class="sr-only">close</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="form-tag2" data-validate-ajax method="POST" action="{{ route('app.servicos.tags.update') }}" class="form-horizontal">
                            <div class="form-group row mb-0">
                                <div class="col-lg-3" id="campo-titulo2">
                                    <label for="ordem_tag_new" class="form-control-label">Título</label>
                                    <input id="titulo_tag_new2" type="text" class="form-control" placeholder="Ex: Horário, Item incluso, Brinde e etc." required
                                           data-required min="3" max="25" maxlength="25" title="Titulo" name="titulo">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-lg-4">
                                    <label for="descricao_tag_edit" class="form-control-label">Descrição</label>
                                    <input id="descricao_tag_edit" type="text" class="form-control" placeholder="Ex: Horário, Item incluso, Brinde e etc." required
                                           data-required data-min="3" title="Descrição tag" name="descricao">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-lg-2">
                                    <label for="ordem_tag_edit" class="form-control-label">Ordem</label>
                                    <input id="ordem_tag_edit" type="number" class="form-control" placeholder="1 - 99" required
                                           data-required min="1" max="99" maxlength="2" title="Ordem da tag tag" name="ordem">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-lg-3">
                                    <label for="search_icon_edit" class="form-control-label">Pesquisar icone</label>
                                    <div class="input-group">
                                        <span class="input-group-addon addon-secondary left"><i class="la la-search"></i></span>
                                        <input id="search_icon_edit" data-action="search" data-target="#list-new-edit" type="text"
                                               class="form-control" placeholder="Digite aqui para filtrar" title="Pesquisar icone">
                                    </div>
                                </div>
                            </div>
                            <div class="em-separator separator-dashed mb-1"></div>
                            <div id="list-new-edit" class="row list-icones-tag pb-3"></div>
                            <div class="hide">
                                {{ method_field("PUT") }}
                                <input type="hidden" name="icone">
                                <input type="hidden" name="tag_id">
                                <input type="hidden" name="delete_tag" value="off">
                            </div>
                            <div class="em-separator separator-dashed mt-2"></div>
                            <div class="mt-2 mb-2">
                                <button type="button" data-action="delete-tag" class="btn btn-danger pull-left">Excluir <i class="la la-trash right"></i></button>
                                <button class="btn btn-success pull-right">Atualizar <i class="la la-refresh right"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div style="display: none;">
        <span id="link-cadastrar2">{{ Route('app.servicos.tags.store2') }}</span>
        <span id="link-atualizar2">{{ Route('app.servicos.tags.update2') }}</span>
        <span id="link-cadastrar">{{ Route('app.servicos.tags.store') }}</span>
        <span id="link-atualizar">{{ Route('app.servicos.tags.update') }}</span>
        <span id="link-icones2">{{ Route('app.servicos.tags.icones-2') }}</span>
    </div>

    <script>

        let onClickEditar  = () => {
            
            $(".editar-faq").on('click', (event) => {

                let pergunta_atual = $(event.target).attr('titulo');
                let resposta_atual = $(event.target).attr('texto');
                let faq_id = $(event.target).attr('faq_servico_id');


                let modal = $("#edit-pergunta");
                let pergunta_modal = $("#edit_pergunta_pergunta");
                let resposta_modal = $("#edit_pergunta_resposta");
                let faq_servico_id_modal = $("#faq_servico_id");
    
                
                pergunta_modal.val(pergunta_atual);
                resposta_modal.val(resposta_atual);
                faq_servico_id_modal.val(faq_id);
                modal.modal();
            })

        }

        window.onload = () => {

            onClickEditar();

        }

    </script>
@endsection