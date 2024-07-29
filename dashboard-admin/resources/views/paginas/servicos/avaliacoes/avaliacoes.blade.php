@extends('template.header')

@section('title', 'Avaliações')

@section('content')


    @php
        function printAvaliacaoHTML($nota) {
            $html = "<div class='stars-container'>
                        <div class='stars'>";

            for($i = 1; $i <= 5; $i++) {
                if($i <= $nota) {
                    $html .= "<img id='star1' src='" . asset('images/star1-24.png') . "'>";
                } else {
                    $html .= "<img id='star1' src='" . asset('images/star0-24.png') . "'>";
                }
            }

            $html .= '</div></div>';

            return $html;
        }
    @endphp

{{--Sistema das estrelas--}}
{{--    <div class="stars-container">--}}
{{--        <div class="stars">--}}
{{--            <img id="star1" src="{{ asset('images/star1.png') }}">--}}
{{--            <img id="star2" src="{{ asset('images/star1.png') }}">--}}
{{--            <img id="star3" src="{{ asset('images/star0.png') }}">--}}
{{--            <img id="star4" src="{{ asset('images/star0.png') }}">--}}
{{--            <img id="star5" src="{{ asset('images/star0.png') }}">--}}
{{--        </div>--}}
{{--        <div class="starts-utils" style="display: none;">--}}
{{--            <span id="link-star0">{{ asset('images/star0.png') }}</span>--}}
{{--            <span id="link-star1">{{ asset('images/star1.png') }}</span>--}}
{{--            <span id="star-contador"></span>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--Sistema das estrelas--}}
<div class="row">
    <div class="page-header">
        <div class="d-flex align-items-center">
            <h2 class="page-header-title text-gradient-01">Avaliações dos serviços</h2>
            <div>{{ Breadcrumbs::render('app.servicos.avaliacoes.index') }}</div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="widget has-shadow">
            <div class="widget-header bordered no-actions d-flex align-items-center">
                <h4 class="mr-auto">Listagem das avaliações dos serviços</h4>
            </div>
            <div class="widget-body">
                <div class="table-responsive no-overflow">
                    <table class="table table-hover table-border-bottom mb-0 data-table table-mobile">
                        <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Serviço</th>
                            <th>Avaliação</th>
                            <th>Status</th>
                            <th>Administração</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($avaliacoes as $avaliacao)
                                <tr>
                                    <td>{{ $avaliacao->nome }}</td>
                                    <td>{{ $avaliacao->servico->nome }}</td>
                                    <td data-toggle="tooltip">{!!   printAvaliacaoHTML($avaliacao->nota) !!}</td>
                                    <td>
                                        <span class="badge-text badge-text-small {{ \TourFacil\Core\Enum\StatusAvaliacaoServicoEnum::CORES_STATUS[$avaliacao->status] }}">{{ $avaliacao->status }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ Route('app.servicos.avaliacoes.edit', $avaliacao->id) }}" class="btn btn-outline-primary">
                                            Administração <i class="la la-edit right"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="float-button">
    <a href="{{ route('app.servicos.avaliacoes.create') }}" class="btn btn-gradient-01 btn-shadow"><i class="ion ion-plus-round"></i></a>
    <p class="float-tooltip">Cadastrar nova avaliação de serviço</p>
</div>

@endsection
