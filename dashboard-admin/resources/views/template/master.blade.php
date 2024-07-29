<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') :: TourFácil</title>
    {{-- Import application CSS --}}
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    {{-- Google fonts --}}
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,600,700|Noto+Sans:400,700&display=swap" rel="stylesheet">
    {{-- Favicon --}}
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <meta name="theme-color" content="#2f2f2f">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="none" />
</head>
<body class="@yield('class_body')">
{{-- Import Loader --}}
@include('template.loader')
@yield('body')
{{-- Import application JS --}}
<script src="{{ mix('js/app.js') }}"></script>
<script src="{{ mix('js/vendor.js') }}"></script>
@yield("scripts")
</body>
</html>
