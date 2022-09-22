@extends('template.master')

@section('title', "Carrinho de compras")

@section('body')


    @php($position = 1)
    @php($json_servicos = [])

    {{--  Footer sticky  --}}
    <div class="d-md-flex flex-column h-100" data-page="carrinho" data-limpar="{{ route('ecommerce.carrinho.clear') }}" data-controller="CarrinhoCtrl">

        @include('template.navbar-clear')

        {{-- Verifica se há algo no carrinho --}}
        @if(count($carrinho) > 0)
            <main class="flex-shrink-0 bg-light">
                <div class="container pb-5">
                    <div class="row justify-content-between">
                        <div class="col-12">
                            {{-- Passos para checkout --}}
                            @include('paginas.checkout.step', ['current_step' => 1])
                        </div>
                        <div class="col-12 col-lg-4 order-last">
                            <div class="card-shop-left pt-lg-2 mb-3 mb-sm-0">
                                <hr class="blue mb-4">
                                <div class="row d-flex justify-content-between">
                                    <div class="col-auto">
                                        <h2 class="font-weight-medium h5">Total ({{ count($carrinho) }} item)</h2>
                                    </div>
                                    <div class="col-auto text-right">
                                        <strong class="d-block h4 m-0 font-weight-medium">R$ {{ formataValor($total_carrinho) }}</strong>
                                        <small class="text-muted">Nenhum valor adicional</small>
                                    </div>
                                </div>
                                <div class="pt-3">
                                    <a href="{{ route('ecommerce.carrinho.pagamento') }}" title="Finalizar" class="btn btn-blue text-white btn-rounded btn-block border-0 font-weight-medium text-uppercase">Finalizar</a>
                                    <a href="{{ $url_logo }}" title="Ver mais atividades" class="btn btn-blue-outline btn-block btn-rounded pb-2 font-weight-medium text-uppercase">Ver mais atividades</a>
                                    @if(! auth('clientes')->check())
                                        <div class="text-center pt-3 section-login">
                                            <a href="#modal-cadastro" rel="jqmodal:open" class="font-weight-bold">Criar conta</a>
                                            <strong class="text-muted">ou</strong>
                                            <a href="#modal-login" rel="jqmodal:open" class="font-weight-bold">entrar</a>
                                            <span class="text-muted d-block">para reservas mais rápidas</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="faq-custom ml-lg-2 mt-3 d-none d-lg-block">
                                    <h4 class="font-weight-medium">FAQ</h4>
                                    <div class="accordion" id="accordionExample">
                                        @foreach($perguntas as $index => $pergunta)
                                            <div class="accordion-item" id="faq_{{ $index }}">
                                                <button class="btn btn-link d-flex align-items-center" type="button" data-toggle="collapse" data-target="#res_{{ $index }}">
                                                    <i class="iconify mt-1 mr-2" data-icon="jam:chevron-down"></i> {{ $pergunta['questao'] }}
                                                </button>
                                                <div id="res_{{ $index }}" class="accordion-body collapse" data-parent="#faq_{{ $index }}">
                                                    <p class="pl-4 py-2 rounded bg-white m-0">{!! $pergunta['resposta'] !!}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-8 pr-lg-4">
                            <h1 class="font-weight-medium h2 m-0">Carrinho</h1>
                            <hr class="blue mb-3">
                            <div class="alert alert-cart mt-3 mb-4 text-center line-initial" role="alert">
                                <strong class="">Seu lugar está reservado <br class="d-sm-none"> por <time>20:00 minutos</time></strong>
                            </div>
                            <div class="shopping-cart-content">
                                @foreach($carrinho as $index => $servico_carrinho)
                                    @php($json_servicos[$index] = ["id" => $servico_carrinho['uuid'], "name" => $servico_carrinho['nome_servico'], "price" => $servico_carrinho['valor_total'], "category" => $servico_carrinho['categoria'] . " em " . $servico_carrinho['cidade'], "position" => $position++, "quantity" => 1])
                                    <div class="row align-items-center mb-4 pb-1">
                                        <div class="col-3 pr-0 pr-lg-3">
                                            <a href="{{ $servico_carrinho['url'] }}" title="{{ $servico_carrinho['nome_servico'] }}">
                                                <img src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" class="img-fluid rounded lazyload" data-src="{{ $servico_carrinho['foto_principal'] }}" alt="{{ $servico_carrinho['nome_servico'] }}">
                                            </a>
                                        </div>
                                        <div class="col-9">
                                            <div class="d-flex">
                                                <a href="{{ $servico_carrinho['url'] }}" class="text-truncate" title="{{ $servico_carrinho['nome_servico'] }}">
                                                    <h3 class="h4 mb-0 text-truncate">{{ $servico_carrinho['nome_servico'] }}</h3>
                                                </a>
                                            </div>
                                            <div class="text-muted mt-1">
                                                <p class="m-0">{{ $servico_carrinho['categoria'] }} em {{ $servico_carrinho['cidade'] }}</p>
                                                <p class="m-0">{{ dataExtenso($servico_carrinho['agenda_selecionada']['data']) }}</p>
                                                <p class="m-0">Para {{ ($servico_carrinho['com_bloqueio'] + $servico_carrinho['sem_bloqueio']) }} pessoa(s)</p>
                                            </div>
                                            <div class="row d-flex justify-content-between">
                                                <div class="col-auto d-flex align-items-center">
                                                    <button title="Alterar quantidade" data-action="edit-servico" data-index="{{ $index }}"
                                                            data-route="{{ route('ecommerce.servico.calendario', $servico_carrinho['uuid']) }}" class="btn btn-link font-weight-medium btn-not-focus pl-0">Editar</button>
                                                    <span class="text-blue">|</span>
                                                    <button title="Remover serviço" data-action="remove" data-index="{{ $index }}"
                                                            data-remove="{{ route('ecommerce.carrinho.remove', $servico_carrinho['uuid']) }}" class="btn btn-link btn-not-focus">Excluir</button>
                                                </div>
                                                <div class="col-auto">
                                                    <strong class="h5 mb-3">R$ {{ formataValor($servico_carrinho['valor_total']) }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        @else
            <div class="d-flex flex-column align-items-center justify-content-center text-center px-4 px-md-0 h-100 bg-light pb-5">
                @if($expirado)
                    <i class="iconify empty mt-3" data-icon="jam:alarm-clock"></i>
                    <h1 class="font-weight-bold line-initial">Seu carrinho expirou!</h1>
                    <p class="text-muted line-initial">Adicione os itens novamente em seu carrinho de compras.</p>
                @else
                    <i class="iconify empty mt-3" data-icon="jam:shopping-cart"></i>
                    <h1 class="font-weight-bold line-initial">Seu carrinho está vazio!</h1>
                    <p class="text-muted line-initial">Encontre sua próxima aventura explorando nosso site</p>
                @endif
                <a href="{{ $url_logo }}" title="Página Incial" class="btn btn-blue border-0 text-white btn-rounded px-4 pb-2 text-uppercase mt-1">Ver aventuras pelo Brasil</a>
            </div>
        @endif

        @include('template.footer-clear')
    </div>

    {{-- Modais de login e cadastro --}}
    @if(! auth('clientes')->check())

        {{-- Modal de cadastro --}}
        @include('paginas.modais.modal-cadastro')

        {{-- Modal de cadastro e redefinicao de senha --}}
        @include('paginas.modais.modal-login')
    @endif

    {{-- Modais para edicao do servico --}}
    @if(count($carrinho) > 0)

        {{-- Modal de loader --}}
        @include('paginas.modais.modal-loader')

        {{-- Modal para compra --}}
        @include('paginas.modais.modal-compra')

        {{-- Modal para informar os dados dos acompanhantes --}}
        @include('paginas.modais.lista-acompanhantes')

        {{-- Modal para informar os dados adicionais --}}
        @include('paginas.modais.campo-adicional')
    @endif

@endsection

@section('scripts')
    <script>
        window.carrinho = @json($json_servicos);
    </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


        @IF(empty($servicos_desatualizados) == false)
            <script>

                window.addEventListener("load", function(event) {
                    
                    sweetAlert({ 
                        title: 'Atenção', 
                        icon: 'warning',
                        text: 'As seguintes atividades foram removidas do seu carrinho, por falta de disponibilidade ou alteração de valores. Consulte a atualização na página do produto:\n \n {{$servicos_desatualizados_texto}}',
                    }).then((result) => {
                        window.location.reload()
                    });

                });

            </script>
        @ENDIF
@endsection
