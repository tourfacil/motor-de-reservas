<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $reserva->servico->nome }} #{{ $reserva->voucher }}</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    {{-- Google Font --}}
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500&display=swap" rel="stylesheet">
    @if($inline_pdf)
        <link rel="stylesheet" href="{{ public_path('css/voucher.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('css/voucher.css') }}">
    @endif
    <style>
        @media print {
            .pagebreak { page-break-before: always; } /* page-break-after works, as well */
        }
    </style>
</head>
<body>

@php($has_observacao = (strlen($reserva->servico->observacao_voucher) > 0))
@php($has_regras = (strlen($reserva->servico->regras) > 0))
@php($has_termos = (strlen($reserva->fornecedor->termos) > 0))

<div class="ticket clr">
    <div class="left clr">
        <div class="cell">
            <div class="logo clr">
                @if($inline_pdf && (env('APP_ENV') != "local"))
                    <img src="{{ public_path("images/logo_tourfacil_voucher.svg") }}" alt="Logo">
                @else
                    <img src="{{ asset("images/logo_tourfacil_voucher.svg") }}" alt="Logo">
                @endif
            </div>
            <div class="buyer">
                Dados do comprador: <br>
                {{ $cliente->nome }} <br>
                Documento CPF: {{ $cliente->cpf }}<br>
            </div>

            <div class="info">
                <strong>{{ $reserva->servico->nome }}</strong><br><br>
                Data de utilização: <br>
                <b>{{ dataExtenso($reserva->agendaDataServico->data) }}</b><br>
            </div>

            <div class="code">
                <div>
                    Data da compra: {{ $reserva->created_at->format('d/m/Y') }}
                </div>
                <div class="clr">
                    <div class="bar">
                        <img src="data:image/png;base64,{!! DNS2D::getBarcodePNG($reserva->voucher, "QRCODE", 4, 4) !!}" alt="barcode"/>
                        <span>{{ $reserva->voucher }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="right">
        <div class="">
            <div class="top">
                <h2>{{ $reserva->servico->nome }}</h2>

                <div class="clr">
                    <div class="info">
                        {{ $reserva->servico->localizacao }} <br>
                        Telefones: {{ $reserva->fornecedor->telefone }} <br>
                        Operado por: {{ $reserva->fornecedor->nome_fantasia }} <br>
                    </div>
                </div>
            </div>

            <div class="code-row clr">
                <div class="desc">
                    <p>Quantidade adquirida</p>
                    @foreach($reserva->quantidadeReserva as $quantidade_reserva)
                        <strong>{{ $quantidade_reserva->quantidade }}x - {{ $quantidade_reserva->variacaoServico->nome }}</strong>
                    @endforeach
                </div>

                <div class="clr code">
                    <div class="date">
                        Data utilização <br>
                        <strong>{{ $reserva->agendaDataServico->data->format('d/m/Y') }}</strong>
                    </div>
                    <div class="bar">
                        @if($inline_pdf)
                            <img src="{{ public_path("images/ticket-voucher.png") }}" alt="Ticket">
                        @else
                            <img src="{{ asset("images/ticket-voucher.png") }}" alt="Ticket">
                        @endif
                    </div>
                </div>
            </div>

            <div class="buyer clr">
                <div class="infos">
                    Dados do comprador: <br>
                    {{ Str::limit($cliente->nome, 37) }} <br>
                    Documento CPF: {{ $cliente->cpf }}<br>
                    Telefone: {{ $cliente->telefone }}
                </div>
                <div class="line-code">
                    <img src="data:image/png;base64,{!! DNS1D::getBarcodePNG($reserva->voucher, "C128", 1.7, 50) !!}" alt="barcode"/>
                    <span>{{ $reserva->voucher }}</span>
                </div>
            </div>

            <div class="obs">
                <div class="obs1">
                    <strong>Informações:</strong> <br>
                    {{ $reserva->servico->horario }}
                </div>
            </div>

            <div class="declaration">
                <strong>Declaro</strong> ter utilizado conforme a compra descrita acima. &nbsp;&nbsp;Data ___/___/_____<br>
                Nome: ______________________________________ &nbsp; CPF: _______________________
            </div>
        </div>
    </div>
</div>

<div class="border"></div>

<div class="politicas clr">
    @if($has_regras || $has_termos)
        <div class="left {{ (! $has_observacao) ? "full-width" : "" }}">
            {!! markdown($reserva->servico->regras) !!}
            {!! markdown($reserva->fornecedor->termos) !!}
        </div>
    @endif
    @if($has_observacao)
        <div class="right {{ (! $has_termos && ! $has_regras) ? "full-width" : "" }}">
            {!! markdown($reserva->servico->observacao_voucher) !!}
        </div>
    @endif
</div>

@if(\TourFacil\Core\Services\Integracao\PWI\PWIService::getNumerosPassaporte($reserva) != false)
    <div class="pagebreak"> </div>
    <div style="text-align: center;">
        <h4>PASSAPORTES {{ $reserva->servico->integracao }}</h4>
        @foreach(\TourFacil\Core\Services\Integracao\PWI\PWIService::getNumerosPassaporte($reserva) as $passaporte)
            <div style="padding: 30px;display: flex;border: solid 2px black;border-radius: 15px; align-items: center; font-size: 15px;" >
                <div>
                    <img src="data:image/png;base64,{!! DNS2D::getBarcodePNG($passaporte['passaporte'].$passaporte['digitoPassaporte'] , "QRCODE", 8, 8) !!}" alt="barcode"/>
                </div>
                <div class="obs">
                    <div class="obs1">
                        Este QR-Code é o seu ingresso e dá direito a acessar o parque, sem troca de voucher
                        <br>
                        Número do ingresso: <b>{{ $passaporte['passaporte'].$passaporte['digitoPassaporte'] }}</b>
                    </div>
                </div>
            </div>
            <br>
        @endforeach
    </div>
@endif
</body>
</html>
