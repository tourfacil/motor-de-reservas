{{-- Opção para remover o back button--}}
@php($class_back = (isset($back_button) && $back_button == false) ? "d-none" : "")
<nav class="navbar navbar-expand-lg navbar-light py-2 py-lg-0">
    <div class="container flex-row">
        {{-- back button --}}
        <button data-action="voltar" class="btn btn-light mr-3 {{ $class_back }} d-lg-none" title="Voltar">
            <i class="iconify" data-icon="jam:arrow-left"></i>
        </button>
        {{-- Logo --}}
        <a class="navbar-brand mr-auto" href="{{ $url_logo ?? route('ecommerce.index') }}" title="Página inicial">
            <img src="{{ asset('images/logo_tourfacil.svg') }}" class="d-inline-block align-top" alt="Logotipo Tour Fácil">
        </a>
        {{-- nav top mobile --}}
        <ul class="navbar-nav flex-row ml-md-auto d-lg-none">
            <li class="nav-item">
                <a class="nav-link" href="http://wa.me/{{config('site.numero_whatsapp')}}" target="_blank" title="Ajuda">
                    <i class="iconify" data-icon="jam:help"></i>
                </a>
            </li>
        </ul>
        {{-- nav desktop --}}
        <div class="collapse navbar-collapse align-self-start mb-0 mb-md-3" id="menu-nav">
            <ul class="navbar-nav ml-md-auto dark-menu position-relative mt-3 mt-lg-0">
                <li class="nav-item mr-md-2">
                    <a class="nav-link" href="http://wa.me/{{config('site.numero_whatsapp')}}" target="_blank" title="Ajuda"><i class="iconify" data-icon="jam:help"></i> Ajuda</a>
                </li>
                <li class="nav-item no-hover">
                    <div class="nav-link"><i class="iconify" data-icon="jam:shield-check"></i> Ambiente seguro</div>
                </li>
            </ul>
        </div>
    </div>
</nav>
