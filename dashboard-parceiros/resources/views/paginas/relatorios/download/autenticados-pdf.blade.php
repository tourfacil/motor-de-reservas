@php($total_net = 0)
<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ingressos autenticados - Grupo Portal Gramado</title>
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
                <h2 class="title">{{ $fornecedor->nome_fantasia }}</h2>
                <p class="m-0">Listagem das reservas autenticadas no periodo</p>
            </div>
            <div class="col-4 pl-5">
                @if($inline_pdf && (env('APP_ENV') != "local"))
                    <img src="{{ public_path('images/logo-preto.svg') }}" class="embed-responsive" alt="Logo Portal Gramado">
                @else
                    <img src="{{ asset('images/logo-preto.svg') }}" class="embed-responsive" alt="Logo Portal Gramado">
                @endif
            </div>
        </div>
    </nav>
    <hr>
    <header class="pt-2 pb-3">
        <div class="row d-flex align-items-center text-centers">
            <div class="col-4">
                <p class="mt-0 mb-2">Período de autenticação</p>
                <h4 class="font-weight-bold">{{ $pp_start->format('d/m/Y') }} até {{ $pp_end->format('d/m/Y') }}</h4>
            </div>
            <div class="col-2">
                <p class="mt-0 mb-2">Total vendido NET</p>
                <h4 class="font-weight-bold">R$ {{ formataValor($tarifa_net ?? 0) }}</h4>
            </div>
            <div class="col-2">
                <p class="mt-0 mb-2">Quantidade</p>
                <h4 class="font-weight-bold">{{ $vendas ?? 0 }} reserva(s)</h4>
            </div>
            <div class="col-4 pl-4">
                <p class="mt-0 mb-2">Autenticado</p>
                <h4 class="font-weight-bold">{{ $quantidade ?? "0" }} ingresso(s)</h4>
            </div>
        </div>
    </header>
    <div class="table-list mt-4">
        <table class="table table-border-bottom mb-0">
            <thead>
            <tr>
                <th>Reserva</th>
                <th>Serviço utilizado</th>
                <th class="text-center">Tarifa NET</th>
                <th class="text-center">Agendado</th>
                <th class="text-center">Validado</th>
            </tr>
            </thead>
            <tbody>
            @forelse($reservas as $reserva)
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
                    <td class="text-center">R$ {{ formataValor($reserva->valor_net) }}</td>
                    <td class="text-center">{{ $reserva->agendaDataServico->data->format('d/m/Y') }}</td>
                    <td class="text-center">{{ $reserva->validacao->validado->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Sem vendas para o período</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
