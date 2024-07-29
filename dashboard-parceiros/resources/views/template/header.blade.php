@extends('template.master')

@section('body')

    @include('template.navbar')

    <div class="page-content d-flex align-items-stretch rounded-widget">

        @include('template.sidebar')

        <div id="content-inner" class="content-inner compact">
            <div class="container-fluid">
                @yield('content')
            </div>
        </div>

    </div>

    @include('paginas.modais.filtro-periodo')

@endsection
