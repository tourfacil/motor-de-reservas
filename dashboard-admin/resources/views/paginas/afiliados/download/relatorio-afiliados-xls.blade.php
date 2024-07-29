<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de afiliados</title>
</head>
<body>
<table>
    <thead>
    <tr>
        <th align="center" valign="center" height="25">#</th>
        <th align="left" valign="center" height="25">Afiliado</th>
        <th align="left" valign="center" height="25">CNPJ</th>
        <th align="center" valign="center" height="25">R$ Vendido</th>
        <th align="center" valign="center" height="25" >R$ Comissão</th>
        <th align="center" valign="center" height="25" >Quantidade</th>
    </tr>
    </thead>
    <tbody>
    @foreach($afiliados as $nome => $afiliado)
        <tr>
            <td align="center">{{ $loop->iteration }}</td>
            <td align="left">{{ $nome }}</td>
            <td align="left">{{ $afiliado['afiliado']['cnpj'] }}</td>
            <td align="center" data-format="{{ \TourFacil\Core\Enum\ExcelEnum::FORMAT_CURRENCY }}">{{ $afiliado['valor_venda'] }}</td>
            <td align="center" data-format="{{ \TourFacil\Core\Enum\ExcelEnum::FORMAT_CURRENCY }}">{{ $afiliado['valor_comissao'] }}</td>
            <td align="center">{{ $afiliado['quantidade'] }} ingresso(s)</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
