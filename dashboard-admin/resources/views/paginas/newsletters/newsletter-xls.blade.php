<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Newsletters {{ $canal_venda->nome }}</title>
</head>
<body>
<table>
    <thead>
    <tr>
        <th align="center" valign="center" height="25" bgcolor="d9d9d9">Email</th>
        <th align="left" valign="center" height="25" bgcolor="d9d9d9">Cadastrado</th>
    </tr>
    </thead>
    <tbody>
    @foreach($newsletters as $newsletter)
        <tr>
            <td align="left">{{ $newsletter->email }}</td>
            <td align="left">{{ $newsletter->created_at->format('d/m/Y H:i') }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
