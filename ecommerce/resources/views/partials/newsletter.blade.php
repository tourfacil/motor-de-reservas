<section data-controller="NewsletterCtrl" class="section-newsletter py-5 lazyload" data-bg="{{ asset('images/fundo_newsletter.png') }}">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-lg-5 text-center text-sm-left">
                <h5 class="h1 font-weight-bold">Receba nossas <br class="d-none d-lg-block"> novidades</h5>
            </div>
            <div class="col-lg-6 mt-4 mt-lg-0">
                <form name="newsletter" action="{{ route('ecommerce.newsletter.store') }}">
                    <div class="input-group input-group-lg">
                        <div class="input-group-prepend d-none d-sm-flex">
                            <span class="input-group-text border-0 bg-white">
                                <i class="iconify text-black-50" data-icon="jam:envelope"></i>
                            </span>
                        </div>
                        @csrf
                        <input type="email" class="form-control pl-sm-0" name="newsletter" required
                               aria-label="Email para newsletter" id="newsletter_input" placeholder="Digite seu melhor email">
                        <div class="input-group-append">
                            <button class="btn btn-purple text-uppercase">
                                <span>Cadastrar</span>
                                <span class="spinner-border spinner-border-sm ml-2 align-baseline d-none"></span>
                            </button>
                        </div>
                    </div>
                    <div id="result-newsletter" class="text-white mt-2 d-none"></div>
                </form>
            </div>
        </div>
    </div>
</section>
