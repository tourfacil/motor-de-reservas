<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Fatura-previsao</title>
</head>
<body>
<table>
    <thead>
    <tr>
        <th align="center" valign="center" height="30" bgcolor="#f2a807"><b>Reserva</b></th>
        <th align="left" valign="center" height="50" bgcolor="#f2a807"><b>Comprador</b></th>
        <th align="left" valign="center" height="50" bgcolor="#f2a807"><b>Serviço</b></th>
        <th align="left" valign="center" height="50" bgcolor="#f2a807"><b>Categorias</b></th>
        <th align="center" valign="center" width="10" height="30" bgcolor="#f2a807"><b>Data Venda</b></th>
        <th align="center" valign="center" height="30" bgcolor="#f2a807"><b>Data Utilização</b></th>
        <th align="center" valign="center" height="30" bgcolor="#f2a807"><b>Valor</b></th>
    </tr>
    </thead>
    <tbody>
    @foreach($reservas as $reserva)
        <tr>
            <td align="center">#{{ $reserva->voucher }}</td>
            <td align="left">{{ $reserva->pedido->cliente->nome }}</td>
            <td align="left">{{ $reserva->servico->nome}}</td>
            <td align="left">
                @foreach ($reserva->quantidadeReserva as $quantidade)
                    {{ $quantidade->quantidade }}x {{ $quantidade->variacaoServico->nome }}
                @endforeach
            </td>
            <td align="center">{{ $reserva->created_at->format('d/m/Y H:i') }}</td>
            <td align="center">{{ $reserva->agendaDataServico->data->format('d/m/Y') }}</td>
            <td align="center" data-format="{{ \TourFacil\Core\Enum\ExcelEnum::FORMAT_CURRENCY }}">{{ $reserva->valor_net }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
