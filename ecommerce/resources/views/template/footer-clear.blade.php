<style>
    .whatsapp-button {
        position: fixed;
        width: 50px;
        height: 50px;
        bottom: 100px;
        right: 20px;
        background-color: #25d366;
        color: #FFF;
        border-radius: 50px;
        text-align: center;
        font-size: 30px;
        box-shadow: 1px 1px 2px #888;
        z-index: 1000;
        display: flex;
        justify-content: center;
        align-items: center;
        transition: transform 0.3s ease-in-out;
    }

    .whatsapp-button:hover {
        transform: scale(1.1);
        background-color: #128C7E;
        color: #FFF;
        box-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
    }
</style>
<a href="http://wa.me/{{ config('site.numero_whatsapp') }}?{{ config('site.whatsapp_text_message_parameter') }}"
    class="whatsapp-button" target="_blank" style="text-decoration: none;">
    <i class="fa fa-whatsapp"></i>
</a>
<footer class="footer mt-auto py-3">
    <div class="container">
        <div class="row d-flex align-items-center justify-content-between">
            <div class="col-12 col-sm-7 col-md-auto">
                <div class="text-muted info-empresa text-center text-sm-left">
                    <p>Expanda Viagens E Turismo LTDA</p>
                    <p>CNPJ: 34.242.332/0001-02</p>
                    <p class="m-0">Rua João Alfredo Schneider, 635</p>
                    <p class="m-0">Planalto, Gramado - RS, 95670-000 - {{ config('site.numero_whatsapp_formatado') }}
                    </p>
                    <strong>tourfacil.com.br</strong>
                </div>
            </div>
            <div class="col-12 col-sm-5 col-md-auto icons-social-media small-icons mt-4 mb-2 mb-sm-0 mt-sm-0">
                <a href="{{ config('site.facebook') }}" target="_blank" rel="nofollow"
                    title="Curta nossa página no Facebook"><span class="iconify" data-icon="jam:facebook"></span></a>
                <a href="{{ config('site.instagram') }}" target="_blank" rel="nofollow"
                    title="Siga nossa página no Instagram"><span class="iconify" data-icon="jam:instagram"></span></a>
                <a href="{{ config('site.twitter') }}" target="_blank" rel="nofollow"
                    title="Siga nossa página no Twitter"><span class="iconify" data-icon="jam:twitter"></span></a>
                <a href="{{ config('site.youtube') }}" title="Inscreva-se no nosso canal do Youtube" target="_blank"
                    rel="nofollow"><span class="iconify" data-icon="jam:youtube"></span></a>
            </div>
            <div class="col-12 col-sm-6 col-md-auto d-none d-md-block text-right">
                <img src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs="
                    class="lazyload img-fluid logo-pagamento" data-src="{{ asset('images/logo_rodape.svg') }}"
                    alt="Formas de pagamento">
            </div>
        </div>
    </div>
</footer>
