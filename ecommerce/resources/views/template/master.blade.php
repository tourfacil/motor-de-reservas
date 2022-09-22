<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') - Tour Fácil</title>
    <link rel="preconnect" href="{{ env('CDN_URL') }}" crossorigin>
    <link rel="preconnect" href="https://code.iconify.design" crossorigin>
    {{-- App style --}}
    <link rel="stylesheet" href="{{ mix("/css/tourfacil.css") }}">
    {{-- Favicon --}}
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <meta name="theme-color" content="#1f1f1f">
    <link rel="canonical" href="@yield('canonical', request()->url())">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- OG tags --}}
    <meta property="og:site_name" content="Tourfacil.com.br">
    <meta property="og:type" content="website">
    <meta property="og:title" content="@yield('title') - Tour Fácil">
    <meta property="og:url" content="{{ request()->url() }}">
    <meta name="twitter:site" content="TourFacil.com.br">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title') - Tour Fácil">
    <meta name="twitter:url" content="{{ request()->url() }}">
    {{-- GTM --}}
    @if(env('APP_ENV') == 'production')
        <script>
            (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0], j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','GTM-MZPD3KF');
        </script>
    @endif
    {{-- Custom Json LD --}}
    @stack('json-ld')
    {{-- SEO --}}
    @yield('seo')
    {{-- Scripts --}}
    @yield("scripts")
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
</head>
<body>
@yield('body')
{{-- Loader --}}
@include('template.loader')
<script src="{{ mix('/js/vendor.js') }}"></script>
<script src="{{ mix('/js/controllers.js') }}"></script>
<script src="{{ mix('/js/app.js') }}"></script>
<script defer src="https://code.iconify.design/1/1.0.3/iconify.min.js"></script>
</body>
</html>
