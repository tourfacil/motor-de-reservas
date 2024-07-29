<style>
    .avaliacao-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    @media (max-width: 992px)
    {
        .avaliacao-info {
            display: block;
        }

        .stars-align {
            margin-bottom: 10px;
        }
    }

</style>


<div id="modal-avaliacao" class="jqmodal modal-lg full-mobile padding-mobile position-relative">
    {{-- Formulario para os dados dos acompanhantes --}}
    <div class="modal-loader d-none align-items-center justify-content-center flex-column">
        <div class="text-center">
            <strong class="d-block">Aguarde...</strong>
            <div class="spinner-border text-primary mt-3" role="status"></div>
        </div>
    </div>
    <form id="form-avaliacao" method="post" action="#">
        <input type="hidden" id="avaliacao_reserva_id" name="reserva_id">
        <div class="avaliacao-info">
            <div>
                <h6 class="font-weight-bold text-blue h3 mt-1 mb-1">Avalie o serviço</h6>
                <p class="text-muted">Nos conte como foi sua experiência com o serviço</p>
            </div>
            <div class="stars-align">
                <div class="stars-container">
                    <div class="stars">
                        <img id="star1" src="{{ asset('images/star0.png') }}">
                        <img id="star2" src="{{ asset('images/star0.png') }}">
                        <img id="star3" src="{{ asset('images/star0.png') }}">
                        <img id="star4" src="{{ asset('images/star0.png') }}">
                        <img id="star5" src="{{ asset('images/star0.png') }}">
                    </div>
                    <div class="starts-utils" style="display: none;">
                        <span id="link-star0">{{ asset('images/star0.png') }}</span>
                        <span id="link-star1">{{ asset('images/star1.png') }}</span>
                        <input id="star-contador" name="nota">
                    </div>
                </div>
            </div>
        </div>

        <div class="fields-input mt-1"></div>
        <div class="alert alert-danger info-error d-none"><p class="m-0"></p></div>
        <div class="modal-footer-custom row d-flex justify-content-between pb-2">

            <div class="col-12 col-sm-auto text-center text-sm-left" style="width: 100%;">
                <textarea id="avaliacao" name="avaliacao" rows="5" class="form-control" style="width: 100%;"></textarea>
            </div>
            <div class="col-12 col-sm-auto text-center text-sm-right mt-2 mt-sm-0">
                <button style="margin-top: 10px;" id="salvar-acompanhantes" type="submit" data-action="savePersons" class="btn text-uppercase btn-success btn-rounded">
                    Salvar e continuar <i class="iconify ml-1" data-icon="jam:chevron-right"></i>
                </button>
            </div>
        </div>
        <div class="d-none">@csrf</div>
    </form>
</div>
