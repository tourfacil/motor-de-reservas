<div class="search-box" style="display: none;" data-controller="SearchBox">
    <button data-action="close-search" class="btn btn-not-focus" title="Fechar"><i class="iconify" data-icon="jam:close-circle"></i></button>
    <div class="d-flex align-items-center justify-content-center h-100">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-12 col-md-11 col-lg-7">
                    <form data-validate-post action="{{ route('ecommerce.servico.search') }}">
                        <input type="text" name="q" required aria-label="Pesquisar" data-required="true" data-min="3"
                               id="search" title="Pesquisar" placeholder="O que você está procurando?" autocomplete="off"
                               data-action="search" data-route="{{ route('ecommerce.servico.search-ajax') }}">
                        <span class="invalid-feedback"></span>
                        <button class="btn d-none d-md-block"><i class="iconify" data-icon="jam:search"></i></button>
                    </form>
                    <div id="autocomplete-list" class="py-2"></div>
                </div>
            </div>
        </div>
    </div>
</div>
