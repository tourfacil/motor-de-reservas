@extends('template.master')

@section('title', 'Expirado')

@section('body')

    <div class="d-flex flex-column h-100" data-page="erro">
        <main class="d-flex flex-column align-items-center justify-content-center h-100 bg-light py-4">
            <div class="container">
                <i class="iconify icon-error mb-3" data-icon="jam:chronometer"></i>
                <h1 class="font-weight-bold">Sua sessão expirou</h1>
                <p class="text-muted line-initial mb-4">Atualize a página e tente novamente!</p>
                <a href="{{ url()->previous(route('ecommerce.index')) }}" title="Tentar novamente" class="btn btn-blue text-uppercase text-white pb-2 px-4 btn-rounded border-0">Tentar novamente</a>
            </div>
        </main>
    </div>

@endsection
