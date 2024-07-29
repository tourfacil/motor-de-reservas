@extends('template.header')

@section('title', 'Nova avaliação')

@section('content')
    <div class="row">
        <div class="page-header">
            <div class="d-flex align-items-center">
                <h2 class="page-header-title text-gradient-01">Nova avaliação de serviço</h2>
                <div>{{ Breadcrumbs::render('app.servicos.avaliacoes.create') }}</div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="tab-content">
            <div class="tab-pane active" id="cadastro">
                <div class="widget has-shadow">
                    <div class="widget-body pl-lg-5 pr-lg-5">
                        <form id="post" method="POST" action="{{ Route('app.servicos.avaliacoes.store') }}" class="form-horizontal">
                            @CSRF
                            <div class="ml-auto mt-4 mb-4">
                                <div class="section-title mr-auto">
                                    <h3>01. Dados da avaliação</h3>
                                    <p class="mt-1">Informações para a avaliação do serviço</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xl-6 mb-3">
                                    <label for="nome" class="form-control-label">Nome do cliente</label>
                                    <input id="nome" type="text" class="form-control" placeholder="Nome do cliente" required
                                           data-required data-min="3" title="nome" name="nome">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-xl-6 mb-3">
                                    <label for="banco" class="form-control-label">Serviço</label>
                                    <div class="input-group">
                                        {{--                                            <span class="input-group-addon addon-secondary left"><i class="la la-suitcase"></i></span>--}}
                                        <select id="select-servico" name="servico_id" class="form-control boostrap-select-custom" required>
                                            <option value="0">Selecione o serviço</option>
                                            @FOREACH($servicos as $servico)
                                                <option value="{{$servico->id}}">{{$servico->nome}}</option>
                                            @ENDFOREACH
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="form-group row">

                                <div class="col-xl-6 mb-3">
                                    <label for="nome" class="form-control-label">Resenha do cliente</label>
                                    <textarea rows="5" id="avalicao" type="text" class="form-control" placeholder="Resenha do cliente" required
                                              data-required data-min="3" title="avaliacao" name="avaliacao"></textarea>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-xl-3 mb-3">
                                    <label for="banco" class="form-control-label">Status</label>
                                    <div class="input-group">
                                        {{--                                            <span class="input-group-addon addon-secondary left"><i class="la la-suitcase"></i></span>--}}
                                        <select id="select-servico" name="status" class="form-control boostrap-select-custom" required>
                                            @FOREACH($status_avaliacao as $key => $status)
                                                <option value="{{ $key }}">{{ $status }}</option>
                                            @ENDFOREACH
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-xl-3 mb-3">
                                    <label for="nome" class="form-control-label">Avaliação do cliente</label>
                                        <div class="stars-container">
                                            <div class="stars">
                                                <img id="star1" src="{{ asset('images/star0.png') }}">
                                                <img id="star2" src="{{ asset('images/star0.png') }}">
                                                <img id="star3" src="{{ asset('images/star0.png') }}">
                                                <img id="star4" src="{{ asset('images/star0.png') }}">
                                                <img id="star5" src="{{ asset('images/star0.png') }}">
                                            </div>
                                            <div class="starts-utils" style="display: none;">
                                                <span id="link-star0">{{ asset('images/star0.png') }}</span>
                                                <span id="link-star1">{{ asset('images/star1.png') }}</span>
                                                <input id="star-contador" name="nota">
                                            </div>
                                        </div>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="em-separator separator-dashed"></div>
                            <div class="text-center">
                                <button class="btn btn-gradient-01" type="submit">Finalizar cadastro <i class="la la-angle-right right"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        window.onload = () => {
            AvaliacaoStarsCtrl.init();

            $("#post").submit((event) => {
                if($("#star-contador").val() == '') {
                    event.preventDefault();
                    swal({
                        title: 'Informe a nota da avaliação',
                        icon: 'warning',
                        text: 'Para completar o cadastro informa a nota da avaliação.'
                    })
                }
            })
        };
    </script>
@endsection
