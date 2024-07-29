@php($total_net = 0)
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Vendas {{ mesPT($data_inicio->month) }}</title>
</head>
<body>
<table>
    <thead>
    <tr>
        <th align="center" valign="center" height="25" bgcolor="d9d9d9">#</th>
        <th align="center" valign="center" height="25" bgcolor="d9d9d9">Reserva</th>
        <th align="left" valign="center" height="25" bgcolor="d9d9d9">Serviço</th>
        <th align="left" valign="center" height="25" bgcolor="d9d9d9">Quantidade</th>
        <th align="center" valign="center" height="25" bgcolor="d9d9d9">Utilização</th>
        <th align="center" valign="center" height="25" bgcolor="d9d9d9">R$ Venda</th>
        <th align="center" valign="center" height="25" bgcolor="d9d9d9">Tarifa NET</th>
        <th align="center" valign="center" height="25" bgcolor="d9d9d9">Data da venda</th>
        <th align="center" valign="center" height="25" bgcolor="d9d9d9">Comissão</th>
        <th align="center" valign="center" height="25" bgcolor="d9d9d9">Porcentagem</th>
    </tr>
    </thead>
    <tbody>
    @forelse($terminal->comissaoTerminal as $comissao)
        @php($total_net += $comissao->reservaPedido->valor_net)
        <tr>
            <td align="center">{{ $loop->iteration }}</td>
            <td align="center">#{{ $comissao->reservaPedido->voucher }}</td>
            <td>{{ $comissao->reservaPedido->servico->nome }}</td>
            <td>
                @foreach($comissao->reservaPedido->quantidadeReserva as $quantidade)
                    {{ $quantidade->quantidade }}x {{ $quantidade->variacaoServico->nome }}
                @endforeach
            </td>
            <td align="center">{{ $comissao->reservaPedido->agendaDataServico->data->format('d/m/Y') }}</td>
            <td align="center" data-format="{{ \TourFacil\Core\Enum\ExcelEnum::FORMAT_CURRENCY }}">{{ $comissao->reservaPedido->valor_total }}</td>
            <td align="center" data-format="{{ \TourFacil\Core\Enum\ExcelEnum::FORMAT_CURRENCY }}">{{ $comissao->reservaPedido->valor_net }}</td>
            <td align="center">{{ $comissao->reservaPedido->created_at->format('d/m/Y') }}</td>
            <td align="center" data-format="{{ \TourFacil\Core\Enum\ExcelEnum::FORMAT_CURRENCY }}">{{ $comissao->comissao }}</td>
            <td align="center">{{ porcentagemComissao($comissao->reservaPedido->valor_total, $comissao->comissao) }}%</td>
        </tr>
    @empty
        <tr>
            <td colspan="10" align="center">Sem vendas para o período</td>
        </tr>
    @endforelse
    </tbody>
    <tfoot>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td align="center"><strong>Valores finais:</strong></td>
        <td align="center" class="font-weight-bold"><strong>R$ {{ formataValor($valores_venda->vendido ?? 0) }}</strong></td>
        <td align="center" class="font-weight-bold"><strong>R$ {{ formataValor($total_net) }}</strong></td>
        <td></td>
        <td align="center" class="font-weight-bold"><strong>R$ {{ formataValor($valores_venda->comissao ?? 0) }}</strong></td>
    </tr>
    </tfoot>
</table>
</body>
</html>
