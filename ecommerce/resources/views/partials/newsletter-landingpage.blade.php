<section data-controller="NewsletterCtrl" class="section-newsletter-landingpage py-5 lazyload"
    data-bg="{{ asset('images/fundo_newsletter.png') }}">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-lg-5 text-center text-sm-left">
                <h5 class="h1 font-weight-bold mb-3">Os melhores passeios você encontra aqui na<br
                        class="d-none d-lg-block"> TourFácil</h5>
                <p class="lead">Inscreva-se para ofertas exclusivas e dicas de viagem!</p>
            </div>
            <div class="col-lg-6 mt-4 mt-lg-0">
                <form name="newsletter" action="{{ route('ecommerce.newsletter.store') }}">
                    <div class="input-group input-group-lg">
                        @csrf
                        <input type="email" class="form-control" name="newsletter" required
                            aria-label="Email para newsletter" id="newsletter_input"
                            placeholder="Digite seu melhor email">
                        <div class="input-group-append">
                            <button class="btn btn-purple text-uppercase" type="submit">
                                Receber novidades
                            </button>
                        </div>
                    </div>
                    <div id="result-newsletter" class="text-white mt-2 d-none"></div>
                </form>
            </div>
        </div>
    </div>
</section>
