@extends('template.header')

@section('title', 'Novo banner')

@section('content')

    <div class="row">
        <div class="page-header">
            <div class="d-flex align-items-center">
                <h2 class="page-header-title text-dark">Novo banner <span class="text-gradient-01">| {{ $canal_venda->nome }}</span></h2>
                <div>{{ Breadcrumbs::render('app.banners.create', $selecionado->id) }}</div>
            </div>
        </div>
    </div>

    <div class="row" data-controller="BannersCtrl">
        <div class="col-12">
            <div class="widget has-shadow">
                <div class="widget-body pl-lg-5 pr-lg-5">
                    <form data-validate-file-ajax action="{{ route('app.banners.store') }}" class="form-horizontal" enctype="multipart/form-data">
                        <div class="ml-auto mt-4 mb-4">
                            <div class="section-title mr-auto">
                                <h3>01. Informações</h3>
                                <p class="mt-1">Tipo de banner, título e descrição</p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-xl-4 mb-3">
                                <label for="destino_id" class="form-control-label">Destino</label>
                                <select id="destino_id" name="destino_id" class="form-control boostrap-select-custom" required
                                        data-required title="Selecione um destino">
                                    @foreach($destinos as $destino)
                                        @if($destino->id == $selecionado->id)
                                            <option value="{{ $destino->id }}" selected>{{ $destino->nome }}</option>
                                        @else
                                            <option value="{{ $destino->id }}">{{ $destino->nome }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-xl-4 mb-3">
                                <label for="servico_id" class="form-control-label">Serviço</label>
                                <select id="servico_id" name="servico_id" class="form-control boostrap-select-custom" required data-live-search="true"
                                        data-required title="Selecione um serviço" data-route="{{ route('app.destinos.servicos.json') }}">
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-xl-4 mb-3">
                                <label for="ordem" class="form-control-label">Ordem do banner</label>
                                <input id="ordem" type="number" class="form-control" placeholder="1 - 99" required
                                       title="Posição do banner" min="1" max="99" maxlength="2" data-required name="ordem">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-xl-4 mb-3">
                                <label for="titulo" class="form-control-label">Título <small>(Texto para chamada 1)</small></label>
                                <input id="titulo" type="text" class="form-control" placeholder="Ex. Trem Maria Fumaça, Máx 25 caracteres" required
                                       data-required data-min="5" title="Título do banner" maxlength="25" data-auto-capitalize name="titulo">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-xl-4 mb-3">
                                <label for="descricao" class="form-control-label">Descrição <small>(Texto para chamada 2)</small></label>
                                <input id="descricao" type="text" class="form-control" placeholder="Ex. Aventure-se com, Máx 25 caracteres" required
                                       data-required data-min="5" title="Descrição do banner" maxlength="25" name="descricao">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-xl-4 mb-3">
                                <label for="canal_venda_id" class="form-control-label">Canal de venda</label>
                                <input id="canal_venda_id" type="text" class="form-control" placeholder="Canal de venda"
                                       readonly title="Canal de venda" value="{{ $canal_venda->site }}">
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
                                <div id="place-banner" class="placeholder-img" style="width: 100%; height: 40vh;"></div>
                            </div>
                            <div class="col-xl-12">
                                <label for="banner" class="form-control-label">Arquivo de imagem <small>(Acima de 1980px de largura)</small></label>
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <button type="button" data-callback="#callback_banner" class="btn btn-primary open-search-file">Selecionar banner</button>
                                    </span>
                                    <input type="text" id="banner" name="placeholder_banner" placeholder="Nenhum arquivo selecionado" required data-required
                                           class="form-control open-search-file" title="Banner" data-callback="#callback_banner">
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
                        <div class="text-center">
                            <button class="btn btn-gradient-01" type="submit">Cadastrar banner <i class="la la-save right"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
