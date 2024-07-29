@extends('template.header')

@section('title', "Banners do $canal_venda->nome")

@section('content')

    <div class="row">
        <div class="page-header">
            <div class="d-flex align-items-center">
                <h2 class="page-header-title text-dark">Banners do <span class="text-gradient-01">{{ $canal_venda->nome }}</span></h2>
                <div>{{ Breadcrumbs::render('app.banners.index') }}</div>
            </div>
        </div>
    </div>

    <div class="row" data-controller="BannersCtrl">
        <div class="col-12">
            <div class="widget has-shadow">
                <div class="widget-header p-3 rounded">
                    <div class="row d-flex align-items-center">
                        <div class="col-12 col-sm-7">
                            <h4 class="mr-auto">Gerencie os banners de {{ $selecionado->nome }}</h4>
                        </div>
                        <div class="col-12 col-sm-2 pr-0 text-md-right pt-3 pt-sm-0">
                            <label for="periodo_vendas_filtro" class="m-auto text-primary">Selecione o destino</label>
                        </div>
                        <div class="col-12 col-sm-3">
                            <select name="destino_filter" id="destino_filter" title="Destino"
                                    class="form-control d-inline" data-route="{{ route('app.banners.index') }}">
                                @foreach($destinos as $destino)
                                    @if($selecionado->id == $destino->id)
                                        <option value="{{ $destino->id }}" selected>{{ $destino->nome }}</option>
                                    @else
                                        <option value="{{ $destino->id }}">{{ $destino->nome }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row flex-row">
                @forelse($banners as $banner)
                    <div class="col-lg-4 col-md-6 col-sm-12 col-remove">
                        <div class="widget-image has-shadow">
                            <div class="contact-card-2">
                                <div class="cover-bg">
                                    <a href="{{ route('app.banners.view', $banner->id) }}" class="d-block" title="Editar Banner">
                                        <div class="banner-preview lazyload" data-src="{{ $banner->banner_url }}">
                                            @if($banner->status)
                                                <span class="badge-text info">Ativo</span>
                                            @else
                                                <span class="badge-text danger">Inativo</span>
                                            @endif
                                        </div>
                                    </a>
                                </div>
                                <div class="widget-body">
                                    <span class="float-right">{{ $banner->ordem }}º</span>
                                    <div class="job text-left">{{ $banner->descricao }}</div>
                                    <h4 class="name mt-0 text-left">
                                        <a href="{{ route('app.banners.view', $banner->id) }}" title="Editar Banner">{{ $banner->titulo }}</a>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center pt-5 ">
                        <i class="la la-image la-4x"></i>
                        <p class="h3 pt-3 text-black-50">Nenhum banner cadastrado!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="float-button">
        <a href="{{ route('app.banners.create', $selecionado->id) }}" class="btn btn-gradient-01 btn-shadow"><i class="ion ion-plus-round"></i></a>
        <p class="float-tooltip">Cadastrar novo banner</p>
    </div>

@endsection
