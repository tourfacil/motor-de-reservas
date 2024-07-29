<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }} - {{ $canal_venda->nome }}</title>
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
                <h2 class="title">{{ $title }}</h2>
                <p class="m-0">Fornecedores que {{ ($somente_autenticados == 'true') ? "validaram ingressos" : "possuem vendas" }} no período</p>
            </div>
            <div class="col-4 pl-5">
                @if($inline_pdf && (env('APP_ENV') != "local"))
                    <img src="{{ public_path($logo_path) }}" class="embed-responsive" alt="Logo {{ $canal_venda->nome }}">
                @else
                    <img src="{{ asset($logo_path) }}" class="embed-responsive" alt="Logo {{ $canal_venda->nome }}">
                @endif
            </div>
        </div>
    </nav>
    <hr>
    <header class="pt-2 pb-3">
        <div class="row d-flex align-items-center text-centers">
            <div class="col-4">
                <p class="mt-0 mb-2">Período de {{ ($somente_autenticados == 'true') ? "autenticação" : "venda" }}</p>
                <h4 class="font-weight-bold">{{ $pp_start->format('d/m/Y') }} até {{ $pp_end->format('d/m/Y') }}</h4>
            </div>
            <div class="col-2">
                <p class="mt-0 mb-2">Total vendido</p>
                <h4 class="font-weight-bold">R$ {{ formataValor($total_vendido ?? 0) }}</h4>
            </div>
            <div class="col-2">
                <p class="mt-0 mb-2">Total NET</p>
                <h4 class="font-weight-bold">R$ {{ formataValor($total_net ?? 0) }}</h4>
            </div>
            <div class="col-4 pl-4">
                <p class="mt-0 mb-2">Ingressos {{ ($somente_autenticados == 'true') ? "autenticados" : "vendidos" }}</p>
                <h4 class="font-weight-bold">{{ $total_quantidade ?? "0" }} ingresso(s)</h4>
            </div>
        </div>
    </header>
    <div class="table-list mt-4">
        <table class="table table-border-bottom mb-0">
            <thead>
            <tr>
                <th>#</th>
                <th>Fornecedor</th>
                <th class="text-center">R$ Vendido</th>
                <th class="text-center">R$ Tarifa NET</th>
                <th class="text-center">{{ ($somente_autenticados == 'true') ? "Validado" : "Vendido" }}</th>
            </tr>
            </thead>
            <tbody>
            @forelse($fornecedores as $fornecedor)
                <tr>
                    <td width="10" class="text-center pt-3 pb-3">{{ $loop->iteration }}</td>
                    <td>
                        {{ $fornecedor->nome_fantasia }} <br>
                        <span class="">CNPJ: {{ $fornecedor->cnpj }}</span>
                    </td>
                    <td class="text-center font-weight-bold">R$ {{ formataValor($fornecedor->vendido) }}</td>
                    <td class="text-center">R$ {{ formataValor($fornecedor->tarifa_net) }}</td>
                    <td class="text-center">{{ $fornecedor->quantidade }} ingresso(s)</td>
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
