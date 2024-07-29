<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Faturas Pendentes</title>
</head>
<body>
<table>
    <thead>
    <tr>
        <th align="center" valign="center" height="30" bgcolor="#f2a807"><b>ID</b></th>
        <th align="left" valign="center" height="50" bgcolor="#f2a807"><b>Fornecedor</b></th>
        <th align="left" valign="center" height="50" bgcolor="#f2a807"><b>Data Início</b></th>
        <th align="left" valign="center" height="50" bgcolor="#f2a807"><b>Data Final</b></th>
        <th align="center" valign="center" width="10" height="30" bgcolor="#f2a807"><b>Vencimento</b></th>
        <th align="center" valign="center" height="30" bgcolor="#f2a807"><b>Tipo</b></th>
        <th align="center" valign="center" height="30" bgcolor="#f2a807"><b>Tipo Período</b></th>
        <th align="center" valign="center" height="30" bgcolor="#f2a807"><b>Valor (R$)</b></th>   
    </tr>
    </thead>
    <tbody>
    @foreach($faturas as $fatura)
        <tr>
            <td align="center">#{{ $fatura->id }}</td>
            <td align="left">{{ $fatura->fornecedor->nome_fantasia }}</td>
            <td align="left">{{ $fatura->inicio }}</td>
            <td align="left">{{ $fatura->final }}</td>
            <td>{{ $fatura->data_pagamento }}</td>
            <td align="center">{{ $fatura->tipo }}</td>
            <td align="center">{{ $fatura->tipo_periodo }}</td>
            <td align="center" data-format="{{ \TourFacil\Core\Enum\ExcelEnum::FORMAT_CURRENCY }}">{{ $fatura->valor }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
