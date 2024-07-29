@extends('template.header')

@section('title', 'Novo Serviço')

@section('content')

    @php($steps = 1)

    <div class="row">
        <div class="page-header">
            <div class="d-flex align-items-center">
                <h2 class="page-header-title text-dark">Novo serviço <span class="text-gradient-01">| {{ $canal_venda->nome }}</span></h2>
                <div>{{ Breadcrumbs::render('app.servicos.create') }}</div>
            </div>
        </div>
    </div>

    <div class="row" data-controller="ServicoCtrl">
        <div class="col-12">
            <ul id="tab_servico" class="nav nav-tabs nav-tab-header nav-tab-no-border">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#descricao">
                        <i class="la la-certificate la-2x align-middle mr-2"></i> Descrição
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" data-toggle="tab" href="#categoria">
                        <i class="la la-filter la-2x align-middle mr-2"></i> Categorias
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" data-toggle="tab" href="#regras">
                        <i class="la la-quote-left la-2x align-middle mr-2"></i> Regras e Voucher
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" data-toggle="tab" href="#variacoes">
                        <i class="la la-users la-2x align-middle mr-2"></i> Variações
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" data-toggle="tab" href="#form">
                        <i class="la la-keyboard-o la-2x align-middle mr-2"></i> Formulário
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" data-toggle="tab" href="#galeria">
                        <i class="la la-image la-2x align-middle mr-2"></i> Galeria de fotos
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane active" id="descricao">
                    <div class="widget has-shadow">
                        <div class="widget-body pl-lg-5 pr-lg-5">
                            <form name="descricao" method="POST" action="{{ route('app.servicos.store.descricao') }}" class="form-horizontal">
                                <div class="ml-auto mt-4 mb-4">
                                    <div class="section-title mr-auto">
                                        <h3>0{{ $steps++ }}. Informações do serviço</h3>
                                        <p class="mt-1">Informações detalhadas sobre o serviço.</p>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xl-8 mb-3">
                                        <label for="nome" class="form-control-label">Nome do serviço</label>
                                        <input id="nome" type="text" class="form-control" placeholder="Ex. Maria Fumaça, Snowland, Sequencia de Fondue" required
                                               data-required data-min="5" title="Nome do serviço" data-auto-capitalize name="nome">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-xl-4 mb-3">
                                        <label for="valor_venda" class="form-control-label">Valor venda <small>&nbsp;(Menor valor da agenda)</small></label>
                                        <div class="input-group">
                                            <span class="input-group-addon addon-secondary left">R$</span>
                                            <input id="valor_venda" type="tel" class="form-control vanillaMaskMoney" placeholder="0,00" required
                                                   data-required data-min="4" title="Valor de venda" name="valor_venda">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xl-4 mb-3">
                                        <label for="fornecedor_id" class="form-control-label">Fornecedor</label>
                                        <select id="fornecedor_id" name="fornecedor_id" class="form-control boostrap-select-custom" required
                                                data-required title="Selecione um fornecedor">
                                            @foreach($fornecedores as $fornecedor)
                                                <option value="{{ $fornecedor->id }}">{{ $fornecedor->nome_fantasia }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-xl-4 mb-3">
                                        <label for="destino_id" class="form-control-label">Destino</label>
                                        <select id="destino_id" name="destino_id" class="form-control boostrap-select-custom" required
                                                data-required title="Selecione um destino">
                                            @foreach($destinos as $destino)
                                                <option value="{{ $destino->id }}">{{ $destino->nome }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-xl-4 mb-3">
                                        <label for="canal_venda_id" class="form-control-label">Canal de venda</label>
                                        <input id="canal_venda_id" type="text" class="form-control" placeholder="Canal de venda"
                                               readonly title="Canal de venda" value="{{ $canal_venda->site }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xl-4 mb-3">
                                        <label for="info_clientes" class="form-control-label">Requer identificação? <small>&nbsp;(Por cliente)</small></label>
                                        <select id="info_clientes" name="info_clientes" class="form-control boostrap-select-custom" required
                                                data-required title="Selecione uma opção">
                                            @foreach($info_clientes as $key => $info_cliente)
                                                <option value="{{ $key }}">{{ $info_cliente }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-xl-4 mb-3">
                                        <label for="comissao_afiliado" class="form-control-label">Comissão do afiliado &nbsp;<small>(Em porcentagem)</small></label>
                                        <div class="input-group">
                                            <span class="input-group-addon addon-secondary left"><i class="la la-balance-scale"></i></span>
                                            <input id="comissao_afiliado" type="tel" class="form-control vanillaPorcentage" placeholder="0.00%"
                                                   title="Comissão do afiliado" name="comissao_afiliado" required data-required data-min="4" maxlength="6">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 mb-3">
                                        <label for="status_servico" class="form-control-label">Status do serviço &nbsp;<small>(Disponibilidade no site)</small></label>
                                        <div class="input-group">
                                            <span class="input-group-addon addon-secondary left"><i class="la la-thumb-tack"></i></span>
                                            <input id="status_servico" type="text" class="form-control" readonly value="{{ $status_pendente }}">
                                        </div>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xl-8 mb-3">
                                        <label for="palavras_chaves" class="form-control-label">Palavras chaves <small>&nbsp;(Separadas por virgula)</small></label>
                                        <div class="input-group">
                                            <span class="input-group-addon addon-secondary left"><i class="la la-search"></i></span>
                                            <input id="palavras_chaves" type="text" class="form-control" placeholder="Palavras que facilitam para encontrar o serviço"
                                                   title="Palavras chaves" name="palavras_chaves" required data-required data-min="5">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 mb-3">
                                        <label for="integracao" class="form-control-label">Possui integração? <small>&nbsp;(Usa voucher do parque)</small></label>
                                        <div class="input-group">
                                            <span class="input-group-addon addon-secondary left"><i class="la la-puzzle-piece"></i></span>
                                            <select id="integracao" name="integracao" class="form-control boostrap-select-custom" required
                                                    data-required title="Selecione uma opção">
                                                @foreach($integracoes as $key => $integracao)
                                                    @if($loop->first)
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
                                    <div class="col-xl-4 mb-3">
                                        <label for="antecedencia_venda" class="form-control-label">Antecedência de venda <small>&nbsp;(Deadline)</small></label>
                                        <select id="antecedencia_venda" name="antecedencia_venda" class="form-control" required
                                                data-required title="Selecione uma opção">
                                            <option value="" selected disabled>Selecione uma opção</option>
                                            @for($antecedencia = 0; $antecedencia <= 10; $antecedencia++)
                                                @if($antecedencia === 0)
                                                    <option value="{{ $antecedencia }}">Nenhuma</option>
                                                @else
                                                    <option value="{{ $antecedencia }}">{{ $antecedencia }} Dia(s)</option>
                                                @endif
                                            @endfor
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-xl-4 mb-3">
                                        <label for="tipo_corretagem" class="form-control-label">Tipo de corretagem <small>&nbsp;(R$ ou %)</small></label>
                                        <select id="tipo_corretagem" name="tipo_corretagem" class="form-control" required
                                                data-required title="Selecione uma opção">
                                            @foreach($corretagens as $key => $corretagem)
                                                <option value="{{ $key }}">{{ $corretagem }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-xl-4 mb-3 corretagem-percentual">
                                        <label for="corretagem_porcent" class="form-control-label">Porcentagem da corregatem</label>
                                        <div class="input-group">
                                            <span class="input-group-addon addon-secondary left">%</span>
                                            <input id="corretagem_porcent" type="tel" class="form-control vanillaMask" placeholder="0.0"
                                                   title="Porcentagem da corregatem" name="corretagem" data-mask="porcent" maxlength="3" disabled>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 mb-3 hide corretagem-valor-fixo">
                                        <label for="corretagem" class="form-control-label">Valor da corregatem</label>
                                        <div class="input-group">
                                            <span class="input-group-addon addon-secondary left">R$</span>
                                            <input id="corretagem" type="tel" class="form-control vanillaMaskMoney" placeholder="0,00"
                                                   title="Valor da corregatem" name="corretagem" disabled>
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
                                                   title="Cidade" data-required data-min="5" required data-auto-capitalize>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-xl-8 mb-3">
                                        <label for="localizacao" class="form-control-label">Endereço <small>&nbsp; (Não é obrigatório)</small></label>
                                        <div class="input-group">
                                            <span class="input-group-addon addon-secondary left"><i class="la la-map-o"></i></span>
                                            <input id="localizacao" name="localizacao" type="text" class="form-control" placeholder="Endereço completo para o mapa (Google Maps)"
                                                   title="Endereço">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xl-12 mb-3">
                                        <label for="horario" class="form-control-label">Horário de atendimento <small>&nbsp;(Abertura e fechamento)</small></label>
                                        <div class="input-group">
                                            <span class="input-group-addon addon-secondary left"><i class="la la-clock-o"></i></span>
                                            <input id="horario" type="text" class="form-control" placeholder="A partir das 08:00h até às 16:00h"
                                                   title="Horário de atendimento" name="horario" required data-required data-min="5" maxlength="100">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
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
                                                  placeholder="O título da página HTML" data-required data-min="5"></textarea>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-xl-6 mb-3">
                                        <label for="descricao_curta" class="form-control-label">Descrição curta <small>&nbsp;(Sem marcação markdown até 70 caracteres)</small></label>
                                        <textarea id="descricao_curta" name="descricao_curta" class="form-control not-resize" rows="5" title="Descrição curta"
                                                  placeholder="Descreva o serviço em até 70 caracteres" data-required data-min="5"></textarea>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xl-6 mb-3">
                                        <label for="titulo_servico" class="form-control-label">Título do serviço <small>&nbsp;(Opcional)</small></label>
                                        <div class="input-group">
                                            <span class="input-group-addon addon-secondary left"><i class="la la-clock-o"></i></span>
                                            <input id="titulo_servico" type="text" class="form-control" placeholder="Título do serviço na sessão de informações"
                                                   title="Título do serviço" name="titulo_servico" data-required data-min="5" maxlength="100">
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
                                                  placeholder="Descreva os detalhes do serviço" data-required data-min="5" required></textarea>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="hide">
                                    <input type="hidden" name="canal_venda_id" value="{{ $canal_venda->id }}">
                                </div>
                                <div class="em-separator separator-dashed"></div>
                                <div class="text-center">
                                    <button class="btn btn-gradient-01" type="submit">Próximo passo <i class="la la-angle-right right"></i></button>
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
                                <div class="col-xl-12">
                                    <div class="alert alert-secondary alert-lg square mb-0">
                                        <i class="la la-rocket mr-2"></i>
                                        <strong>Adicione</strong> uma categoria para continuar!
                                    </div>
                                </div>
                            </div>
                            <div class="em-separator separator-dashed"></div>
                            <div class="text-center mt-3">
                                <button class="btn btn-gradient-01" data-action="add-categoria"
                                        data-target="#add-categoria" data-route="{{ route('app.destinos.view.json', 1) }}">
                                    Adicionar categoria <i class="la la-plus right"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="regras">
                    <div class="widget has-shadow">
                        <div class="widget-body pl-lg-5 pr-lg-5">
                            <form name="regras_servico_voucher" method="POST" action="{{ route('app.servicos.store.regras') }}" class="form-horizontal">
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
                                                  placeholder="Descreva as regras do serviço"></textarea>
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
                                                  placeholder="Liste as observações para serem adicionadas no voucher"></textarea>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="hide">
                                    <input type="hidden" name="servico_id" class="callback_servico_id">
                                </div>
                                <div class="em-separator separator-dashed"></div>
                                <div class="text-center">
                                    <button class="btn btn-gradient-01" type="submit">Próximo passo <i class="la la-angle-right right"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="variacoes">
                    <div class="widget has-shadow">
                        <div class="widget-body pl-lg-5 pr-lg-5">
                            <div class="ml-auto mt-4 mb-4">
                                <div class="section-title mr-auto">
                                    <h3>0{{ $steps++ }}. Variações do serviço</h3>
                                    <p class="mt-1">Cadastre as variações para o serviço.</p>
                                </div>
                            </div>
                            <div class="em-separator separator-dashed"></div>
                            <div class="row mt-3">
                                <div class="col-xl-12">
                                    <div class="alert alert-secondary alert-lg square mb-0">
                                        <i class="la la-rocket mr-2"></i>
                                        <strong>Cadastre</strong> uma nova variação para continuar!
                                    </div>
                                </div>
                            </div>
                            <div class="em-separator separator-dashed"></div>
                            <div class="text-center">
                                <button class="btn btn-gradient-01" data-target="#add-variation" data-toggle="modal">
                                    Nova variação <i class="la la-plus right"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
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
                                <table class="table mb-0">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nome do campo</th>
                                        <th>Placeholder</th>
                                        <th>Obrigatório</th>
                                        <th>Status</th>
                                        <th>Administração</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td colspan="6" class="text-center pt-4 pb-2">Sem campos adicionais</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="em-separator separator-dashed"></div>
                            <div class="text-center">
                                <button class="btn btn-gradient-02" data-target="#add-field" data-toggle="modal">
                                    Adicionar campo <i class="la la-plus right"></i></button>
                                <span class="mr-3 ml-3">OU</span>
                                <button class="btn btn-gradient-01" data-action="nextStep">Próximo passo <i class="la la-angle-right right"></i></button>
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
                                <div class="row mb-0 list-imagens-servico"></div>
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="alert alert-secondary alert-lg square mb-0">
                                            <i class="la la-cloud-upload mr-2"></i>
                                            Faça <strong>upload</strong> de fotos do serviço para continuar!
                                        </div>
                                    </div>
                                    <div class="col-xl-12 mt-3">
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
                                    <input type="hidden" name="servico_id" class="callback_servico_id">
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
                        <form name="new_secao_categoria" method="POST" action="{{ route('app.servicos.store.categoria') }}" class="form-horizontal">
                            <div class="form-group row mb-0">
                                <div class="col-xl-12 mb-1 mt-0 mb-3">
                                    <label for="categoria-cadastro" class="form-control-label">Categorias disponíveis em</label>
                                    <select id="categoria-cadastro" name="categoria_id" class="form-control" required
                                            data-required title="Selecione uma categoria" data-route="{{ route('app.categorias.view.json') }}">
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-xl-12 mb-3">
                                    <label for="tipo_categoria" class="form-control-label">Qual é o tipo da categoria neste serviço?</label>
                                    <input id="tipo_categoria" type="text" class="form-control" readonly disabled
                                           title="tipo da categoria neste serviço" value="{{ $e_tipo_categoria }}">
                                    <input type="hidden" name="padrao" value="{{ $e_categoria_padrao }}">
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
                                <input type="hidden" name="servico_id" class="callback_servico_id">
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
                        <form name="new_field" method="POST" action="{{ route('app.servicos.store.form') }}" class="form-horizontal">
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
                                <input type="hidden" name="servico_id" class="callback_servico_id">
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
                        <form name="new_variation_service" method="POST" action="{{ route('app.servicos.store.variacao') }}" class="form-horizontal">
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
                                    <label for="new_net_base" class="form-control-label">Tarifa base do serviço &nbsp;<small>(Net adulto ou o maior valor net)</small></label>
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
                                <div class="col-xl-6 mb-1">
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
                            </div>
                            <div class="hide">
                                <input type="hidden" name="servico_id" class="callback_servico_id">
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
    </div>

@endsection
