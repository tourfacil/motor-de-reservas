@extends('template.master')

@section('title', \App\Enum\NatalLuzEnum::TITULO_PAGINA )

@section('canonical', route('ecommerce.servico.view', [$destino->slug, $categoria_padrao->slug, $servico->slug]))

@section('body')

    <style>
        .owl-nav-blue .owl-nav button.owl-next, .owl-nav-blue .owl-nav button.owl-prev {
            background: {{ \App\Enum\NatalLuzEnum::COR_PAGINA }};
        }

        .section-newsletter {
            background-color: {{ \App\Enum\NatalLuzEnum::COR_PAGINA }};
        }

        .font-weight-bold {
            color: {{ \App\Enum\NatalLuzEnum::COR_PAGINA }};
        }

        .card-servico .card-body .card-body-price strong {
            color: {{ \App\Enum\NatalLuzEnum::COR_PAGINA }}
        }
    </style>

    @php
        function printAvaliacaoHTML($nota, $cod_star, $padding = 0) {
            $html = "";


            for($i = 1; $i <= 5; $i++) {
                if($i <= $nota) {
                    $html .= "<img id='star1' style='padding:" . $padding . "px;' src='" . $cod_star . "'>";
                } else {
                    //$html .= "<img id='star1' src='" . asset('images/star0.png') . "'>";
                }
            }

            $html .= '';

            return $html;
        }

        function breakText($texto) {
            $inicio = "";
            $final = "";

            $tamanho_texto = strlen($texto);

            return [
                'inicio' => substr($texto, 0, 280),
                'final' => substr($texto, $tamanho_texto/ 2),
            ];
        }
    @endphp


    {{-- Navbar --}}
    @include('template.navbar')

    {{-- Galeria de imagens --}}
    <div class="wrapper-slider-servico">
        <div class="owl-carousel owl-nav-transparent owl-nav-container owl-gallery">
            @foreach($servico->fotos as $foto_servico)
                @php($foto = $foto_servico->foto_large)
                <div class="item">
                    <a href="{{ $foto }}" title="Imagem {{ $foto_servico->legenda }}" data-lightbox="galeria">
                        <img src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" class="owl-lazy" data-src="{{ $foto }}" alt="{{ $foto_servico->legenda }}">
                    </a>
                </div>
            @endforeach
        </div>
        <button data-action="openGallery" class="btn btn-purple btn-rounded px-4 pb-2 border-0 d-none d-lg-inline">Ver galeria completa</button>
    </div>

    <main class="container" data-page="servico" data-controller="ServicoCtrl" data-servico="{{ route('ecommerce.servico.view.json', [$servico->uuid, 'category' => $categoria->slug]) }}">
        {{-- breadcrumb --}}
        <nav class="custom-bread pt-3 pb-2" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item d-none d-md-flex"><a href="{{ route('ecommerce.index') }}">Tour Fácil</a></li>
                <li class="breadcrumb-item first-mobile"><a href="{{ route('ecommerce.destino.index', $destino->slug) }}">{{ $destino->nome }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('ecommerce.categoria.index', [$destino->slug, $categoria->slug]) }}">{{ $categoria->nome }}</a></li>
                <li class="breadcrumb-item active d-none d-md-flex" aria-current="page">Ingressos do Natal Luz</li>
            </ol>
        </nav>



        <div class="pb-3 pt-4">
            @if(count($relacionados) > 0)
                <h3 class="h2 pb-1 font-weight-bold">{{ \App\Enum\NatalLuzEnum::TITULO_SLIDER }}</h3>
                <div class="owl-carousel owl-nav-blue owl-nav-container owl-relacionados mt-4 mb-5 owl-padding">
                    @foreach($relacionados as $servico_relacionado)
{{--                        @php(dd($servico_relacionado))--}}
                        @php($lazy_class = ($loop->index <= 3) ? "lazyload" : "owl-lazy")
                        @php($servico_relacionado->tags->splice(\TourFacil\Core\Enum\ServicoEnum::LIMITE_TAGS))
                        <div class="card-servico item">
                            <a href="{{ route('ecommerce.servico.view', [$destino->slug, $categoria->slug, $servico_relacionado->slug]) }}" title="{{ $servico_relacionado->nome }}">
                                <div class="card-servico-image">
                                    <img src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" class="{{ $lazy_class }}" data-src="{{ $servico_relacionado->fotoPrincipal->fotoLarge }}" alt="{{ $servico_relacionado->nome }}">
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <h3 class="ellipsis-2-lines">{{ $servico_relacionado->nome }}</h3>
                                    <div class="card-body-items mt-auto">
                                        @foreach($servico_relacionado->tags as $tag_servico)
                                            <div class="card-body-item">
                                                <i class="card-body-icon jam jam-{{ $tag_servico->icone }}"></i>
                                                <span>{{ $tag_servico->descricao }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="mt-auto d-flex justify-content-end align-items-center card-body-price">
                                        <span>A partir de</span>
                                        <strong>R$ {{ formataValor($servico_relacionado->valor_venda) }}</strong>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>




{{--        <div class="row justify-content-center justify-content-lg-between">--}}
{{--            <div class="col-12 col-lg-7">--}}
{{--                --}}{{-- Detalhes do serviço --}}
{{--                <section class="service-details pr-md-1 mt-1 mt-md-3 mt-lg-0">--}}
{{--                    @if($servico->titulo_servico == null)--}}
{{--                        <h1 class="h1 font-weight-bold">{{ $servico->nome }}</h1>--}}
{{--                    @else--}}
{{--                        <h1 class="h1 font-weight-bold">{{ $servico->titulo_servico }}</h1>--}}
{{--                    @endif--}}
{{--                    <p class="horario">{{ $servico->horario }}</p>--}}
{{--                </section>--}}
{{--            </div>--}}
{{--            <div class="col-12 col-md-8 col-lg-5 col-top-lg order-lg-last">--}}
{{--                <div class="fixed-content">--}}
{{--                    --}}{{-- Card flutante para compra --}}
{{--                    <div class="rounded bg-grey p-4 card-buy ml-lg-2 mb-3 mb-md-0 mt-2 mt-lg-0">--}}
{{--                        <div class="text-center">--}}
{{--                            <p class="h4 text-muted mb-1">Valores a partir de</p>--}}
{{--                            <div class="d-flex align-items-start justify-content-center card-buy-valor">--}}
{{--                                <strong class="moeda mr-2">R$ </strong>--}}
{{--                                <strong class="price">{{ formataValor($servico->valor_venda) }}</strong>--}}
{{--                            </div>--}}
{{--                            <div class="card-buy-valor mt-3">--}}
{{--                                <strong class="h5 d-block text-muted mb-3">Parcele em até {{ config('site.parcelamento') }}x!</strong>--}}
{{--                                <small class="text-muted">Aqui você compra com segurança.</small>--}}
{{--                                <small class="text-muted">Sem burocracias e sem taxas escondidas!</small>--}}
{{--                            </div>--}}
{{--                            <div class="text-center mt-3">--}}
{{--                                <img src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" class="lazyload img-fluid" data-src="{{ asset('images/bandeiras_cartao.svg') }}" alt="Cartões aceitos">--}}
{{--                                <button class="btn btn-link btn-not-focus" title="Veja mais opções de pagamento" data-modal="#modal-formas-pagamento">Ver mais opções de pagamento</button>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="text-center mt-3 d-none d-block">--}}
{{--                            @if($ativo)--}}
{{--                                <button data-action="comprar" class="btn btn-purple btn-not-focus btn-rounded text-uppercase font-weight-medium">COMPRAR AGORA</button>--}}
{{--                            @else--}}
{{--                                <button disabled class="btn">Serviço indisponível</button>--}}
{{--                            @endif--}}
{{--                            <p class="text-muted mt-2 mb-0">Você não será cobrado ainda</p>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="faq-custom ml-lg-2 mt-3 d-none d-lg-block">--}}
{{--                        <h4 class="font-weight-bold">FAQ</h4>--}}
{{--                        <div class="accordion" id="accordionExample">--}}
{{--                            @foreach($perguntas as $index => $pergunta)--}}
{{--                                <div class="accordion-item" id="faq_{{ $index }}">--}}
{{--                                    <button class="btn btn-link d-flex align-items-center" type="button" data-toggle="collapse" data-target="#res_{{ $index }}">--}}
{{--                                        <i class="iconify mt-1 mr-2" data-icon="jam:chevron-down"></i> {{ $pergunta['questao'] }}--}}
{{--                                    </button>--}}
{{--                                    <div id="res_{{ $index }}" class="accordion-body collapse" data-parent="#faq_{{ $index }}">--}}
{{--                                        <p class="pl-4 py-2 rounded bg-light m-0">{!! $pergunta['resposta'] !!}</p>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            @endforeach--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="col-12 col-lg-7">--}}
{{--                --}}{{-- Detalhes do serviço --}}
{{--                <section class="service-details pr-md-1 mt-1 mt-md-3 mt-lg-0">--}}
{{--                    @IF($servico->tagsInternas->count() > 0)--}}
{{--                        <div style="margin-bottom: 15px;">--}}

{{--                            <div style="background-color: rgba(0, 0, 0, 0.017);padding: 0.8rem 1rem; border-radius: 3px; margin: 0.8rem 0 0.95rem 0; text-align: justify;">--}}
{{--                                @FOREACH($servico->tagsInternas as $tag)--}}
{{--                                    <div style="margin-bottom: 15px;">--}}
{{--                                        <div style="margin-bottom: 10px;">--}}
{{--                                            <h4 style=" color: #4E4E4E;">--}}
{{--                                                <i style="margin-right: 5px;" class="{{ $tag->icone }}"></i> {{ $tag->titulo }}--}}
{{--                                            </h4>--}}
{{--                                        </div>--}}
{{--                                        <div style="margin-left: 15px;">--}}
{{--                                            {{ $tag->descricao }}--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                @ENDFOREACH--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    @ENDIF--}}
{{--                    <h3>Sobre esta atividade</h3>--}}
{{--                    --}}{{-- Descricao do servico --}}
{{--                    {!! markdown_cache($servico->descricao_completa, "desc_{$servico->id}") !!}--}}
{{--                    --}}{{-- Regras do servico --}}
{{--                    {!! markdown_cache($servico->regras, "regras_{$servico->id}") !!}--}}
{{--                    --}}{{-- Regras do fornecedor --}}
{{--                    {!! markdown_cache($servico->fornecedor->termos, "termos_{$servico->id}") !!}--}}
{{--                    @if(!is_null($servico->localizacao))--}}
{{--                        <h3>Como chegar:</h3>--}}
{{--                        <div class="localizacao mb-2">--}}
{{--                            <img src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" class="lazyload img-fluid" data-src="{{ asset('images/icons/icone-mapa.svg') }}" alt="Icone como chegar">--}}
{{--                            <p>{{ $servico->localizacao }}</p>--}}
{{--                        </div>--}}
{{--                        <div class="embed-responsive rounded mb-2 mt-4">--}}
{{--                            <iframe data-hj-allow-iframe class="embed-responsive-item lazyload rounded" data-src="https://www.google.com/maps/embed/v1/place?key={{ env('GOOGLE_API_KEY') }}&q={{ Str::slug($servico->localizacao, "+") }}+{{ Str::slug($servico->fornecedor->nome_fantasia, "+") }}" allowfullscreen></iframe>--}}
{{--                        </div>--}}
{{--                        <div class="text-center mt-4 d-md-none">--}}
{{--                            <a href="https://www.google.com/maps?daddr={{ Str::slug($servico->localizacao, "+") }}+{{ Str::slug($servico->fornecedor->nome_fantasia, "+") }}&hl=pt-br"--}}
{{--                               target="_blank" title="Ver no mapa a localização" class="btn btn-blue btn-rounded text-white px-4 pb-2">Ver no mapa</a>--}}
{{--                        </div>--}}
{{--                    @endif--}}

{{--                    @if($total_avaliacoes > 0)--}}
{{--                        <h3>Avaliações <small style="margin-left: 10px;">{{ $nota_media }}</small> <img style="margin-bottom: 5px;" src="{{ asset('images/star1-24.png') }}"> <small>({{ $total_avaliacoes }} avaliações)</small></h3>--}}
{{--                        <div id="section-avaliacoes">--}}
{{--                            @foreach($avaliacoes as $key => $avaliacao)--}}
{{--                                <div class="avaliacaoi-{{ $key }}">--}}
{{--                                    <span><b>{{ $avaliacao->nome }}</b></span>--}}
{{--                                    <br>--}}
{{--                                    {!! printAvaliacaoHTML($avaliacao->nota, asset('images/star1-16.png'), 5) !!}--}}
{{--                                    <br>--}}
{{--                                    @if(strlen($avaliacao->avaliacao) <= 280)--}}
{{--                                        <p style="text-align: justify;">{{ $avaliacao->avaliacao }}</p>--}}
{{--                                    @else--}}
{{--                                        <p id="avaliacao-resumo-{{ $key }}" style="text-align: justify; margin-bottom: 0px;">{{ substr($avaliacao->avaliacao, 0, 280) }}...</p>--}}
{{--                                        <p id="avaliacao-completo-{{ $key }}" style="text-align: justify; display: none;">{{ $avaliacao->avaliacao }}</p>--}}
{{--                                        <a id="ver-mais-avaliacao-{{ $key }}" key="{{ $key }}" class="btn-ver-mais" href="#">Ver mais</a>--}}
{{--                                        <a style="display: none;" id="ver-menos-avaliacao-{{ $key }}" key="{{ $key }}" class="btn-ver-menos" href="#">Ver menos</a>--}}
{{--                                    @endif--}}
{{--                                    <p>Feito em {{ $avaliacao->created_at->format('d/m/Y') }}</p>--}}
{{--                                </div>--}}
{{--                            @endforeach--}}
{{--                        </div>--}}
{{--                        @if($total_avaliacoes > 2)--}}
{{--                            <button id="carregar-mais-avaliacoes" class="btn btn-purple-outline btn-rounded px-4">Ver mais avaliações</button>--}}
{{--                            <div style="display: none;">--}}
{{--                                <span id="avaliacao-qtd">{{ $total_avaliacoes }}</span>--}}
{{--                                <span id="avaliacao-qtd-mostrada">2</span>--}}
{{--                                <span id="avaliacao-link">{{ Route('ecommerce.servico.avaliacoes', $servico->uuid) }}</span>--}}
{{--                                <span id="avaliacao-next-key">{{ $key + 1 }}</span>--}}
{{--                                <span id="avaliacao-star">{{ asset('images/star1-16.png') }}</span>--}}
{{--                            </div>--}}
{{--                        @endif--}}
{{--                    @endif--}}
{{--                </section>--}}
{{--            </div>--}}
{{--        </div>--}}

{{--        --}}{{-- Barra fixa no mobile --}}
{{--        <div class="fixed-bottom-mobile d-lg-none px-3 py-2">--}}
{{--            <div class="row flex-nowrap justify-content-between align-items-center">--}}
{{--                <div class="col-auto">--}}
{{--                    <small class="text-muted mb-1">Valores a partir de</small>--}}
{{--                    <div class="d-flex align-items-start">--}}
{{--                        <strong class="moeda">R$ </strong>--}}
{{--                        <strong class="price">{{ formataValor($servico->valor_venda) }}</strong>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="col-auto pl-0">--}}
{{--                    @if($ativo)--}}
{{--                        <button data-action="comprar" class="btn btn-purple btn-not-focus btn-rounded border-0 px-md-4 pb-2 text-uppercase font-weight-medium">COMPRAR AGORA</button>--}}
{{--                    @else--}}
{{--                        <button disabled class="btn">Indisponível</button>--}}
{{--                    @endif--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}

{{--        <div class="pb-3 pt-4">--}}
{{--            @if(count($relacionados) > 0)--}}
{{--                <h3 class="h2 pb-1 font-weight-bold">As pessoas também compraram</h3>--}}
{{--                <div class="owl-carousel owl-nav-blue owl-nav-container owl-relacionados mt-4 mb-5 owl-padding">--}}
{{--                    @foreach($relacionados as $servico_relacionado)--}}
{{--                        @php($lazy_class = ($loop->index <= 3) ? "lazyload" : "owl-lazy")--}}
{{--                        @php($servico_relacionado->tags->splice(\TourFacil\Core\Enum\ServicoEnum::LIMITE_TAGS))--}}
{{--                        <div class="card-servico item">--}}
{{--                            <a href="{{ route('ecommerce.servico.view', [$destino->slug, $categoria->slug, $servico_relacionado->slug]) }}" title="{{ $servico_relacionado->nome }}">--}}
{{--                                <div class="card-servico-image">--}}
{{--                                    <img src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" class="{{ $lazy_class }}" data-src="{{ $servico_relacionado->fotoPrincipal->fotoLarge }}" alt="{{ $servico_relacionado->nome }}">--}}
{{--                                </div>--}}
{{--                                <div class="card-body d-flex flex-column">--}}
{{--                                    <h3 class="ellipsis-2-lines">{{ $servico_relacionado->nome }}</h3>--}}
{{--                                    <div class="card-body-items mt-auto">--}}
{{--                                        @foreach($servico_relacionado->tags as $tag_servico)--}}
{{--                                            <div class="card-body-item">--}}
{{--                                                <i class="card-body-icon jam jam-{{ $tag_servico->icone }}"></i>--}}
{{--                                                <span>{{ $tag_servico->descricao }}</span>--}}
{{--                                            </div>--}}
{{--                                        @endforeach--}}
{{--                                    </div>--}}
{{--                                    <div class="mt-auto d-flex justify-content-end align-items-center card-body-price">--}}
{{--                                        <span>A partir de</span>--}}
{{--                                        <strong>R$ {{ formataValor($servico_relacionado->valor_venda) }}</strong>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </a>--}}
{{--                        </div>--}}
{{--                    @endforeach--}}
{{--                </div>--}}
{{--            @endif--}}
{{--        </div>--}}
    </main>

    {{-- Modal de loader --}}
    @include('paginas.modais.modal-loader')

    {{-- Modal com as formas de pagamento --}}
    @include('paginas.modais.formas-pagamento', ['canal_venda' => $canal_venda])

    {{-- Modal para compra --}}
    @include('paginas.modais.modal-compra')

    {{-- Modal para informar os dados dos acompanhantes --}}
    @include('paginas.modais.lista-acompanhantes')

    {{-- Modal para informar os dados adicionais --}}
    @include('paginas.modais.campo-adicional')

    {{-- Newsletter --}}
    @include('partials.newsletter')

    {{-- Footer --}}
    @include('template.footer')

@endsection

@section('seo')
    <meta name="description" content="{{ $servico->descricao_curta }}" />
    <meta property="og:description" content="{{ $servico->descricao_curta }}" />
    <meta property="og:image" content="{{ $foto_destaque->foto_large }}" />
    <meta name="twitter:description" content="{{ $servico->descricao_curta }}" />
    <meta name="twitter:image" content="{{ $foto_destaque->foto_large }}" />
    <meta property="og:image:width" content="800" />
    <meta property="og:image:height" content="600" />
@endsection

@push('json-ld')
    <script type="application/ld+json">
        {
          "@context": "https://schema.org/",
          "@type": "Product",
          "name": "{{ $servico->nome }}",
          "image": "{{ $foto_destaque->foto_large }}",
          "description": "{{ trim($servico->descricao_curta) }}",
          "sku": "{{ $servico->uuid }}",
          @if($total_avaliacoes > 0)
            "aggregateRating": {
              "@type": "AggregateRating",
              "ratingValue": "{{ $nota_media }}",
              "reviewCount": "{{ $total_avaliacoes }}"
            },
          @endif
        "offers": {
          "@type": "Offer",
          "url": "{!! request()->url() !!}",
            "priceCurrency": "BRL",
            "price": "{{ $servico->valor_venda }}",
            "availability": "{{ ($ativo) ? "http://schema.org/InStock" : "http://schema.org/OutOfStock" }}"
          }
        }
    </script>
@endpush
