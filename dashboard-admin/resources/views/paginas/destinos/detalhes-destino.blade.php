@extends('template.header')

@section('title', $destino->nome . " em " . $destino->canalVenda->nome)

@section('content')

    <div class="row">
        <div class="page-header">
            <div class="d-flex align-items-center">
                <h2 class="page-header-title text-dark">{{ $destino->nome }} <span class="text-gradient-01">| {{ $destino->canalVenda->nome }}</span></h2>
                <div>{{ Breadcrumbs::render('app.destinos.view') }}</div>
            </div>
        </div>
    </div>

    <div class="row" data-controller="DestinoCtrl">
        <div class="col-12">
            <ul id="tab_destino" class="nav nav-tabs nav-tab-header nav-tab-no-border tabs-mobile">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#descricao">
                        <i class="la la-map la-2x align-middle mr-2"></i> Descrição do destino
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#destaque">
                        <i class="la la-image la-2x align-middle mr-2"></i> Foto de destaque
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#home">
                        <i class="la la-list la-2x align-middle mr-2"></i> Serviços na home
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane active" id="descricao">
                    <div class="widget has-shadow">
                        <div class="widget-body pl-lg-5 pr-lg-5">
                            <form data-validate-ajax method="POST" action="{{ route('app.destinos.update') }}" class="form-horizontal">
                                <div class="ml-auto mt-4 mb-4">
                                    <div class="section-title mr-auto">
                                        <h3>01. Descrição do destino</h3>
                                        <p class="mt-1">Informações detalhadas sobre o destino</p>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xl-8 mb-3">
                                        <label for="nome" class="form-control-label">Nome do destino</label>
                                        <input id="nome" type="text" class="form-control" placeholder="Ex. Serra Gaúcha, Foz do Iguaçu, Fortaleza" required
                                               data-required data-min="5" title="Nome do destino" data-auto-capitalize name="nome" value="{{ $destino->nome }}">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-xl-4 mb-3">
                                        <label for="canal_venda_id" class="form-control-label">Canal de venda <small>(Não é possível alterar)</small></label>
                                        <input id="canal_venda_id" type="text" class="form-control" placeholder="Canal de venda"
                                               readonly title="Canal de venda" value="{{ $destino->canalVenda->site }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xl-12 mb-0">
                                        <label for="descricao_completa" class="form-control-label">Descrição longa <small>&nbsp;(Com marcação markdown)</small></label>
                                        <textarea id="descricao_completa" name="descricao_completa" class="form-control not-resize simple-editor" title="Descrição completa"
                                                  placeholder="Descreva o destino em até 250 caracteres" data-required data-min="5" required>{{ $destino->descricao_completa }}</textarea>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="row d-flex align-items-center">
                                    <div class="col-lg-9 mt-4 mb-4">
                                        <div class="section-title mr-auto">
                                            <h3>02. Ferramentas de SEO</h3>
                                            <p class="mt-1">Título da página e descrição da categoria para SEO</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xl-6 mb-3">
                                        <label for="titulo_pagina" class="form-control-label">Título da página <small>(Opcional)</small></label>
                                        <textarea id="titulo_pagina" name="titulo_pagina" class="form-control not-resize" rows="5" title="Título da página"
                                                  placeholder="O título da página HTML" data-required data-min="5">{{ $destino->titulo_pagina }}</textarea>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-xl-6 mb-3">
                                        <label for="descricao_curta" class="form-control-label">Descrição <small>&nbsp;(Para SEO, Sem marcação markdown)</small></label>
                                        <textarea id="descricao_curta" name="descricao_curta" class="form-control not-resize" rows="5" title="Descrição da categoria"
                                                  placeholder="Descreva a categoria em até 70 caracteres" data-required data-min="5" required>{{ $destino->descricao_curta }}</textarea>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>






                                <div class="hide">
                                    {{ method_field("PUT") }}
                                    <input type="hidden" name="destino_id" value="{{ $destino->id }}">
                                    <input type="hidden" name="canal_venda_id" value="{{ $destino->canalVenda->id }}">
                                </div>
                                <div class="em-separator separator-dashed"></div>
                                <div class="text-center">
                                    <button class="btn btn-gradient-01" type="submit">Atualizar cadastro <i class="la la-refresh right"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="destaque">
                    <div class="widget has-shadow">
                        <div class="widget-body pl-lg-5 pr-lg-5">
                            <form name="foto-destino" method="POST" enctype="multipart/form-data" action="{{ route('app.destinos.foto') }}" class="form-horizontal">
                                <div class="ml-auto mt-4 mb-4">
                                    <div class="section-title mr-auto">
                                        <h3>02. Foto de destaque</h3>
                                        <p class="mt-1">Foto para página principal do site</p>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xl-4 mb-3 text-center d-flex align-items-center">
                                        <div class="placeholder"></div>
                                        <a href="{{ $destino->foto_destino }}" title="Foto de destaque {{ $destino->nome }}" class="lumos-link w-100">
                                            <div data-src="{{ $destino->foto_destino }}" id="place-foto" class="placeholder-img lazyload" style="width: {{ $dimensao_foto['width'] }}px; height: 300px"></div>
                                        </a>
                                    </div>
                                    <div class="col-xl-8 mb-3">
                                        <label for="foto" class="form-control-label">Imagem destino</label>
                                        <div class="input-group">
                                            <span class="input-group-btn">
                                                <button type="button" data-callback="#callback_foto" class="btn btn-primary open-search-file">Selecionar nova foto</button>
                                            </span>
                                            <input type="text" id="foto" name="placeholder" placeholder="Nenhum arquivo selecionado"
                                                   class="form-control open-search-file" title="Foto destino" data-callback="#callback_foto">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <ul class="mt-4">
                                            <li><p>Escolha uma foto que defina bem o destino.</p></li>
                                            <li><p>A foto ficará na página inicial do site.</p></li>
                                            <li><p>O tamanho ideal é a partir de {{ $dimensao_foto['width'] }}px de largura.</p></li>
                                            <li><p>Formatos aceitos: .PNG .JPG e .JPEG até 3mb.</p></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="hide">
                                    <input type="file" name="foto" class="hide" id="callback_foto" accept="image/png, image/jpeg, image/jpg">
                                    <input type="hidden" name="destino_id" value="{{ $destino->id }}">
                                </div>
                                <div class="em-separator separator-dashed"></div>
                                <div class="text-center">
                                    <button class="btn btn-gradient-01" type="submit">Atualizar foto <i class="la la-refresh right"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="home">
                    <div class="widget has-shadow">
                        <div class="widget-body pl-lg-5 pr-lg-5">
                            <div class="ml-auto mt-4 mb-4">
                                <div class="section-title mr-auto">
                                    <h3>03. Serviços na home</h3>
                                    <p class="mt-1">Seções e serviços que aparecem na página inicial do destino.</p>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-divider mb-0 table-mobile">
                                    <thead>
                                    <tr>
                                        <th class="text-center">Ordem</th>
                                        <th>Nome seção</th>
                                        <th>Tipo da seção</th>
                                        <th>Quantidade</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Administração</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($destino->homeDestino as $home_destino)
                                        <tr>
                                            <td data-label="Ordem" class="text-center">{{ $home_destino->ordem }}º</td>
                                            <td data-label="Nome seção">
                                                @if($home_destino->status)
                                                    <a href="{{ route('app.destinos.servicos.view', $home_destino->id) }}" data-action="edit-secao">
                                                        {{ $home_destino->titulo }}
                                                    </a>
                                                @else
                                                    {{ $home_destino->titulo }}
                                                @endif
                                            </td>
                                            <td data-label="Tipo da seção">{{ $home_destino->tipo_home }}</td>
                                            <td data-label="Quantidade">{{ $home_destino->servicos->first()->quantidade }} serviço(s)</td>
                                            <td data-label="Status" class="text-center">
                                                @if($home_destino->status)
                                                    <span class="badge-text badge-text-small success">Ativo</span>
                                                @else
                                                    <span class="badge-text badge-text-small danger">Inativo</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($home_destino->status)
                                                    <a href="{{ route('app.destinos.servicos.view', $home_destino->id) }}" data-action="edit-secao" class="btn btn-outline-primary">
                                                        Editar <i class="la la-edit right"></i>
                                                    </a>
                                                @else
                                                    <a href="{{ route('app.destinos.servicos.restore') }}" data-action="ativar-secao" data-id="{{ $home_destino->id }}" class="btn btn-outline-light">
                                                        Ativar <i class="la la-level-up right"></i>
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center pt-4 pb-4">Nenhuma seção cadastrada</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="em-separator separator-dashed"></div>
                            <div class="text-center">
                                <button class="btn btn-gradient-01" data-toggle="modal" data-target="#new-secao-destino">Cadastrar seção <i class="la la-plus right"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="new-secao-destino" class="modal modal-top fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Nova seção</h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">×</span>
                        <span class="sr-only">close</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form data-validate-ajax method="POST" action="{{ route('app.destinos.servicos.store') }}" class="form-horizontal">
                        <div class="form-group row">
                            <div class="col-xl-6 mb-3">
                                <label for="titulo_new_secao" class="form-control-label">Título da seção <small>(Máx. 40 caracteres)</small></label>
                                <input id="titulo_new_secao" type="text" class="form-control" placeholder="Ex. Todo mundo tá comprando eles" required
                                       data-required data-min="5" title="Título da seção" name="titulo" maxlength="40">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-xl-2 mb-3">
                                <label for="ordem_new_secao" class="form-control-label">Posição</label>
                                <input id="ordem_new_secao" type="number" class="form-control" placeholder="0" required
                                       data-required data-min="1" min="0" max="99" title="Posição da seção" name="ordem" maxlength="1">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-xl-4 mb-3">
                                <label for="tipo_new_secao" class="form-control-label">Tipo da seção</label>
                                <select id="tipo_new_secao" name="tipo" class="form-control boostrap-select-custom" required
                                        data-required title="Selecione um tipo">
                                    @foreach($tipos_secao as $index_tipo => $tipo_secao)
                                        <option value="{{ $index_tipo }}">{{ $tipo_secao }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-xl-12 mb-2">
                                <label for="descricao_new_secao" class="form-control-label">Descrição da seção <small>(Máx. 70 caracteres)</small></label>
                                <input id="descricao_new_secao" type="text" class="form-control" placeholder="Descrição curta para a seção com os serviços"
                                       data-min="2" title="Descrição da seção" name="descricao" maxlength="70">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <label for="search-disponiveis">Serviços na seção <small>(Máx 10 serviços por seção)</small></label>
                            </div>
                            <div class="col-xl-6 no-padding-right">
                                <div class="grid-servicos">
                                    <input id="search-disponiveis" type="text" class="form-control search-on-list" data-target="#disponiveis-cadastro" placeholder="Pesquisar serviço disponível">
                                    <ul id="disponiveis-cadastro">
                                        @foreach($servicos as $servico)
                                            <li>
                                                <div class="row">
                                                    <div class="col-10 text-truncate">
                                                        <strong data-nome class="text-truncate">{{ $servico->nome }}</strong>
                                                        <small data-fornecedor class="text-truncate">{{ $servico->fornecedor->nome_fantasia }}</small>
                                                    </div>
                                                    <div class="col-2 text-right d-flex align-items-center">
                                                        <div class="styled-checkbox">
                                                            <input type="checkbox" data-action="cadastro" id="cadastro-{{ $servico->id }}" value="{{ $servico->id }}">
                                                            <label for="cadastro-{{ $servico->id }}"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="grid-servicos">
                                    <input id="search-selecionados" type="text" class="form-control search-on-list" data-target="#cadastro-selecionados" placeholder="Pesquisar serviço selecionado">
                                    <ul id="cadastro-selecionados"></ul>
                                </div>
                            </div>
                        </div>
                        <div class="hide">
                            <input type="hidden" name="destino_id" value="{{ $destino->id }}">
                        </div>
                        <div class="em-separator separator-dashed"></div>
                        <div class="mt-3 text-center">
                            <button class="btn btn-success">Salvar seção <i class="la la-save right"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="edit-secao-destino" class="modal modal-top fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Editar seção</h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">×</span>
                        <span class="sr-only">close</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form data-validate-ajax method="POST" action="{{ route('app.destinos.servicos.update') }}" class="form-horizontal">
                        <div class="form-group row">
                            <div class="col-xl-6 mb-3">
                                <label for="titulo_edit_secao" class="form-control-label">Título da seção <small>(Máx. 40 caracteres)</small></label>
                                <input id="titulo_edit_secao" type="text" class="form-control" placeholder="Ex. Todo mundo tá comprando eles" required
                                       data-required data-min="5" title="Título da seção" name="titulo" maxlength="40">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-xl-2 mb-3">
                                <label for="ordem_edit_secao" class="form-control-label">Posição</label>
                                <input id="ordem_edit_secao" type="number" class="form-control" placeholder="0" required
                                       data-required data-min="1" min="0" max="99" title="Posição da seção" name="ordem" maxlength="1">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-xl-4 mb-3">
                                <label for="tipo_edit_secao" class="form-control-label">Tipo da seção</label>
                                <select id="tipo_edit_secao" name="tipo" class="form-control boostrap-select-custom" required
                                        data-required title="Selecione um tipo">
                                    @foreach($tipos_secao as $index_tipo => $tipo_secao)
                                        <option value="{{ $index_tipo }}">{{ $tipo_secao }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-xl-12 mb-2">
                                <label for="descricao_edit_secao" class="form-control-label">Descrição da seção <small>(Máx. 70 caracteres)</small></label>
                                <input id="descricao_edit_secao" type="text" class="form-control" placeholder="Descrição curta para a seção com os serviços"
                                       data-min="2" title="Descrição da seção" name="descricao" maxlength="70">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <label for="search-disponiveis-edit">Serviços na seção <small>(Máx 10 serviços por seção)</small></label>
                            </div>
                            <div class="col-xl-6 no-padding-right">
                                <div class="grid-servicos">
                                    <input id="search-disponiveis-edit" type="text" class="form-control search-on-list" data-target="#disponiveis-editar" placeholder="Pesquisar serviço disponível">
                                    <ul id="disponiveis-editar">
                                        @foreach($servicos as $servico)
                                            <li>
                                                <div class="row">
                                                    <div class="col-10 text-truncate">
                                                        <strong data-nome class="text-truncate">{{ $servico->nome }}</strong>
                                                        <small data-fornecedor class="text-truncate">{{ $servico->fornecedor->nome_fantasia }}</small>
                                                    </div>
                                                    <div class="col-2 text-right d-flex align-items-center">
                                                        <div class="styled-checkbox">
                                                            <input type="checkbox" data-action="editar" id="editar-{{ $servico->id }}" value="{{ $servico->id }}">
                                                            <label for="editar-{{ $servico->id }}"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="grid-servicos">
                                    <input id="search-selecionados-edit" type="text" class="form-control search-on-list" data-target="#editar-selecionados" placeholder="Pesquisar serviço selecionado">
                                    <ul id="editar-selecionados"></ul>
                                </div>
                            </div>
                        </div>
                        <div class="hide">
                            {{ method_field("PUT") }}
                            <input type="hidden" name="home_destino_id">
                        </div>
                        <div class="em-separator separator-dashed"></div>
                        <div class="mt-3">
                            <button type="button" data-route="{{ route('app.destinos.servicos.remove') }}" data-action="delete"
                                    class="btn btn-danger left">Desativar <i class="la la-ban right"></i></button>
                            <button class="btn btn-success pull-right">Atualizar <i class="la la-refresh right"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
