<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Faturas pagamento pendente</title>
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
<body class="view_pdf" style="padding: 50px;">
<div style="margin: 0px;">
    <nav>
        <div class="row d-flex align-items-center pb-2">
            <div class="col-8">
                <h2 class="title">Faturas para pagamento</h2>
                {{-- <p class="m-0">Fatura Prevista</p> --}}
            </div>
            <div class="col-4 pl-5">
                @if($inline_pdf && (env('APP_ENV') != "local"))
                    <img src="{{ public_path($logo_path) }}" class="embed-responsive" alt="Logo">
                @else
                    <img src="{{ asset($logo_path) }}" class="embed-responsive" alt="Logo">
                @endif
            </div>
        </div>
    </nav>
    <hr>
    <header class="pt-2 pb-3">
        <div class="row d-flex align-items-center text-centers">
            <div class="col-4">
                <p class="mt-0 mb-2">Descrição</p>
                <h4 class="font-weight-bold">Faturas com pagamento pendente</h4>
            </div>
            <div class="col-2">
                <p class="mt-0 mb-2">Valor pendente</p>
                <h4 class="font-weight-bold">R$ {{ formataValor($faturas->sum('valor')) }}</h4>
            </div>
            <div class="col-2">
                <p class="mt-0 mb-2">Quantidade vendida</p>
                <h4 class="font-weight-bold">{{ $faturas->sum('quantidade') }} Ingresso(s)</h4>
            </div>
            <div class="col-4 pl-4">
                <p class="mt-0 mb-2">Quantidade reservas</p>
                <h4 class="font-weight-bold">{{ $faturas->count() }} Reserva(s)</h4>
            </div>
        </div>
    </header>
    <div class="table-list mt-4">
        <table class="table table-border-bottom mb-0">
            <thead>
            <tr>
                <th>ID</th>
                <th>Fornecedor</th>
                <th>Data Início</th>
                <th>Data Final</th>
                <th>Vencimento</th>
                <th>Tipo</th>
                <th>Tipo Período</th>
                <th class="text-center">Valor(R$)</th>
            </tr>
            </thead>
            <tbody>
                @forelse($faturas as $fatura)
                    <tr>
                        <td>#{{ $fatura->id }}</td>
                        <td>{{ $fatura->fornecedor->nome_fantasia }}</td>
                        <td>{{ $fatura->inicio->format('d/m/Y') }}</td>
                        <td>{{ $fatura->final->format('d/m/Y') }}</td>
                        <td>{{ $fatura->data_pagamento->format('d/m/Y') }}</td>
                        <td>{{ $fatura->tipo }}</td>
                        <td>{{ $fatura->tipo_periodo }}</td>
                        <td class="text-center">R${{ formataValor($fatura->valor) }}</td>
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
