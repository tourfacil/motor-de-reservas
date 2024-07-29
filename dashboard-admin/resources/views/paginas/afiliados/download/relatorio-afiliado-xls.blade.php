@php
    use Carbon\Carbon;
@endphp

<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório</title>
</head>
<body>
<table>
    <thead>
    <tr>
        <th align="center" valign="center" height="25">#</th>
        <th align="left" valign="center" height="25">Serviço</th>
        <th align="left" valign="center" height="25">Categoria</th>
        <th align="center" valign="center" height="25">Data Venda</th>
        <th align="center" valign="center" height="25">Valor Total R$</th>
        <th align="center" valign="center" height="25">Comissão %</th>
        <th align="center" valign="center" height="25">Comissão R$</th>
    </tr>
    </thead>
    <tbody>
    @foreach($reservas as $reserva)
        <tr>
            <td align="center">{{ $reserva->voucher }}</td>
            <td align="left">{{ $reserva->servico->nome }}</td>
            <td align="left">{{$reserva->servico->categorias->first()->nome}}</td>
            <td align="center">{{Carbon::parse($reserva->created_at)->format('d/m/Y')}}</td>
            <td align="center" data-format="{{ \TourFacil\Core\Enum\ExcelEnum::FORMAT_CURRENCY }}">{{$reserva->valor_total}}</td>
            <td align="center">{{\TourFacil\Core\Services\AfiliadoService::getComissaoPercentual($reserva)}}</td>
            <td align="center" data-format="{{ \TourFacil\Core\Enum\ExcelEnum::FORMAT_CURRENCY }}">{{ \TourFacil\Core\Services\AfiliadoService::getComissaoAfiliado($reserva) }} </td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
