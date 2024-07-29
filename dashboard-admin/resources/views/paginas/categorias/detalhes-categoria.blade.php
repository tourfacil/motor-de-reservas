@extends('template.header')

@section('title', $categoria->nome . " em " . $categoria->destino->nome . " - " . $categoria->destino->canalVenda->nome)

@section('content')

    <div class="row">
        <div class="page-header {{ ($categoria->status === false) ? "pb-4" : "" }}">
            <div class="d-flex align-items-center">
                <h2 class="page-header-title text-dark">{{ $categoria->nome . " - " . $categoria->destino->nome }} <span class="text-gradient-01">| {{ $categoria->destino->canalVenda->nome }}</span></h2>
                <div>{{ Breadcrumbs::render('app.categorias.view') }}</div>
            </div>
        </div>
    </div>

    @if($categoria->status === false)
        <div class="row">
            <div class="col-12">
                <div class="alert alert-danger alert-lg square mb-4" role="alert">
                    <strong>Categoria desativada.</strong> Para editá-la você deve ativar a categoria primeiro!
                </div>
            </div>
        </div>
    @endif

    <div class="row" data-controller="CategoriaCtrl">
        <div class="col-12">
            <ul id="tab_categoria" class="nav nav-tabs nav-tab-header nav-tab-no-border tabs-mobile">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#descricao">
                        <i class="la la-flag-o la-2x align-middle mr-2"></i> Descrição da categoria
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#secoes">
                        <i class="la la-filter la-2x align-middle mr-2"></i> Seções categoria
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#fotos">
                        <i class="la la-image la-2x align-middle mr-2"></i> Fotos de destaque
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane active" id="descricao">
                    <div class="widget has-shadow">
                        <div class="widget-body pl-lg-5 pr-lg-5">
                            <form data-validate-ajax method="POST" action="{{ route('app.categorias.update') }}" class="form-horizontal">
                                <div class="row d-flex align-items-center">
                                    <div class="col-lg-9 mt-4 mb-4">
                                        <div class="section-title mr-auto">
                                            <h3>01. Descrição da categoria</h3>
                                            <p class="mt-1">Informações detalhadas sobre a categoria</p>
                                        </div>
                                    </div>
                                    {{-- <div class="col-lg-3 d-flex justify-content-end">
                                        <label class="m-0 d-flex align-items-center cursor-pointer">
                                            <span class="mr-3 pointer">Status categoria</span>
                                            <input name="status" class="toggle-checkbox" type="checkbox" {{ ($categoria->status) ? "checked" : "" }}>
                                            <span><span></span></span>
                                        </label>
                                    </div> --}}
                                </div>
                                <div class="form-group row">
                                    <div class="col-xl-6 mb-3">
                                        <label for="nome" class="form-control-label">Nome da categoria</label>
                                        <input id="nome" type="text" class="form-control" placeholder="Ex. Restaurantes, Passeios, Parques" required
                                               data-required data-min="5" title="Nome da categoria" data-auto-capitalize name="nome" value="{{ $categoria->nome }}">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-xl-3 mb-3">
                                        <label for="tipo_categoria" class="form-control-label">Tipo da categoria</label>
                                        <select id="tipo_categoria" name="tipo" class="form-control boostrap-select-custom" required
                                                data-required title="Selecione um tipo">
                                            @foreach($tipos_categoria as $index_tipo => $tipo_categoria)
                                                @if($categoria->tipo == $index_tipo)
                                                    <option value="{{ $index_tipo }}" selected>{{ $tipo_categoria }}</option>
                                                @else
                                                    <option value="{{ $index_tipo }}">{{ $tipo_categoria }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-xl-3 mb-3">
                                        <label for="status" class="form-control-label">Status da categoria</label>
                                        <select id="status" name="status" class="form-control boostrap-select-custom" required
                                                data-required title="Selecione um status">
                                            @foreach($status_categoria as $index_tipo => $status)
                                                @if($categoria->status == $index_tipo)
                                                    <option value="{{ $index_tipo }}" selected>{{ $status }}</option>
                                                @else
                                                    <option value="{{ $index_tipo }}">{{ $status }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xl-4 mb-3">
                                        <label for="destino_id" class="form-control-label">Destino &nbsp;<small>(Não é possível alterar)</small></label>
                                        <input id="destino_id" type="text" class="form-control" readonly value="{{ $categoria->destino->nome }}">
                                    </div>
                                    <div class="col-xl-4 mb-3">
                                        <label for="posicao_menu" class="form-control-label">Posição no menu</label>
                                        <input id="posicao_menu" type="number" class="form-control" placeholder="1 - 99" required value="{{ $categoria->posicao_menu }}"
                                               title="Posição no menu" min="1" max="99" maxlength="2" data-required name="posicao_menu">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-xl-4 mb-3">
                                        <label for="canal_venda_id" class="form-control-label">Canal de venda &nbsp;<small>(Não é possível alterar)</small></label>
                                        <input id="canal_venda_id" type="text" class="form-control" readonly value="{{ $categoria->destino->canalVenda->site }}">
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
                                                  placeholder="O título da página HTML" data-required data-min="5">{{ $categoria->titulo_pagina }}</textarea>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-xl-6 mb-3">
                                        <label for="descricao" class="form-control-label">Descrição <small>&nbsp;(Para SEO, Sem marcação markdown)</small></label>
                                        <textarea id="descricao" name="descricao" class="form-control not-resize" rows="5" title="Descrição da categoria"
                                                  placeholder="Descreva a categoria em até 70 caracteres" data-required data-min="5" required>{{ $categoria->descricao }}</textarea>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="hide">
                                    {{ method_field("PUT") }}
                                    <input type="hidden" name="categoria_id" value="{{ $categoria->id }}">
                                    <input type="hidden" name="destino_id" value="{{ $categoria->destino->id }}">
                                </div>
                                <div class="em-separator separator-dashed"></div>
                                <div class="text-center">
                                    <button class="btn btn-gradient-01" type="submit">Atualizar descrição <i class="la la-refresh right"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="secoes">
                    <div class="widget widget-18 has-shadow">
                        <div class="widget-body pl-lg-5 pr-lg-5">
                            <div class="ml-auto mt-4 mb-4">
                                <div class="section-title mr-auto">
                                    <h3>02. Seções categoria</h3>
                                    <p class="mt-1">As seções são usadas como filtro na página de categoria.</p>
                                </div>
                            </div>
                            <div class="em-separator separator-dashed"></div>
                            <div class="row mt-3">
                                @if(sizeof($categoria->secoesCategoria) == 0)
                                    <div class="col-xl-12">
                                        <div class="alert alert-secondary alert-lg square mb-0">
                                            <i class="la la-ban mr-2"></i>
                                            <strong>Nenhuma</strong> seção cadastrada!
                                        </div>
                                    </div>
                                @else
                                    @foreach($categoria->secoesCategoria as $secao_categoria)
                                        <div class="col-xl-4">
                                           <div class="list-group-item">
                                               <div class="other-message">
                                                   <div class="media">
                                                       <div class="media-left align-self-center mr-3">
                                                           <div class="media-letter">
                                                               <span>{{ str_limit($secao_categoria->nome, 1, "") }}</span>
                                                           </div>
                                                       </div>
                                                       <div class="media-body align-self-center">
                                                           <div class="other-message-sender">{{ $secao_categoria->nome }}</div>
                                                           <div class="other-message-time">Cadastrado: {{ $secao_categoria->created_at->format('d/m/Y H:i') }}</div>
                                                       </div>
                                                       <div class="media-right align-self-center">
                                                           <div class="actions">
                                                               <a href="{{ route('app.categorias.secao.view', $secao_categoria->id) }}" data-action="edit-secao">
                                                                   <i class="la la-edit reply"></i>
                                                               </a>
                                                           </div>
                                                       </div>
                                                   </div>
                                               </div>
                                           </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <div class="em-separator separator-dashed"></div>
                            <div class="text-center mt-3">
                                <button class="btn btn-gradient-01" data-toggle="modal" data-target="#new-secao-categoria">Adicionar seção <i class="la la-plus right"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="fotos">
                    <div class="widget has-shadow">
                        <div class="widget-body pl-lg-5 pr-lg-5">
                            <form name="fotos_categoria" method="POST" autocomplete="off" enctype="multipart/form-data" action="{{ route('app.categorias.foto.update') }}" class="form-horizontal">
                                <div class="ml-auto mt-4 mb-4">
                                    <div class="section-title mr-auto">
                                        <h3>03. Banner da categoria</h3>
                                        <p class="mt-1">Banner para página da categoria.</p>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-5 col-xl-12 mb-3">
                                        <a href="{{$categoria->banner_categoria }}" title="Banner {{ $categoria->nome }}" class="lumos-link w-100">
                                            <div id="place-banner" data-src="{{ $categoria->banner_categoria }}" class="placeholder-img place-loader-large lazyload" style="width: {{ $preset_banner['width'] }}px; height: 25vh;"></div>
                                        </a>
                                    </div>
                                    <div class="col-xl-12">
                                        <label for="banner" class="form-control-label">Banner categoria <small>(Acima de {{ $preset_banner['width'] }}px de largura)</small></label>
                                        <div class="input-group">
                                            <span class="input-group-btn">
                                                <button type="button" data-callback="#callback_banner" class="btn btn-primary open-search-file">Selecionar novo banner</button>
                                            </span>
                                            <input type="text" id="banner" name="placeholder_banner" placeholder="Nenhum arquivo selecionado"
                                                   class="form-control open-search-file" title="Banner categoria" data-callback="#callback_banner">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <ul class="mt-4">
                                            <li><p class="mb-0">Formatos aceitos: .PNG .JPG e .JPEG até 3mb.</p></li>
                                        </ul>
                                    </div>
                                    <div class="hide">
                                        {{-- data-placeholder lugar do preview da foto data-name é onde ira o nome da foto input --}}
                                        <input type="file" name="banner" class="hide" id="callback_banner" data-placeholder="#place-banner"
                                               data-name="#banner" accept="image/png, image/jpeg, image/jpg">
                                    </div>
                                </div>
                                <div class="em-separator separator-dashed"></div>
                                <div class="ml-auto mt-4 mb-4">
                                    <div class="section-title mr-auto">
                                        <h3>04. Foto de destaque</h3>
                                        <p class="mt-1">Foto para página principal do site.</p>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xl-4 mb-3">
                                        <a href="{{$categoria->foto_categoria }}" title="Foto destaque {{ $categoria->nome }}" class="lumos-link w-100">
                                            <div id="place-foto" data-src="{{ $categoria->foto_categoria }}" class="placeholder-img place-loader-medium lazyload" style="width: {{ $preset_foto['width'] }}px; height: 300px"></div>
                                        </a>
                                    </div>
                                    <div class="col-xl-8 mb-3">
                                        <label for="foto" class="form-control-label">Foto da categoria</label>
                                        <div class="input-group">
                                            <span class="input-group-btn">
                                                <button type="button" data-callback="#callback_foto" class="btn btn-primary open-search-file">Selecionar nova foto</button>
                                            </span>
                                            <input type="text" id="foto" name="placeholder" placeholder="Nenhum arquivo selecionado"
                                                   class="form-control open-search-file" title="Foto categoria" data-callback="#callback_foto">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <ul class="mt-4">
                                            <li><p>Escolha uma foto que defina bem a categoria.</p></li>
                                            <li><p>A foto ficará na página inicial do site.</p></li>
                                            <li><p>O ideal é que tenha no mínimo {{ $preset_foto['width'] }}px de largura.</p></li>
                                            <li><p>Formatos aceitos: .PNG .JPG e .JPEG até 3mb.</p></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="hide">
                                    {{-- data-placeholder lugar do preview da foto data-name é onde ira o nome da foto input --}}
                                    <input type="file" name="foto" class="hide" id="callback_foto" data-placeholder="#place-foto"
                                           data-name="#foto" accept="image/png, image/jpeg, image/jpg">
                                    <input type="hidden" name="categoria_id" value="{{ $categoria->id }}">
                                    {{ method_field("PUT") }}
                                </div>
                                <div class="em-separator separator-dashed"></div>
                                <div class="text-center">
                                    <button class="btn btn-gradient-01" type="submit">Atualizar fotos <i class="la la-refresh right"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="new-secao-categoria" class="modal modal-top fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Novo cadastro</h4>
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">×</span>
                            <span class="sr-only">close</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form data-validate-ajax method="POST" action="{{ route('app.categorias.secao.store') }}" class="form-horizontal">
                            <div class="form-group row">
                                <div class="col-xl-12">
                                    <div class="alert alert-primary-bordered alert-lg square">
                                        <p class="mb-2">A seção é uma <strong>sub-categoria</strong> dentro da categoria.</p>
                                        <p class="m-0">Exemplo: <br> A categoria <strong>Restaurante</strong> possui Pizzaria como sub-categoria.</p>
                                    </div>
                                </div>
                                <div class="col-xl-12 mb-1 mt-3">
                                    <label for="nome_new_secao" class="form-control-label">Nome da seção</label>
                                    <input id="nome_new_secao" type="text" class="form-control" placeholder="Ex. Pizzaria, Fondue, Privativo, Museus, Aventura e etc." required
                                           data-required data-min="2" title="Nome da categoria" data-auto-capitalize name="nome">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="hide">
                                <input type="hidden" name="categoria_id" value="{{ $categoria->id }}">
                            </div>
                            <div class="em-separator separator-dashed"></div>
                            <div class="text-center mt-3">
                                <button class="btn btn-success">Salvar seção <i class="la la-angle-right right"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div id="edit-secao-categoria" class="modal modal-top fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Editar seção</h4>
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">×</span>
                            <span class="sr-only">close</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form data-validate-ajax method="POST" action="{{ route('app.categorias.secao.update') }}" class="form-horizontal">
                            <div class="form-group row">
                                <div class="col-xl-12">
                                    <div class="alert alert-primary-bordered alert-lg square">
                                        <p class="m-0">O nome da seção deve ser <strong>única</strong> dentro da categoria.</p>
                                    </div>
                                </div>
                                <div class="col-xl-12 mb-1 mt-3">
                                    <label for="nome_edit_secao" class="form-control-label">Nome da seção</label>
                                    <input id="nome_edit_secao" type="text" class="form-control" placeholder="Ex. Pizzaria, Fondue, Privativo, Museus, Aventura e etc." required
                                           data-required data-min="2" title="Nome da categoria" data-auto-capitalize name="nome">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="hide">
                                {{ method_field("PUT") }}
                                <input type="hidden" name="secao_id">
                                <input type="hidden" name="categoria_id" value="{{ $categoria->id }}">
                                <input type="hidden" name="delete_secao" value="off">
                            </div>
                            <div class="em-separator separator-dashed"></div>
                            <div class="mt-3">
                                <button type="button" data-action="delete" class="btn btn-danger left">Excluir <i class="la la-trash right"></i></button>
                                <button class="btn btn-success pull-right">Atualizar <i class="la la-refresh right"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection
