@extends('template.master')

@section('title', "Os Melhores Ingressos em {$destino->nome}")

@section('body')


    @include('template.navbar')

    <section class="banner-destino lazyload" data-bg="{{ $banner->banner_url }}" data-controller="DestinoCtrl">
        <div class="container h-100">
            <div class="d-flex align-items-center justify-content-center h-100">
                <div class="text-center">
                    <h1 class="font-weight-medium text-white mb-4">{{ $banner->titulo }}</h1>
                    <div class="btn btn-blue btn-rounded px-4 pb-2">{{ formatarNome($banner->descricao) }}</div>
                </div>
            </div>
        </div>
    </section>

    <main data-page="destino" class="pb-4 mb-3">
        <div class="bg-blue-light">
            <div class="container">
                {{-- breadcrumb --}}
                <nav class="custom-bread pt-3 pb-2" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('ecommerce.index') }}">Tour Fácil</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $destino->nome }}</li>
                    </ol>
                </nav>
            </div>
        </div>

        {{-- CAMPANHA DE DIA DOS NAMORADOS --}}
        @IF(config('campanha.ativo') == true)
           

            @php

                $ids_servico = config('campanha.servicos'); 
                $servicos_campanha = TourFacil\Core\Models\Servico::whereIn('id', $ids_servico)
                                                                ->with([
                                                                    'fotoPrincipal',
                                                                    'categoria',
                                                                    'destino',
                                                                    'tags'
                                                                    ])
                                                                ->get()->shuffle();


            @endphp


            <style>
                .owl-nav-campanha .owl-nav button.owl-prev {
                    background-color: {{ config('campanha.cor') }};
                } 

                .owl-nav-campanha .owl-nav button.owl-next {
                    background-color: {{ config('campanha.cor') }};
                } 
                
            </style>


            <section class="section-servicos-populares mt-4 mb-4" id="namorados" class="namorados">
                <div class="container pt-3">
                    <h2 class="text-center font-weight-medium pb-2" style="color: {{ config('campanha.cor') }}">{{ config('campanha.titulo') }}</h2>
                    <div class="owl-carousel owl-nav-campanha owl-nav-container owl-servicos mt-4 owl-padding">
                        @foreach($servicos_campanha as $index => $servico_destaque)
                            @php($servico_destaque->tags->splice(\TourFacil\Core\Enum\ServicoEnum::LIMITE_TAGS))
                            @php($lazy_class = ($index <= 3) ? "lazyload" : "owl-lazy")
                            <div class="card-servico item">
                                <a href="{{ route('ecommerce.servico.view', [$servico_destaque->destino->slug, $servico_destaque->categoria->slug, $servico_destaque->slug]) }}" title="{{ $servico_destaque->nome }}">
                                    <div class="card-servico-image">
                                        <img src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" class="{{ $lazy_class }}" data-src="{{ $servico_destaque->fotoPrincipal->fotoLarge }}" alt="{{ $servico_destaque->nome }}">
                                    </div>
                                    <div class="card-body d-flex flex-column">
                                        <h3 class="ellipsis-2-lines" sty style="color: {{ config('campanha.cor') }}">{{ $servico_destaque->nome }}</h3>
                                        <div class="card-body-items mt-auto">
                                            @foreach($servico_destaque->tags as $tag_servico)
                                                <div class="card-body-item">
                                                    <i class="card-body-icon jam jam-{{ $tag_servico->icone }}"></i>
                                                    <span>{{ $tag_servico->descricao }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="mt-auto d-flex justify-content-end align-items-center card-body-price">
                                            <span>A partir de</span>
                                            <strong style="color: {{ config('campanha.cor') }}">R$ {{ formataValor($servico_destaque->valor_venda) }}</strong>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @ENDIF    


        {{-- Home destino --}}
        @foreach($destino->homeDestino as $home_destino)
            <div class="{{ ($loop->first) ? "bg-blue-light pb-1" : "bg-white pt-2 mt-1" }}">
                <section class="container pb-4">
                    <header>
                        <h2 class="h1 font-weight-bold mb-0 {{ ($loop->first) ? "text-blue" : "" }}">{{ $home_destino->titulo }}</h2>
                        <p class="mb-4 text-muted">{{ $home_destino->descricao }}</p>
                    </header>
                    <div class="owl-carousel owl-nav-blue owl-nav-container owl-servicos owl-padding">
                        @foreach($home_destino->servicosAtivos as $servico_home)
                            @php($lazy_class = ($loop->index <= 3) ? "lazyload" : "owl-lazy")
                            @php($servico_home->tags->splice(\TourFacil\Core\Enum\ServicoEnum::LIMITE_TAGS))
                            <div class="card-servico item" data-list="Home {{ $destino->nome }}">
                                <a href="{{ route('ecommerce.servico.view', [$destino->slug, $servico_home->categoria->slug, $servico_home->slug]) }}" title="{{ $servico_home->nome }}">
                                    <div class="card-servico-image">
                                        <img src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" class="{{ $lazy_class }}" data-src="{{ $servico_home->fotoPrincipal->fotoLarge }}" alt="{{ $servico_home->nome }}">
                                    </div>
                                    <div class="card-body d-flex flex-column">
                                        <h3 class="ellipsis-2-lines">{{ $servico_home->nome }}</h3>
                                        <div class="card-body-items mt-auto">
                                            @foreach($servico_home->tags as $tag_servico)
                                                <div class="card-body-item">
                                                    <i class="card-body-icon jam jam-{{ $tag_servico->icone }}"></i>
                                                    <span>{{ $tag_servico->descricao }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="mt-auto d-flex justify-content-end align-items-center card-body-price">
                                            <span>A partir de</span>
                                            <strong>R$ {{ formataValor($servico_home->valor_venda) }}</strong>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </section>
            </div>
        @endforeach
    </main>

    {{-- Newsletter --}}
    @include('partials.newsletter')

    {{-- Footer --}}
    @include('template.footer')

@endsection

@section('seo')
    <meta name="description" content="{{ $destino->descricao_curta }}" />
    <meta property="og:description" content="{{ $destino->descricao_curta }}" />
    <meta property="og:image" content="{{ $destino->foto_destino }}" />
    <meta name="twitter:description" content="{{ $destino->descricao_curta }}" />
    <meta name="twitter:image" content="{{ $destino->foto_destino }}" />
    <meta property="og:image:width" content="500" />
    <meta property="og:image:height" content="400" />
@endsection
