@extends('validador.template.header')

@section('title', "Painel do anunciante")

@section('content')

    <div class="lista-cpf">
        <div id="container" class="large">
            <a href="{{ route('app.validador.index', ['tipo' => $tipo_validacao]) }}" class="m-button-voltar"><i class="fas fa-arrow-left"></i> Voltar</a>
            <h2>{{ $quantidade_reservas }} resultados encontrados</h2>
            <table>
                <thead>
                <tr>
                    <th>#</th>
                    <th>Serviço adquirido</th>
                    <th class="text-center">Status </th>
                    <th class="text-center">Detalhes</th>
                </tr>
                </thead>
                <tbody>
                @foreach($reservas as $reserva)
                    <tr>
                        <td width="10" class="text-center">{{ $loop->iteration }}</td>
                        <td>
                            <strong>({{ $reserva->quantidade }}x) {{ $reserva->servico->nome }}</strong>
                        </td>
                        <td class="text-center">
                            <strong class="info text-{{ $reserva->cor_status }}">{{ $reserva->status_reserva }}</strong>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('app.validador.view', ['ticket' => $reserva->voucher, 'validacao' => $validacao_codigo]) }}" target="_blank" class="m-button">
                                Detalhes <i class="fas fa-chevron-right"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection
