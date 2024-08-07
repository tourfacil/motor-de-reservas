<table>
    <thead>
    <tr>
        <th align="center" valign="center" height="25" bgcolor="d9d9d9">Reserva</th>
        <th align="left" valign="center" height="25" bgcolor="d9d9d9">Comprador</th>
        <th align="center" valign="center" height="25" bgcolor="d9d9d9">Documento</th>
        <th align="center" valign="center" height="25" bgcolor="d9d9d9">Data Venda</th>
        <th align="center" valign="center" height="25" bgcolor="d9d9d9">Data Escolhida</th>
        <th align="left" valign="center" height="25" bgcolor="d9d9d9">Serviço</th>
        <th align="left" valign="center" height="25" bgcolor="d9d9d9">Bloqueio</th>
        <th align="left" valign="center" height="25" bgcolor="d9d9d9">Variações vendidas</th>
        <th align="center" valign="center" height="25" bgcolor="d9d9d9">Valor Venda</th>
        <th align="center" valign="center" height="25" bgcolor="d9d9d9">Tarifa NET</th>
    </tr>
    </thead>
    <tbody>
    @foreach($reservas as $reserva)
        <tr>
            <td align="center">#{{ $reserva->voucher }}</td>
            <td align="left">{{ $reserva->pedido->cliente->nome }}</td>
            <td align="center">{{ $reserva->pedido->cliente->cpf }}</td>
            <td align="center">{{ $reserva->created_at->format('d/m/Y H:i') }}</td>
            <td align="center">{{ $reserva->agendaDataServico->data->format('d/m/Y') }}</td>
            <td align="left">{{ $reserva->servico->nome }}</td>
            <td align="center">{{ $reserva->bloqueio_consumido }}</td>
            <td align="left">
                @foreach ($reserva->quantidadeReserva as $quantidade)
                    {{ $quantidade->quantidade }}x {{ $quantidade->variacaoServico->nome }}
                @endforeach
            </td>
            <td align="center" data-format="{{ \PortalGramado\Admin\Enum\ExcelEnum::FORMAT_CURRENCY }}">{{ $reserva->valor_total }}</td>
            <td align="center" data-format="{{ \PortalGramado\Admin\Enum\ExcelEnum::FORMAT_CURRENCY }}">{{ $reserva->valor_net }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
