<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Relatório Afiliados</title>
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
                <h2 class="title">Relatório de afiliados</h2>
                <p class="m-0">Afiliados que possuem vendas no período</p>
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
                <p class="mt-0 mb-2">Período de {{ ($tipo_data == 'VENDA') ? "venda" : "utilização" }}</p>
                <h4 class="font-weight-bold">{{ $inicio->format('d/m/Y') }} até {{ $final->format('d/m/Y') }}</h4>
            </div>
            <div class="col-2">
                <p class="mt-0 mb-2">Total vendido</p>
                <h4 class="font-weight-bold">R$ {{ formataValor($total_vendido ?? 0) }}</h4>
            </div>
            <div class="col-2">
                <p class="mt-0 mb-2">Total Comissão</p>
                <h4 class="font-weight-bold">R$ {{ formataValor($total_comissionado ?? 0) }}</h4>
            </div>
            <div class="col-4 pl-4">
                <p class="mt-0 mb-2">Ingressos</p>
                <h4 class="font-weight-bold">{{ $total_quantidade ?? "0" }} ingresso(s)</h4>
            </div>
        </div>
    </header>
    <div class="table-list mt-4">
        <table class="table table-border-bottom mb-0">
            <thead>
            <tr>
                <th>#</th>
                <th>Afiliado</th>
                <th>CNPJ</th>
                <th class="text-center">R$ Vendido</th>
                <th class="text-center">R$ Comissão</th>
                <th class="text-center">{{ ($tipo_data == 'VENDA') ? "Vendido" : "Utilizado" }}</th>
            </tr>
            </thead>
            <tbody>
            @forelse($afiliados as $nome => $afiliado)
                <tr>
                    <td width="10" class="text-center pt-3 pb-3">{{ $loop->iteration }}</td>
                    <td>{{ $nome }}</td>
                    <td>CNPJ: {{ $afiliado['afiliado']['cnpj'] }}</td>
                    <td class="text-center font-weight-bold">R$ {{ formataValor($afiliado['valor_venda']) }}</td>
                    <td class="text-center">R$ {{ formataValor($afiliado['valor_comissao']) }}</td>
                    <td class="text-center">{{ $afiliado['quantidade'] }} ingresso(s)</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center pt-3 pb-3">Sem dados para o período</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
