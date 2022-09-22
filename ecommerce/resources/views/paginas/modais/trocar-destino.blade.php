<div id="modal-trocar-destino" class="jqmodal">
    <h6 class="font-weight-medium h2 text-blue mb-1">Nossos destinos</h6>
    <p class="line-initial">Selecione qual destino você deseja</p>
    <ul class="list-group list-group-flush" itemscope itemtype="https://www.schema.org/SiteNavigationElement">
        @foreach($destinos as $destino_nav)
            @if($destino_nav->id != $destino->id)
                <li class="list-group-item pl-0">
                    <a href="{{ route('ecommerce.destino.index', $destino_nav->slug) }}" class="h5 nav-link p-0 my-1" itemprop="url" title="Ir para {{ $destino_nav->nome }}">
                        <i class="iconify mr-2 align-text-bottom" data-icon="jam:map-marker-f"></i> <span class="text-dark ">{{ $destino_nav->nome }}</span>
                    </a>
                </li>
            @endif
        @endforeach
    </ul>
</div>
