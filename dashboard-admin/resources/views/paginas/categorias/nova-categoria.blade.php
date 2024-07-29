@extends('template.header')

@section('title', 'Nova categoria')

@section('content')

    <div class="row">
        <div class="page-header">
            <div class="d-flex align-items-center">
                <h2 class="page-header-title text-gradient-01">Nova categoria</h2>
                <div>{{ Breadcrumbs::render('app.categorias.create') }}</div>
            </div>
        </div>
    </div>

    <div class="row" data-controller="CategoriaCtrl">
        <div class="col-12">
            <ul id="tab_categoria" class="nav nav-tabs nav-tab-header nav-tab-no-border">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#descricao">
                        <i class="la la-flag-o la-2x align-middle mr-2"></i> Descrição da categoria
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" data-toggle="tab" href="#secoes">
                        <i class="la la-filter la-2x align-middle mr-2"></i> Seções categoria
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" data-toggle="tab" href="#fotos">
                        <i class="la la-image la-2x align-middle mr-2"></i> Fotos de destaque
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane active" id="descricao">
                    <div class="widget has-shadow">
                        <div class="widget-body pl-lg-5 pr-lg-5">
                            <form name="descricao" method="POST" action="{{ route('app.categorias.store') }}" class="form-horizontal">
                                <div class="ml-auto mt-4 mb-4">
                                    <div class="section-title mr-auto">
                                        <h3>01. Descrição da categoria</h3>
                                        <p class="mt-1">Informações detalhadas sobre a categoria</p>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xl-8 mb-3">
                                        <label for="nome" class="form-control-label">Nome da categoria</label>
                                        <input id="nome" type="text" class="form-control" placeholder="Ex. Restaurantes, Passeios, Parques" required
                                               data-required data-min="5" title="Nome da categoria" data-auto-capitalize name="nome">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-xl-4 mb-3">
                                        <label for="tipo_categoria" class="form-control-label">Tipo da categoria</label>
                                        <select id="tipo_categoria" name="tipo" class="form-control boostrap-select-custom" required
                                                data-required title="Selecione um tipo">
                                            @foreach($tipos_categoria as $index_tipo => $tipo_categoria)
                                                <option value="{{ $index_tipo }}">{{ $tipo_categoria }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="form-group row">
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
                                        <label for="posicao_menu" class="form-control-label">Posição no menu</label>
                                        <input id="posicao_menu" type="number" class="form-control" placeholder="1 - 99" required
                                               title="Posição no menu" value="99" min="1" max="99" maxlength="2" data-required name="posicao_menu">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-xl-4 mb-3">
                                        <label for="canal_venda_id" class="form-control-label">Canal de venda</label>
                                        <input id="canal_venda_id" type="text" class="form-control" placeholder="Canal de venda"
                                               readonly title="Canal de venda" value="{{ $canal_venda->site }}">
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
                                                  placeholder="O título da página HTML" data-required data-min="5"></textarea>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-xl-6 mb-3">
                                        <label for="descricao" class="form-control-label">Descrição <small>&nbsp;(Para SEO, Sem marcação markdown)</small></label>
                                        <textarea id="descricao" name="descricao" class="form-control not-resize" rows="5" title="Descrição da categoria"
                                                  placeholder="Descreva a categoria em até 70 caracteres" data-required data-min="5" required></textarea>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="em-separator separator-dashed"></div>
                                <div class="text-center">
                                    <button class="btn btn-gradient-01" type="submit">Próximo passo <i class="la la-angle-right right"></i></button>
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
                                <div class="col-xl-12">
                                    <div class="alert alert-secondary alert-lg square mb-0">
                                        <i class="la la-rocket mr-2"></i>
                                        <strong>Cadastre</strong> uma nova seção para continuar!
                                    </div>
                                </div>
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
                            <form name="fotos_categoria" novalidate autocomplete="off" method="POST" enctype="multipart/form-data" action="{{ route('app.categorias.foto') }}" class="form-horizontal">
                                <div class="ml-auto mt-4 mb-4">
                                    <div class="section-title mr-auto">
                                        <h3>03. Banner da categoria</h3>
                                        <p class="mt-1">Banner para página da categoria.</p>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-5 col-xl-12 mb-3">
                                        <div id="place-banner" class="placeholder-img" style="width: {{ $preset_banner['width'] }}px; height: 25vh;"></div>
                                    </div>
                                    <div class="col-xl-12">
                                        <label for="banner" class="form-control-label">Banner categoria <small>(Acima de {{ $preset_banner['width'] }}px de largura)</small></label>
                                        <div class="input-group">
                                            <span class="input-group-btn">
                                                <button type="button" data-callback="#callback_banner" class="btn btn-primary open-search-file">Selecionar banner</button>
                                            </span>
                                            <input type="text" id="banner" name="placeholder_banner" placeholder="Nenhum arquivo selecionado" required data-required
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
                                        <div id="place-foto" class="placeholder-img" style="width: {{ $preset_foto['width'] }}px; height: 300px"></div>
                                    </div>
                                    <div class="col-xl-8 mb-3">
                                        <label for="foto" class="form-control-label">Foto da categoria</label>
                                        <div class="input-group">
                                            <span class="input-group-btn">
                                                <button type="button" data-callback="#callback_foto" class="btn btn-primary open-search-file">Selecionar foto</button>
                                            </span>
                                            <input type="text" id="foto" name="placeholder" placeholder="Nenhum arquivo selecionado" required data-required
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
                                    <input type="hidden" name="categoria_id" class="callback_categoria_id">
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
                        <form name="new_secao_categoria" method="POST" action="{{ route('app.categorias.secao.store') }}" class="form-horizontal">
                            <div class="form-group row">
                                <div class="col-xl-12">
                                    <div class="alert alert-primary-bordered alert-lg square">
                                        <p class="mb-2">A seção é uma <strong>sub-categoria</strong> dentro da categoria.</p>
                                        <p class="m-0">Exemplo: <br> A categoria <strong>Restaurante</strong> possui Pizzaria como sub-categoria.</p>
                                    </div>
                                </div>
                                <div class="col-xl-12 mb-1 mt-3">
                                    <label for="nome" class="form-control-label">Nome da seção</label>
                                    <input id="nome" type="text" class="form-control" placeholder="Ex. Pizzaria, Fondue, Privativo, Museus, Aventura e etc." required
                                           data-required data-min="2" title="Nome da categoria" data-auto-capitalize name="nome">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="hide">
                                <input type="hidden" name="categoria_id" class="callback_categoria_id">
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
    </div>

@endsection
