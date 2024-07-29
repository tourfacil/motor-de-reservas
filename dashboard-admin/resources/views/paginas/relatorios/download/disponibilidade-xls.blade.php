<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
</head>
<body>
<table>
    <thead>
    <tr>
        <th>SERVICO</th>
        <th>FORNECEDOR</th>
        <th>DATA</th>
        <th>DISPONIVEL</th>
        <th>CONSUMIDO</th>
        <th>SALDO</th>
        <th>DESTINO</th>
    </tr>
    </thead>
    <tbody>
        @foreach($servicos as $servico)
            @if($servico->agendaServico->first())
                @foreach($servico->agendaServico->first()->datasServico()->where('data', '>=', $inicio)->where('data', '<=', $final)->get() as $agenda)
                    <tr>
                        <td>{{ $servico->nome }}</td>
                        <td>{{ $servico->fornecedor->nome_fantasia }}</td>
                        <td>{{ $agenda->data->format('d/m/Y') }}</td>
                        <td>{{ $agenda->disponivel }}</td>
                        <td>{{ $agenda->consumido }}</td>
                        <td>{{ $agenda->disponivel - $agenda->consumido }}</td>
                        <td>{{ $servico->destino->nome }}</td>
                    </tr>
                @endforeach
            @endif
        @endforeach
    </tbody>
</table>
</body>
</html>
