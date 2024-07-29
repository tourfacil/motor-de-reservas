<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') :: Grupo Portal Gramado</title>
    {{-- Import application CSS --}}
    <link rel="stylesheet" href="{{ asset('css/validador.css') }}">
    {{-- Google fonts --}}
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.min.css">
    {{-- Favicon --}}
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}" type="image/png">
    <meta name="theme-color" content="#2f2f2f">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
{{-- Import Loader --}}
@include('validador.template.loader')
{{-- Content --}}
@yield('body')
{{-- Import application JS --}}
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/vendor.js') }}"></script>
@yield("scripts")
</body>
</html>
