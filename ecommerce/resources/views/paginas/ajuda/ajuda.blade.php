@extends('template.master')

@section('title', "Central de ajuda")

@section('body')


    @include('template.navbar-clear')

        <div data-page="ajuda">
            <main class="site-content u-mtLarge">

            </main>
        </div>


    @include('template.footer')

@endsection

@section('seo')
    <meta name="description" content="Central de ajuda para tirar suas dúvidas sobre seus ingressos adquiridos." />
    <meta property="og:description" content="Central de ajuda para tirar suas dúvidas sobre seus ingressos adquiridos." />
    <meta name="twitter:description" content="Central de ajuda para tirar suas dúvidas sobre seus ingressos adquiridos." />
@endsection
