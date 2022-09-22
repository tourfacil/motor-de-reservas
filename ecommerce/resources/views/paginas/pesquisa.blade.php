@extends('template.master')

@section('title', "Resultados para '{$keyword}'")

@section('body')

   

    {{-- Navbar --}}
    @include('template.navbar')

    <div class="wrapper-pesquisa lazyload" data-bg="{{ asset('images/fundo_newsletter.png') }}">
        <div class="container position-relative d-flex align-items-center justify-content-start h-100">
            <h1 class="font-weight-bold">Resultados para <br class="d-none d-md-block"><span>'{{ $keyword }}'</span></h1>
        </div>
    </div>

    <main class="container pb-5" data-page="categoria" data-controller="CategoriaCtrl">
        {{-- breadcrumb --}}
        <nav class="custom-bread pt-3 pb-1" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('ecommerce.index') }}">Tour Fácil</a></li>
                <li class="breadcrumb-item active" aria-current="page">Resultados para '{{ $keyword }}'</li>
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
                                <a href="{{ route('ecommerce.servico.search', ['q' => $keyword, $filtros_ativos['query_secoes']]) }}" title="{{ $filtro_secao['nome'] }}">
                                    <div class="filter-category checked"></div> {{ $filtro_secao['nome'] }}
                                </a>
                            </div>
                        @else
                            <div class="filter-item text-truncate">
                                <a href="{{ route('ecommerce.servico.search', ['q' => $keyword, 'secao' => $filtro_secao['slug'], $filtros_ativos['query_secoes']]) }}" title="{{ $filtro_secao['nome'] }}">
                                    <div class="filter-category"></div> {{ $filtro_secao['nome'] }}
                                </a>
                            </div>
                        @endif
                    @endforeach
                </div>
                <h2 class="font-weight-bold mt-3">Cidades</h2>
                <div class="filter-list mt-3">
                    @foreach($filtro_cidades as $filtro_cidade)
                        {{-- Caso o filtro esteja ativo --}}
                        @if($filtro_cidade['slug'] == $filtros_ativos['cidade'])
                            <div class="filter-item text-truncate">
                                <a href="{{ route('ecommerce.servico.search', ['q' => $keyword, $filtros_ativos['query_cidade']]) }}" title="{{ $filtro_cidade['cidade'] }}">
                                    <div class="filter-category checked"></div> {{ $filtro_cidade['cidade'] }}
                                </a>
                            </div>
                        @else
                            <div class="filter-item text-truncate">
                                <a href="{{ route('ecommerce.servico.search', ['q' => $keyword, 'cidade' => $filtro_cidade['slug'], $filtros_ativos['query_cidade']]) }}" title="{{ $filtro_cidade['cidade'] }}">
                                    <div class="filter-category"></div> {{ $filtro_cidade['cidade'] }}
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
                            Filtrar @if($filtros_ativos['secao'] || $filtros_ativos['cidade'])<span class="ml-1 badge badge-info">1</span>@endif
                        </button>
                        {{-- Ordenacao dos servicos --}}
                        <select title="Ordenação dos serviços" name="listing" id="listing" class="custom-select w-auto ml-auto">
                            <option value="{{ route('ecommerce.servico.search', ['q' => $keyword, 'ordem' => 'destaques', $filtros_ativos['query_ordem']]) }}"
                                    {{ ($filtros_ativos['ordem'] == "destaques") ? "selected" : "" }}>Destaques</option>
                            <option value="{{ route('ecommerce.servico.search', ['q' => $keyword, 'ordem' => 'menor-preco', $filtros_ativos['query_ordem']]) }}"
                                    {{ ($filtros_ativos['ordem'] == "menor-preco") ? "selected" : "" }}>Menor preço</option>
                        </select>
                    </div>
                    @if($qtd_servicos > 0)
                        <div class="row">
                            @foreach($servicos as $servico)
                                @php($servico->tags->splice(\TourFacil\Core\Enum\ServicoEnum::LIMITE_TAGS))
                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="card-servico item mb-4" data-list="Resultado pesquisa">
                                        <a href="{{ route('ecommerce.servico.view', [$servico->destino->slug, $servico->categoria->slug, $servico->slug]) }}" title="{{ $servico->nome }}">
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
                                                    <span>A partir de</span>
                                                    <strong>R$ {{ formataValor($servico->valor_venda) }}</strong>
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
                            <a href="{{ route('ecommerce.servico.search', ['q' => $keyword]) }}" title="Remover filtros" class="btn btn-blue text-white btn-rounded border-0 pb-2 px-4">
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
