@php
    use Carbon\Carbon;
@endphp
        <!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ingressos Vendidos {{ $afiliado->nome_fantasia }}</title>
    {{-- Import application CSS --}}
    @if($inline_pdf)
        <link rel="stylesheet" href="{{ public_path('css/app.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @endif
    {{-- Google fonts --}}
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700" rel="stylesheet">
    {{-- Favicon --}}
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}" type="image/png">
</head>
<body class="view_pdf">
<div class="page-content d-print-inline">
    <nav>
        <div class="row d-flex align-items-center pb-2">
            <div class="col-8">
                <h2 class="title">{{ $afiliado->nome_fantasia }}</h2>
                <p class="m-0">Relatório de afiliado</p>
            </div>
            <div class="col-4 pl-5">
                @if($inline_pdf && (env('APP_ENV') != "local"))
                    <img src="{{ public_path($logo_path) }}" class="embed-responsive">
                @else
                    <img src="{{ asset($logo_path) }}" class="embed-responsive">
                @endif
            </div>
        </div>
    </nav>
    <hr>
    <header class="pt-2 pb-3">
        <div class="row d-flex align-items-center text-centers">
            <div class="col-4">
                @IF($tipo_operacao == "UTILIZACAO")
                    <p class="m-0 text-primary">Período de utilização</p>
                @ELSE
                    <p class="m-0 text-primary">Período de vendas</p>
                @ENDIF
                <h4 class="font-weight-bold">{{ $data_inicio->format('d/m/Y') }} até {{ $data_final->format('d/m/Y') }}</h4>
            </div>
            <div class="col-2">
                <p class="mt-0 mb-2">Total vendido</p>
                <h4 class="font-weight-bold">R$ {{ formataValor($total_vendido ?? 0) }}</h4>
            </div>
            <div class="col-2">
                <p class="mt-0 mb-2">Total Comissão</p>
                <h4 class="font-weight-bold">R$ {{ formataValor($total_comissionado) }}</h4>
            </div>
            <div class="col-4 pl-4">
                <p class="mt-0 mb-2">Quantidade</p>
                <h4 class="font-weight-bold">{{ $quantidade_reservas }} reserva(s)</h4>
            </div>
        </div>
    </header>
    <div class="table-list mt-4">
        <table class="table table-border-bottom mb-0">
            <thead>
            <tr>
                <th>#</th>
                <th>Serviço</th>
                <th class="text-center">Categoria</th>
                @IF($tipo_operacao == "UTILIZACAO")
                    <th class="text-center">Data Utilização</th>
                @ELSE
                    <th class="text-center">Data Venda</th>
                @ENDIF
                <th class="text-center">Valor Total R$</th>
                <th class="text-center">Comissão %</th>
                <th class="text-center">Comissão R$</th>
            </tr>
            </thead>
            <tbody>
            @foreach($reservas as $reserva)
                <tr>
                    <td>
                        #{{ $reserva->voucher }} <br>
                        <small>{{ $reserva->created_at->format('d/m/Y H:i') }}</small>
                    </td>
                    <td>
                        {{ $reserva->servico->nome }} <br>
                        @foreach ($reserva->quantidadeReserva as $quantidade)
                            <small>{{ $quantidade->quantidade }}x {{ $quantidade->variacaoServico->nome }}</small>
                        @endforeach
                    </td>
                    <td class="text-center">{{ $reserva->servico->categorias->first()->nome }}</td>
                    <td class="text-center">
                        @IF($tipo_operacao == "UTILIZACAO")
                            {{$reserva->agendaDataServico->data->format('d/m/Y')}}
                        @ELSE
                            {{$reserva->created_at->format('d/m/Y')}}
                        @ENDIF
                    </td>
                    <td class="text-center">R$ {{formataValor($reserva->valor_total)}}</td>
                    <td class="text-center">{{ \TourFacil\Core\Services\AfiliadoService::getComissaoPercentual($reserva) }}</td>
                    <td>R$ {{formataValor( \TourFacil\Core\Services\AfiliadoService::getComissaoAfiliado($reserva) )}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
