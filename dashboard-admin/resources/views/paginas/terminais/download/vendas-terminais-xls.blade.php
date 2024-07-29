@php($total_net = 0)
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Vendas - {{ mesPT($data_inicio->month) }} de {{ $data_final->year }}</title>
</head>
<body>
<table>
    <thead>
    <tr>
        <th align="center" valign="center" height="25" bgcolor="d9d9d9">#</th>
        <th align="left" valign="center" height="25" bgcolor="d9d9d9">Terminal de venda</th>
        <th align="center" valign="center" height="25" bgcolor="d9d9d9">Quantidade</th>
        <th align="center" valign="center" height="25" bgcolor="d9d9d9">Comissão</th>
        <th align="center" valign="center" height="25" bgcolor="d9d9d9">Tarifa NET</th>
        <th align="center" valign="center" height="25" bgcolor="d9d9d9">Valor vendido</th>
    </tr>
    </thead>
    <tbody>
    @foreach($relatorio as $venda_terminal)
        @php($total_net += $venda_terminal->valor_net)
        <tr>
            <td align="center">{{ $loop->iteration }}</td>
            <td align="left">{{ $venda_terminal->terminal->nome }}</td>
            <td align="center">{{ $venda_terminal->ingressos }} ingresso(s)</td>
            <td align="center">{{ formataValor($venda_terminal->comissao) }}</td>
            <td align="center">{{ formataValor($venda_terminal->valor_net) }}</td>
            <td align="center">{{ formataValor($venda_terminal->vendido) }}</td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <td align="right" valign="center"></td>
        <td align="right" valign="center">Valores finais:</td>
        <td align="center" valign="center"><strong>{{ $total_ingressos }} ingresso(s)</strong></td>
        <td align="center" valign="center" data-format="{{ \TourFacil\Core\Enum\ExcelEnum::FORMAT_CURRENCY }}"><strong>{{ $total_comissao }}</strong></td>
        <td align="center" valign="center" data-format="{{ \TourFacil\Core\Enum\ExcelEnum::FORMAT_CURRENCY }}"><strong>{{ $total_net }}</strong></td>
        <td align="center" valign="center" data-format="{{ \TourFacil\Core\Enum\ExcelEnum::FORMAT_CURRENCY }}"><strong>{{ $total_vendido }}</strong></td>
    </tr>
    </tfoot>
</table>
</body>
</html>
