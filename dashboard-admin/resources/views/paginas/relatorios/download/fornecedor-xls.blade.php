<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>{{ ($somente_autenticados == 'true') ? "Autenticados" : "Vendidos" }} - {{ $canal_venda->nome }}</title>
</head>
<body>
<table>
    <thead>
    <tr>
        <th align="center" valign="center" height="25" bgcolor="d9d9d9">#</th>
        <th align="left" valign="center" height="25" bgcolor="d9d9d9">Fornecedor</th>
        <th align="left" valign="center" height="25" bgcolor="d9d9d9">CNPJ</th>
        <th align="center" valign="center" height="25" bgcolor="d9d9d9">R$ Vendido</th>
        <th align="center" valign="center" height="25" bgcolor="d9d9d9">R$ Tarifa NET</th>
        <th align="center" valign="center" height="25" bgcolor="d9d9d9">{{ ($somente_autenticados == 'true') ? "Validado" : "Vendido" }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($fornecedores as $fornecedor)
        <tr>
            <td align="center">{{ $loop->iteration }}</td>
            <td align="left">{{ $fornecedor->nome_fantasia }}</td>
            <td align="left">{{ $fornecedor->cnpj }}</td>
            <td align="center" data-format="{{ \TourFacil\Core\Enum\ExcelEnum::FORMAT_CURRENCY }}">{{ $fornecedor->vendido }}</td>
            <td align="center" data-format="{{ \TourFacil\Core\Enum\ExcelEnum::FORMAT_CURRENCY }}">{{ $fornecedor->tarifa_net }}</td>
            <td align="center">{{ $fornecedor->quantidade }} ingresso(s)</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
