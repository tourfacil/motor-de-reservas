<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
</head>
<body>
<table>
    <thead>
    <tr>
        <th>UTILIZAÇÃO</th>
        <th>VENDA</th>
        <th>COD_PEDIDO</th>
        <th>COD_RESERVA</th>
        <th>VALOR_VENDA</th>
        <th>VALOR_NET</th>
        <th>QUANTIDADE</th>
        <th>STATUS</th>
        <th>SERVICO</th>
        <th>VENDEDOR</th>
    </tr>
    </thead>
    <tbody>
    @foreach($reservas as $reserva)
        <tr>
            <td>{{ $reserva->agendaDataServico->data->format('d/m/Y') }}</td>
            <td>{{ $reserva->created_at->format('d/m/Y H:i') }}</td>
            <td>#{{ $reserva->pedido->codigo }}</td>
            <td>#{{ $reserva->voucher }}</td>
            <td>{{ $reserva->valor_total }}</td>
            <td>{{ $reserva->valor_net }}</td>
            <td>{{ $reserva->quantidade }}</td>
            <td>{{ $reserva->status }}</td>
            <td>{{ $reserva->servico->nome }}</td>
            <td>{{ $reserva->vendedor->nome_fantasia ?? 'Tour Fácil' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
