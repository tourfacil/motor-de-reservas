@extends('validador.template.header')

@section('title', "Painel do anunciante")

@section('content')

    <div class="detalhes-ticket">
        <div id="container" class="large">

            @if($reserva_valida)
                <div class="aviso">
                    <strong>Ingresso válido</strong>
                    <span>#{{ $reserva->voucher }}</span>
                </div>
            @elseif($reserva_cancelada)
                <div class="aviso red">
                    <strong>Este ticket está cancelado!</strong>
                </div>
            @elseif($reserva_utilizada)
                <div class="aviso red">
                    <strong>Ticket validado no dia {{ $reserva->validacao->validado->format('d/m/Y H:i') }}</strong>
                </div>
            @elseif($reserva_pendente)
                <div class="aviso orange">
                    <strong>Este ticket não teve o pagamento autorizado</strong>
                </div>
            @endif

            <span>Serviço adquirido:</span>
            <h2>{{ $reserva->servico->nome }}</h2>

            <span>Data de utilização:</span>
            <h2>
                {{ $reserva->agendaDataServico->data->format('d/m/Y') }}
                <small>- {{ dataExtenso($reserva->agendaDataServico->data) }}</small>
            </h2>

            <span>Quantidade adquirida:</span>
            <ul class="quantidade">
                @foreach($reserva->quantidadeReserva as $quantidade)
                    <li>{{ $quantidade->quantidade }}x {{ $quantidade->variacaoServico->nome }}</li>
                @endforeach
            </ul>
            <div class="center">
                @if($reserva_valida)
                    <form data-validate-ajax action="{{ route('app.validador.validar') }}">
                        {{ method_field("PUT") }}
                        <input type="hidden" name="tipo_validacao" value="{{ $tipo_validacao }}">
                        <input type="hidden" name="reserva" value="{{ $reserva->id }}">
                        <button class="m-button">Autenticar voucher</button>
                    </form>
                    <br>
                @endif
                <a href="{{ route('app.validador.index', ['tipo' => $tipo_validacao]) }}" class="{{ ($reserva_valida)?: "m-button" }}">
                    {{ ($reserva_valida)? "Cancelar" : "Voltar" }}
                </a>
            </div>
            <hr>
            <div class="dados-comprador">
                <p>
                    <span>Comprador:</span>
                    <strong>{{ $reserva->pedido->cliente->nome }}</strong>
                </p>
                <p>
                    <span>Documento:</span>
                    <strong>{{ $reserva->pedido->cliente->cpf }}</strong>
                </p>
                <p>
                    <span>Telefone:</span>
                    <strong>{{ $reserva->pedido->cliente->telefone }}</strong>
                </p>
                <p>
                    <span>E-mail:</span>
                    <strong>{{ $reserva->pedido->cliente->email }}</strong>
                </p>
                <p>
                    <span>Data da compra:</span>
                    <strong>{{ $reserva->pedido->created_at->format('d/m/Y H:i') }}</strong>
                </p>
            </div>
            <br>
            <div class="clear"></div>
        </div>
    </div>

@endsection
