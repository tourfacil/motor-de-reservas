@extends('template.header')

@section('title', "Banner {$banner->titulo}")

@section('content')

    <div class="row">
        <div class="page-header {{ ($banner->status)?: "pb-4" }}">
            <div class="d-flex align-items-center">
                <h2 class="page-header-title text-dark">Banner {{ $banner->titulo }} <span class="text-gradient-01">| {{ $banner->destino->nome }}</span></h2>
                <div>{{ Breadcrumbs::render('app.banners.view', $banner->destino->id) }}</div>
            </div>
        </div>
    </div>

    {{-- Informativo sobre o status do banner --}}
    <div class="row">
        <div class="col-12">
            @if($banner->status == false)
                <div class="alert alert-danger mb-4" role="alert">
                    <i class="la la-ban mr-2"></i>
                    Este banner está <strong>Inativo</strong>!
                </div>
            @endif
        </div>
    </div>

    <div class="row" data-controller="BannersCtrl" data-route-status="{{ route('app.banners.status') }}" data-banner="{{ $banner->id }}">
        <div class="col-12">
            <div class="widget has-shadow">
                <div class="widget-body pl-lg-5 pr-lg-5">
                    <form data-validate-file-ajax action="{{ route('app.banners.update') }}" class="form-horizontal" enctype="multipart/form-data">
                        <div class="ml-auto mt-4 mb-4">
                            <div class="row">
                                <div class="col-12 col-md-8">
                                    <div class="section-title mr-auto">
                                        <h3>01. Informações</h3>
                                        <p class="mt-1">Tipo de banner, título e descrição</p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4 text-md-right" data-buttons-action>
                                    @if($banner->status == false)
                                        <button type="button" class="btn btn-secondary mr-2" title="Reativar banner" data-action="ativar">Reativar <i class="la la-arrow-up right"></i></button>
                                    @else
                                        <button type="button" class="btn btn-secondary mr-2" title="Desativar banner" data-action="desativar">Desativar <i class="la la-ban right"></i></button>
                                    @endif
                                    <button type="button" class="btn btn-danger" title="Excluir banner" data-action="excluir">Excluir <i class="la la-trash right"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-xl-4 mb-3">
                                <label for="destino_id" class="form-control-label">Destino <small>(Não é possível alterar)</small></label>
                                <input id="destino_id" type="text" class="form-control" placeholder="Destino"
                                       title="Destino" value="{{ $banner->destino->nome }}" readonly>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-xl-4 mb-3">
                                <label for="servico_id" class="form-control-label">Serviço <small>(Não é possível alterar)</small></label>
                                <input id="servico_id" type="text" class="form-control" placeholder="Serviço"
                                       title="Serviço" value="{{ $banner->servico->nome }}" readonly>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-xl-4 mb-3">
                                <label for="ordem" class="form-control-label">Ordem do banner</label>
                                <input id="ordem" type="number" class="form-control" placeholder="1 - 99" required value="{{ $banner->ordem }}"
                                       title="Posição do banner" min="1" max="99" maxlength="2" data-required name="ordem">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-xl-4 mb-3">
                                <label for="titulo" class="form-control-label">Título <small>(Texto para chamada 1)</small></label>
                                <input id="titulo" type="text" class="form-control" placeholder="Ex. Trem Maria Fumaça, Máx 25 caracteres" required
                                       data-required data-min="5" title="Título do banner" maxlength="25" data-auto-capitalize name="titulo" value="{{ $banner->titulo }}">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-xl-4 mb-3">
                                <label for="descricao" class="form-control-label">Descrição <small>(Texto para chamada 2)</small></label>
                                <input id="descricao" type="text" class="form-control" placeholder="Ex. Aventure-se com, Máx 25 caracteres" required
                                       data-required data-min="5" title="Descrição do banner" maxlength="25" name="descricao" value="{{ $banner->descricao }}">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-xl-4 mb-3">
                                <label for="canal_venda_id" class="form-control-label">Canal de venda</label>
                                <input id="canal_venda_id" type="text" class="form-control" placeholder="Canal de venda"
                                       readonly title="Canal de venda" value="{{ $banner->destino->canalVenda->site }}">
                            </div>
                        </div>
                        <div class="em-separator separator-dashed"></div>
                        <div class="ml-auto mt-4 mb-4">
                            <div class="section-title mr-auto">
                                <h3>02. Upload do banner</h3>
                                <p class="mt-1">Com no mínimo 1980px de largura</p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-5 col-xl-12 mb-3">
                                <a href="{{ $banner->banner_url }}" title="Ver banner" class="lumos-link w-100">
                                    <div id="place-banner" class="placeholder-img lazyload" style="width: 100%; height: 40vh;" data-src="{{ $banner->banner_url }}"></div>
                                </a>
                            </div>
                            <div class="col-xl-12">
                                <label for="banner" class="form-control-label">Arquivo de imagem <small>(Acima de 1980px de largura)</small></label>
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <button type="button" data-callback="#callback_banner" class="btn btn-primary open-search-file">Selecionar banner</button>
                                    </span>
                                    <input type="text" id="banner" name="placeholder_banner" placeholder="Nenhum arquivo selecionado"
                                           class="form-control open-search-file" title="Banner" data-callback="#callback_banner">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <ul class="mt-4">
                                    <li><p class="mb-0">Formatos aceitos: .PNG .JPG e .JPEG até 3mb.</p></li>
                                </ul>
                            </div>
                            <div class="hide">
                                {{ method_field("PUT") }}
                                <input type="hidden" name="banner_id" value="{{ $banner->id }}">
                                {{-- data-placeholder lugar do preview da foto data-name é onde ira o nome da foto input --}}
                                <input type="file" name="banner" class="hide" id="callback_banner" data-placeholder="#place-banner"
                                       data-name="#banner" accept="image/png, image/jpeg, image/jpg">
                            </div>
                        </div>
                        <div class="em-separator separator-dashed"></div>
                        <div class="text-center">
                            <button class="btn btn-gradient-01" type="submit">Atualizar banner <i class="la la-refresh right"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
