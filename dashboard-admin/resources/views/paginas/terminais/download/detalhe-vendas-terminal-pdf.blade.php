@php($total_net = 0)
<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Vendas {{ $terminal->nome }} - {{ mesPT($data_inicio->month) }} de {{ $data_final->year }}</title>
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
                <h2 class="title">Vendas Terminal {{ $terminal->nome }}</h2>
                <p class="m-0">{{ $url_terminais }}</p>
            </div>
            <div class="col-4 pl-5">
                @if($inline_pdf && (env('APP_ENV') != "local"))
                    <img src="{{ public_path('images/logos/logo_tourfacil.svg') }}" class="embed-responsive" alt="Logo TourFacil">
                @else
                    <img src="{{ asset('images/logos/logo_tourfacil.svg') }}" class="embed-responsive" alt="Logo TourFacil">
                @endif
            </div>
        </div>
    </nav>
    <hr>
    <header class="pt-2 pb-3">
        <div class="row d-flex align-items-center text-centers">
            <div class="col-3">
                <p class="mt-0 mb-2">Período das vendas</p>
                <h4 class="font-weight-bold">{{ mesPT($data_inicio->month) }} de {{ $data_final->year }}</h4>
            </div>
            <div class="col-3">
                <p class="mt-0 mb-2">Total vendido</p>
                <h4 class="font-weight-bold">R$ {{ formataValor($valores_venda->vendido ?? 0) }}</h4>
            </div>
            <div class="col-3">
                <p class="mt-0 mb-2">Total comissão</p>
                <h4 class="font-weight-bold">R$ {{ formataValor($valores_venda->comissao ?? 0) }}</h4>
            </div>
            <div class="col-3">
                <p class="mt-0 mb-2">Ingressos vendidos</p>
                <h4 class="font-weight-bold">{{ $valores_venda->ingressos ?? "0" }} ingresso(s)</h4>
            </div>
        </div>
    </header>
    <div class="table-list mt-4">
        <table class="table table-border-bottom mb-0">
            <thead>
            <tr>
                <th>#</th>
                <th>Reserva</th>
                <th>Serviço vendido</th>
                <th class="text-center">Utilização</th>
                <th class="text-center">R$ Venda</th>
                <th class="text-center">Data da venda</th>
                <th class="text-center">Comissão</th>
            </tr>
            </thead>
            <tbody>
            @forelse($terminal->comissaoTerminal as $comissao)
                <tr>
                    <td width="10" class="text-center">{{ $loop->iteration }}</td>
                    <td>#{{ $comissao->reservaPedido->voucher }}</td>
                    <td>
                        <span class="d-inline">
                            {{ $comissao->reservaPedido->servico->nome }} <br>
                            @foreach ($comissao->reservaPedido->quantidadeReserva as $quantidade)
                                <small>{{ $quantidade->quantidade }}x {{ $quantidade->variacaoServico->nome }}</small>
                            @endforeach
                        </span>
                    </td>
                    <td class="text-center">{{ $comissao->reservaPedido->agendaDataServico->data->format('d/m/Y') }}</td>
                    <td class="text-center">R$ {{ formataValor($comissao->reservaPedido->valor_total) }}</td>
                    <td class="text-center">{{ $comissao->reservaPedido->created_at->format('d/m/Y') }}</td>
                    <td class="text-center">
                        <span class="font-weight-bold">R$ {{ formataValor($comissao->comissao) }}</span>
                        <sup>{{ porcentagemComissao($comissao->reservaPedido->valor_total, $comissao->comissao) }}%</sup>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Sem vendas para o período</td>
                </tr>
            @endforelse
            </tbody>
            <tfoot>
            <tr>
                <td colspan="4" class="text-right">Valores finais:</td>
                <td class="text-center font-weight-bold">R$ {{ formataValor($valores_venda->vendido ?? 0) }}</td>
                <td colspan="1"></td>
                <td class="text-center font-weight-bold">R$ {{ formataValor($valores_venda->comissao ?? 0) }}</td>
            </tr>
            </tfoot>
        </table>
    </div>
</div>
</body>
</html>
