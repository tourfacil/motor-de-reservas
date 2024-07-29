<!doctype html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
</head>

<body>
    <table>
        <thead>
            <tr>
                <th>VENDA</th>
                <th>UTILIZAÇÃO</th>
                <th>COD_RESERVA</th>
                <th>COD_PEDIDO</th>
                <th>VALOR_VENDA</th>
                <th>VALOR_NET</th>
                <th>STATUS</th>
                <th>SERVICO</th>
                <th>FORNECEDOR</th>
                <th>DESTINO</th>
                <th>CATEGORIA</th>
                <th>CLIENTE</th>
                <th>DOCUMENTO</th>
                <th>E-MAIL</th>
                <th>TELEFONE</th>
                <th>NASCIMENTO</th>
                <th>ESTADO</th>
                <th>CIDADE</th>
                <th>BAIRRO</th>
                <th>NUMERO</th>
                <th>CEP</th>
                <th>BLOQUEIO</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reservas as $reserva)
                <tr>
                    <td>{{ $reserva->created_at->format('d/m/Y') }}</td>
                    <td>{{ $reserva->agendaDataServico->data->format('d/m/Y') }}</td>
                    <td>{{ $reserva->voucher }}</td>
                    <td>{{ $reserva->pedido->codigo }}</td>
                    <td>{{ $reserva->valor_total }}</td>
                    <td>{{ $reserva->valor_net }}</td>
                    <td>{{ $reserva->status }}</td>
                    <td>{{ $reserva->servico->nome }}</td>
                    <td>{{ $reserva->servico->fornecedor->nome_fantasia }}</td>
                    <td>{{ $reserva->servico->destino->nome }}</td>
                    <td>{{ $reserva->servico->categorias->first()->nome ?? '' }}</td>
                    <td>{{ $reserva->pedido->cliente->nome }}</td>
                    <td>{{ $reserva->pedido->cliente->cpf }}</td>
                    <td>{{ $reserva->pedido->cliente->email }}</td>
                    <td>{{ $reserva->pedido->cliente->telefone }}</td>
                    <td>{{ $reserva->pedido->cliente->nascimento->format('d/m/Y') }}</td>
                    <td>{{ $reserva->pedido->cliente->endereco->estado ?? '' }}</td>
                    <td>{{ $reserva->pedido->cliente->endereco->cidade ?? '' }}</td>
                    <td>{{ $reserva->pedido->cliente->endereco->bairro ?? '' }}</td>
                    <td>{{ $reserva->pedido->cliente->endereco->numero ?? '' }}</td>
                    <td>{{ $reserva->pedido->cliente->endereco->cep ?? '' }}</td>
                    <td>{{ $reserva->bloqueio_consumido }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
