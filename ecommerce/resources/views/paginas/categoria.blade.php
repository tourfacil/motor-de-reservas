@extends('template.master')

@if($categoria->titulo_pagina == null)
    @if(is_array($secao_atual) && (is_null($cidade_atual)))
        @section('title', "{$secao_atual['nome']} em {$destino->nome}  - Tour Fácil")
    @elseif(is_array($secao_atual) && is_string($cidade_atual))
        @section('title', "{$secao_atual['nome']} em {$cidade_atual}  - Tour Fácil")
    @elseif(is_null($secao_atual) && is_string($cidade_atual))
        @section('title', "{$categoria->nome} em {$cidade_atual}  - Tour Fácil")
    @else
        @section('title', "{$categoria->nome} em {$destino->nome}  - Tour Fácil")
    @endif
@else
    @section('title', $categoria->titulo_pagina)
@endif

@section('body')


    {{-- Navbar --}}
    @include('template.navbar')

    <div class="wrapper-categoria lazyload" data-bg="{{ $categoria->banner_categoria }}">
        <div class="container position-relative d-flex align-items-center justify-content-start h-100">
            @if(is_array($secao_atual) && (is_null($cidade_atual)))
                <h1 class="font-weight-bold">{{ $secao_atual['nome'] }} em <br><span>{{ $destino->nome }}</span></h1>
            @elseif(is_array($secao_atual) && is_string($cidade_atual))
                <h1 class="font-weight-bold">{{ $secao_atual['nome'] }} em <br><span>{{ $cidade_atual }}</span></h1>
            @elseif(is_null($secao_atual) && is_string($cidade_atual))
                <h1 class="font-weight-bold">{{ $categoria->nome }} em <br><span>{{ $cidade_atual }}</span></h1>
            @else
                <h1 class="font-weight-bold">{{ $categoria->nome }} em <br><span>{{ $destino->nome }}</span></h1>
            @endif
        </div>
    </div>

    <main class="container pb-5" data-page="categoria" data-controller="CategoriaCtrl">
        {{-- breadcrumb --}}
        <nav class="custom-bread pt-3 pb-1" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item d-none d-md-flex"><a href="{{ route('ecommerce.index') }}">Tour Fácil</a></li>
                <li class="breadcrumb-item first-mobile"><a href="{{ route('ecommerce.destino.index', $destino->slug) }}">{{ $destino->nome }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $categoria->nome }}</li>
            </ol>
        </nav>
        <div class="row justify-content-between">
            <div id="filtros" class="col-12 col-md-2 d-none d-md-block">
                <h2 class="font-weight-bold mt-2">Filtros</h2>
                <div class="filter-list mt-3 mb-2">
                    @foreach($filtro_secoes as $filtro_secao)
                        {{-- Caso o filtro esteja ativo --}}
                        @if($filtro_secao['slug'] == $filtros_ativos['secao'])
                            <div class="filter-item text-truncate">
                                <a href="{{ route('ecommerce.categoria.index', [$destino->slug, $categoria->slug, $filtros_ativos['query_secoes']]) }}" title="{{ $filtro_secao['nome'] }} - {{ $destino->nome }}">
                                    <div class="filter-category checked"></div> {{ $filtro_secao['nome'] }}
                                </a>
                            </div>
                        @else
                            <div class="filter-item text-truncate">
                                <a href="{{ route('ecommerce.categoria.index', [$destino->slug, $categoria->slug, 'filtro' => $filtro_secao['slug'], $filtros_ativos['query_secoes']]) }}" title="{{ $filtro_secao['nome'] }} - {{ $destino->nome }}">
                                    <div class="filter-category"></div> {{ $filtro_secao['nome'] }}
                                </a>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="col-12 col-md-10">
                <div class="pl-lg-4">
                    <div class="d-flex align-items-center justify-content-between mt-3 mt-md-0 mb-4">
                        <button type="button" data-action="filtros" class="btn d-md-none btn-outline-dark font-weight-medium">
                            Filtrar @if($filtros_ativos['secao'])<span class="ml-1 badge badge-info">1</span>@endif
                        </button>
                        {{-- Ordenacao dos servicos --}}
                        <select title="Ordenação dos serviços" name="listing" id="listing" class="custom-select w-auto ml-auto">
                            <option value="{{ route('ecommerce.categoria.index', [$destino->slug, $categoria->slug, 'ordem' => 'destaques', $filtros_ativos['query_ordem']]) }}"
                                    {{ ($filtros_ativos['ordem'] == "destaques") ? "selected" : "" }}>Destaques</option>
                            <option value="{{ route('ecommerce.categoria.index', [$destino->slug, $categoria->slug, 'ordem' => 'menor-preco', $filtros_ativos['query_ordem']]) }}"
                                    {{ ($filtros_ativos['ordem'] == "menor-preco") ? "selected" : "" }}>Menor preço</option>
                        </select>
                    </div>
                    {{-- lista de servicos --}}
                    @if($qtd_servicos > 0)
                        <div class="row">
                            @foreach($servicos as $servico)
                                @php($servico->tags->splice(\TourFacil\Core\Enum\ServicoEnum::LIMITE_TAGS))
                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="card-servico item mb-4" data-list="Categoria {{ $categoria->nome }}">
                                        <a href="{{ route('ecommerce.servico.view', [$destino->slug, $categoria->slug, $servico->slug]) }}" title="{{ $servico->nome }}">
                                            <div class="card-servico-image">
                                                <img src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" class="lazyload" data-src="{{ $servico->fotoPrincipal->fotoLarge }}" alt="{{ $servico->nome }}">
                                            </div>
                                            <div class="card-body d-flex flex-column">
                                                <h3 class="ellipsis-2-lines">{{ $servico->nome }}</h3>
                                                <div class="card-body-items mt-auto">
                                                    @foreach($servico->tags as $tag_servico)
                                                        <div class="card-body-item">
                                                            <i class="card-body-icon jam jam-{{ $tag_servico->icone }}"></i>
                                                            <span>{{ $tag_servico->descricao }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <div class="mt-auto d-flex justify-content-end align-items-center card-body-price">
                                                    @if($servico->descontoAtivo == null)
                                                        <span>A partir de</span>
                                                        <strong>R$ {{ formataValor($servico->valor_venda) }}</strong>
                                                    @else
                                                        <span style="text-decoration: line-through;">De R$ {{ formataValor($servico->descontoAtivo->valor_de) }}</span>
                                                        <strong style="color: #e73677;">Por R$ {{ formataValor($servico->descontoAtivo->valor_por) }}</strong>
                                                    @endif
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center mt-2">
                            <i class="iconify icon-error mb-3" data-icon="jam:backpack"></i>
                            <h3 class="font-weight-bold mb-1">Nenhum resultado encontrado!</h3>
                            <p class="text-muted">Remova os filtros e tente novamente...</p>
                            <a href="{{ route('ecommerce.categoria.index', [$destino->slug, $categoria->slug]) }}" title="Remover filtros" class="btn btn-blue text-white btn-rounded border-0 pb-2 px-4">
                                Remover filtros <span class="iconify ml-1 align-middle" data-icon="jam:filter"></span>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>

    {{-- Newsletter --}}
    @include('partials.newsletter')

    {{-- Footer --}}
    @include('template.footer')

@endsection

@section('seo')
    <meta name="description" content="{{ $categoria->descricao }}" />
    <meta property="og:description" content="{{ $categoria->descricao }}" />
    <meta property="og:image" content="{{ $categoria->foto_categoria }}" />
    <meta name="twitter:description" content="{{ $categoria->descricao }}" />
    <meta name="twitter:image" content="{{ $categoria->foto_categoria }}" />
    <meta property="og:image:width" content="500" />
    <meta property="og:image:height" content="400" />
@endsection
