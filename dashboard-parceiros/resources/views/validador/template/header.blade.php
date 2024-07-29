@extends('validador.template.master')

@section('title', "Painel do anunciante")

@section('body')

    @include('validador.template.navbar-validador')

    @yield('content')

    @include('validador.template.footer')

@endsection
