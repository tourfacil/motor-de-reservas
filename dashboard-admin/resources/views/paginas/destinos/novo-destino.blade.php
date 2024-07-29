@extends('template.header')

@section('title', 'Novo destino')

@section('content')

    <div class="row">
        <div class="page-header">
            <div class="d-flex align-items-center">
                <h2 class="page-header-title text-gradient-01">Novo destino</h2>
                <div>{{ Breadcrumbs::render('app.destinos.create') }}</div>
            </div>
        </div>
    </div>

    <div class="row" data-controller="DestinoCtrl">
        <div class="col-12">
            <ul id="tab_destino" class="nav nav-tabs nav-tab-header nav-tab-no-border">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#descricao">
                        <i class="la la-map la-2x align-middle mr-2"></i> Descrição do destino
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" data-toggle="tab" href="#destaque">
                        <i class="la la-image la-2x align-middle mr-2"></i> Foto de destaque
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane active" id="descricao">
                    <div class="widget has-shadow">
                        <div class="widget-body pl-lg-5 pr-lg-5">
                            <form name="descricao" method="POST" action="{{ route('app.destinos.store') }}" class="form-horizontal">
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
                                               data-required data-min="5" title="Nome do destino" data-auto-capitalize name="nome">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-xl-4 mb-3">
                                        <label for="canal_venda_id" class="form-control-label">Canal de venda</label>
                                        <select id="canal_venda_id" name="canal_venda_id" class="form-control boostrap-select-custom" required
                                                data-required title="Canal de venda" data-placeholder="Selecione um canal">
                                            @foreach($canais_venda as $canal_venda)
                                                @if($canal_venda->id === $canal_user->id)
                                                    <option value="{{ $canal_venda->id }}" selected>{{ $canal_venda->site }}</option>
                                                @else
                                                    <option value="{{ $canal_venda->id }}">{{ $canal_venda->site }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xl-12 mb-3">
                                        <label for="descricao_curta" class="form-control-label">Descrição curta <small>&nbsp;(Para SEO, Sem marcação markdown)</small></label>
                                        <textarea id="descricao_curta" name="descricao_curta" class="form-control not-resize" rows="3" title="Descrição curta"
                                                  placeholder="Descreva o destino em até 70 caracteres" data-required data-min="5" required></textarea>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xl-12 mb-0">
                                        <label for="descricao_completa" class="form-control-label">Descrição longa <small>&nbsp;(Com marcação markdown)</small></label>
                                        <textarea id="descricao_completa" name="descricao_completa" class="form-control not-resize simple-editor" title="Descrição completa"
                                                  placeholder="Descreva o destino em até 250 caracteres" data-required data-min="5" required></textarea>
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
                                    <div class="col-xl-4 mb-3">
                                        <div id="place-foto" class="placeholder-img" style="width: {{ $dimensao_foto['width'] }}px; height: 400px"></div>
                                    </div>
                                    <div class="col-xl-8 mb-3">
                                        <label for="foto" class="form-control-label">Imagem destino</label>
                                        <div class="input-group">
                                            <span class="input-group-btn">
                                                <button type="button" data-callback="#callback_foto" class="btn btn-primary open-search-file">Selecionar foto</button>
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
                                    <input type="hidden" name="destino_id" class="callback_destino_id">
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
    </div>

@endsection
