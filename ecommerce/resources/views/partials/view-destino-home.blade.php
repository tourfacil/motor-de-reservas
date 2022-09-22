<div class="container section-info-destino pb-4">
    <div class="row">
        <div class="col-12 col-md-6 col-lg-5 d-none d-md-flex">
            <img src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" class="overlay-destinos d-none d-md-block img-fluid lazyload" data-src="{{ asset('images/overlay_destinos.svg') }}" alt="Overlay destinos">
            <img src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" class="section-info-destino-image lazyload" data-src="{{ $destino_destaque['foto'] }}" alt="{{ $destino_destaque['nome'] }}">
        </div>
        <div class="col-12 col-md-6 col-lg-7">
            <section class="px-lg-4">
                <h3 id="nome_destino" class="mt-md-3">{!! $destino_destaque['destino'] !!}</h3>
                <div id="desc_destino" class="pr-lg-4 mt-3 mt-sm-4">{!! $destino_destaque['descricao'] !!}</div>
                <div class="chip-sub-categorias pt-2">
                    @foreach($destino_destaque['categorias'] as $categoria_destino)
                        <a href="{{ $categoria_destino['url'] }}" title="{{ $categoria_destino['nome'] }} em {{ $destino_destaque['nome'] }}"
                           class="btn btn-rounded btn-outline-dark text-uppercase btn-sm">#{{ $categoria_destino['nome'] }}</a>
                    @endforeach
                </div>
            </section>
        </div>
    </div>
</div>

<section class="section-atividades mt-4 pb-4">
    <div class="container">
        <h4 class="h2 text-center text-sm-left">Atrações imperdíveis em {{ $destino_destaque['nome'] }}</h4>
        <div class="owl-carousel owl-nav-purple owl-nav-container owl-padding owl-servicos">
            @foreach($destino_destaque['servicos'] as $index => $destino_servico)
                @php($lazy_class = ($index <= 3) ? "lazyload" : "owl-lazy")
                <div class="card-servico item">
                    <a href="{{ $destino_servico['url'] }}" title="{{ $destino_servico['nome'] }}">
                        <div class="card-servico-image">
                            <img src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" class="{{ $lazy_class }}" data-src="{{ $destino_servico['foto'] }}" alt="{{ $destino_servico['nome'] }}">
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h3 class="ellipsis-2-lines h4">{{ $destino_servico['nome'] }}</h3>
                            <div class="card-body-items mt-auto">
                                @foreach($destino_servico['tags'] as $tag_servico)
                                    <div class="card-body-item">
                                        <i class="card-body-icon jam jam-{{ $tag_servico['icone'] }}"></i>
                                        <span>{{ $tag_servico['descricao'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-auto d-flex justify-content-end align-items-center card-body-price">
                                <span>A partir de</span>
                                <strong>R$ {{ $destino_servico['valor_venda'] }}</strong>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
        <div class="text-center pt-3 pb-3">
            <a href="{{ $destino_destaque['url'] }}" title="Ver todas as atividades {{ $destino_destaque['nome'] }}"
               class="btn btn-purple-outline btn-rounded mt-4 text-uppercase">Ver todas as atividades</a>
        </div>
    </div>
</section>
