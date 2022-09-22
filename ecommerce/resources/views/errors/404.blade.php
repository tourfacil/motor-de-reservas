@extends('template.master')

@section('title', 'Página não encontrada')

@section('body')

    <div class="d-flex flex-column h-100" data-page="erro">
        <main class="d-flex flex-column align-items-center justify-content-center h-100 bg-light py-4">
            <div class="container">
                <i class="iconify icon-error mb-3" data-icon="jam:ghost"></i>
                <h1 class="font-weight-bold">Página não encontrada</h1>
                <p class="text-muted line-initial mb-4">A página que você procura não foi encontrada!</p>
                <a href="{{ url()->previous(route('ecommerce.index')) }}" title="Tentar novamente" class="btn btn-blue text-uppercase text-white pb-2 px-4 btn-rounded border-0">Tentar novamente</a>
            </div>
        </main>
    </div>

@endsection
